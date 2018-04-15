<?php
namespace Cloud\Files\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use PM\_CLOUD\FolderModel;
use PM\_CLOUD\FileModel;
use PM\_CLOUD\FileMetaModel;
use PM\_CLOUD\FileSourceModel;

class UEditorController extends FilesController {
    use \Cloud\Files\Controllers\traits\authorities;
    
    private static $stateMap = [
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确",
        "INVALID_URL" => "非法 URL",
        "INVALID_IP" => "非法 IP"
    ];

    protected static function getFolderID($options){
        if($folder = FolderModel::postIfNotExists(5, '__UEditor')){
            $id = $folder['id'];
            $array = explode('/', date('Y/m/d', $_SERVER['REQUEST_TIME']));
			foreach($array as $name){
                if($folder = FolderModel::postIfNotExists($id, $name)){
                    $id = $folder['id'];
                }else{
                    new Status(400);
                }
            }
        }else{
            $id = 5;
        }
		return $id;
    }

    protected $config;

    protected function returnUploadsData($options, bool $is2ndPass = false){
        if(count($this->successed[$options['__fieldName']])){
            $file = $this->successed[$options['__fieldName']][0];
            $data= [
                "state"     =>  static::$stateMap[0],                                                     //上传状态，上传成功时必须返回"SUCCESS"
                "url"       =>  __aurl__.'uploads/files/'.$file["ID"].'.'.$file['FILE_EXTN'],              //返回的地址
                "title"     =>  $file["FILE_NAME"],                                                     //新文件名
                "original"  =>  $file["FILE_NAME"],                                                     //原始文件名
                "type"      =>  ".".$file["FILE_EXTN"],                                              //文件类型
                "size"      =>  $file["FILE_SIZE"]                                                      //文件大小
            ];
        }else{
            if(count($this->successed[$options['__fieldName']])){
                $data= [
                    "state"     =>  static::$stateMap[$this->failed[$options['__fieldName']][0]['error']]
                ];
            }else{
                $data= [
                    "state"     =>  static::$stateMap['ERROR_FILE_NOT_FOUND']
                ];
            }            
        }
		$result = json_encode($data);
        /* 输出结果 */
        if (isset($options["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
    

    public function get($id = NULL, array $options = []){
        $CONFIG = $this->app->xProps['Config'];
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
        
            /* 列出图片 */
            case 'listimage':
                $result = $this->getFiles("`SK_IS_RECYCLED` = '0' AND `FILE_TYPE` = 'image'", $CONFIG['fileManagerListSize']);
                break;

            /* 列出文件 */
            case 'listfile':
                $result = $this->getFiles("`SK_IS_RECYCLED` = '0'", $CONFIG['imageManagerListSize']);
                break;
            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }
        echo $result;
    }

    protected function getFiles($requires, $listSize){
        $files = [];
        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $objs = FileMetaModel::query($requires, FileMetaModel::MTIME_DESC, [$start, $size]);
        foreach ($objs as $file) {
            $files[] = array(
                'url'   =>  __aurl__.'uploads/files/'.$file["ID"].'.'.$file['FILE_EXTN'],
                'mtime' =>  $file["SK_MTIME"]
            );
        }
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => [],
                "start" => $start,
                "total" => 0
            ));
        }
        return json_encode(array(
            "state" => "SUCCESS",
            "list" => $files,
            "start" => $start,
            "total" => count($files)
        ));
    }

    public function post($id = NULL, array $options = []){
        // var_dump($_GET, $_POST, $_FILES);
        $CONFIG = $this->app->xProps['Config'];
        $action = $_GET['action'];
        switch ($action) {
            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                return $this->upload($CONFIG, $options);
            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }
    }

    protected function upload($CONFIG, $options){
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':
                $this->config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $this->config = array(
                    "pathFormat" => $CONFIG['scrawlPathFormat'],
                    "maxSize" => $CONFIG['scrawlMaxSize'],
                    "allowFiles" => ['.png'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $this->config = array(
                    "pathFormat" => $CONFIG['videoPathFormat'],
                    "maxSize" => $CONFIG['videoMaxSize'],
                    "allowFiles" => $CONFIG['videoAllowFiles']
                );
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $this->config = array(
                    "pathFormat" => $CONFIG['filePathFormat'],
                    "maxSize" => $CONFIG['fileMaxSize'],
                    "allowFiles" => $CONFIG['fileAllowFiles']
                );
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }
        $this->successed[$fieldName] = $this->failed[$fieldName] = [];
        if(isset($_FILES[$fieldName])){
            $this->postFile($fieldName, $_FILES[$fieldName], $options);
        }elseif($base64==='base64'){
            $base64Data = $_POST[$fieldName];
            $img = base64_decode($base64Data);
            $this->postFile($fieldName, [
                'name'      =>  'scrawl.png',
                'type'      =>  'image/png',
                'tmp_name'  =>  NULL,
                'blob'      =>  $img,
                'size'      =>  strlen($img),
                'error'     =>  0
            ], $options);
        }
        $options['__fieldName'] = $fieldName;
        return $this->returnUploadsData($options);
    }

    public function put($id, array $options = []){
		new Status(1411.3, ture);
	}
	
	public function delete($id, array $options = []){
        new Status(1411.4, ture);
	}
}