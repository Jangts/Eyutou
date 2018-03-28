<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

// 引入相关命名空间，以简化书写
use Status;

/**
 * Universal Responser
 * 通用响应对象
 * 仿单例类，其实例是一个可控响应，不限制调用者，被抢先实例化后仍可以被修改状态
 * 负责辅助应用响应客户端（包括浏览者端在内的两口一端，不包括维运人员端和测试员端）
**/
final class ResponseModel implements interfaces\model {
    use traits\magic;
	use traits\arraylike;
    use traits\conversion;
     
    const
    OK      =   200,
    MV      =   301,
    SO      =   303,
    NM      =   304,
    FB      =   403,
    NF      =   404,
    UA      =   700,
    
    ALL     =   'application/octet-stream',
    AVI     =   'video/x-msvideo',
    CSS     =   'text/css',
    DOC     =   'application/msword',
    DOCX    =   'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    GIF     =   'image/gif',
    HTML    =   'text/html',
    JPG     =   'image/jpeg',
    JPEG    =   'image/jpeg',
    JS      =   'text/javascript',
    JSON    =   'application/json',
    MOV     =   'video/quicktime',
    MP3     =   'audio/mpeg',
    MP4     =   'video/mp4',
    MPEG    =   'audio/mpeg',
    OGG     =   'audio/ogg',
    PDF     =   'application/pdf',
    PNG     =   'image/png',
    PPT     =   'application/vnd.ms-powerpoint',
    PPTX    =   'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    PPS     =   'application/vnd.ms-powerpoint',
    PPSX    =   'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
    RAR     =   'application/x-rar-compressed',
    TXT     =   'text/plain',
    WAV     =   'audio/wav',
    WMV     =   'video/x-ms-wmv',
    XLX     =   'application/vnd.ms-excel	application/x-excel',
    XLSX    =   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    XML     =   'text/xml',
    ZIP     =   'application/x-zip-compressed',
    
    TPL_GREEN_CHANNEL 	= CPATH.'THEME/200.php',
	TPL_400 			= CPATH.'THEME/400.php',
	TPL_YELLOW 			= CPATH.'THEME/400.php',
	TPL_404 			= CPATH.'THEME/404.php',
	TPL_ASHY_CHANNEL 	= CPATH.'THEME/404.php',
	TPL_1400 			= CPATH.'THEME/1400.php',
	TPL_NACARAT 		= CPATH.'THEME/1400.php',
	TPL_1401 			= CPATH.'THEME/1401.php',
	TPL_RED 			= CPATH.'THEME/1401.php',
	TPL_1111 			= CPATH.'THEME/1111.php',
	TPL_AZURE			= CPATH.'THEME/1111.php',
	TPL					= CPATH.'THEME/1111.php',
	TPL_1422 			= CPATH.'THEME/1422.php',
	TPL_MAGENTA			= CPATH.'THEME/1422.php',
	TPL_PHP_ERRORS 	    = CPATH.'THEME/1500.php';

    private static $instance = NULL;

    public static function instance($spCode = NULL, $type = self::HTML){
        if(self::$instance===NULL){
            $spCode = is_numeric($spCode) ? $spCode : 200;
            self::$instance = new self($spCode);
        }else{
            if(is_numeric($spCode) && isset(Status::$codes[$spCode])){
                self::$instance->STATUS = $spCode;
            }
        }
        self::$instance->MIME = $type;
        return self::$instance;
    }

    public static function trimServerFilename($filename){
		$filename = str_replace('\\', '/', $filename);
		if(strpos($filename, APATH)===0){
			$filename = str_replace(APATH, '<%A%>', $filename);
        }
        elseif(strpos($filename, __ROOT__.__BOOT__)===0){
			$filename = '<%BOOTSTRAP%>';
		}
        elseif(strpos($filename, DBF_PATH)===0){
			$filename = str_replace(DBF_PATH, '<%D%>', $filename);
		}
        elseif(strpos($filename, CPATH)===0){
			$filename = str_replace(CPATH, '<%K%>', $filename);
        }
        elseif(strpos($filename, RUNPATH)===0){
			$filename = str_replace(RUNPATH, '<%R%>', $filename);
        }
		elseif(strpos($filename, I4s_PATH)===0){
			$filename = str_replace(I4s_PATH, '<%S%>', $filename);
        }
        elseif(strpos($filename, USR_PATH)===0){
			$filename = str_replace(USR_PATH, '<%U%>', $filename);
		}
		elseif(strpos($filename, APP_PATH)===0){
			$filename = str_replace(APP_PATH, '<%X%>', $filename);
		}
		elseif(strpos($filename, __ROOT__)===0){
			$filename = str_replace(__ROOT__, '<%/%>', $filename);
		}else{
			$filename = '<%******%>';
		}
		return $filename;
	}

	public static function restoreServerFilename($filename){
		$filename = str_replace('\\', '/', $filename);
		if(strpos($filename, '<%A%>')===0){
			$filename = str_replace('<%A%>', APATH, $filename);
        }
        elseif(strpos($filename, '<%BOOTSTRAP%>')===0){
			$filename = str_replace('<%BOOTSTRAP%>', __ROOT__.__BOOT__, $filename);
        }
        elseif(strpos($filename, '<%D%>')===0){
			$filename = str_replace('<%D%>', DBF_PATH, $filename);
        }
		elseif(strpos($filename, '<%K%>')===0){
			$filename = str_replace('<%K%>', CPATH, $filename);
        }
        elseif(strpos($filename, '<%R%>')===0){
			$filename = str_replace('<%D%>', RUNPATH, $filename);
        }
		elseif(strpos($filename, '<%S%>')===0){
			$filename = str_replace('<%S%>', I4s_PATH, $filename);
		}
		elseif(strpos($filename, '<%U%>')===0){
			$filename = str_replace('<%U%>', USR_PATH, $filename);
		}
		elseif(strpos($filename, '<%X%>')===0){
			$filename = str_replace('<%X%>', APP_PATH, $filename);
		}
		elseif(strpos($filename, '<%/%>')===0){
			$filename = str_replace('<%/%>', __ROOT__, $filename);
		}
		return $filename;
	}

    public static function moveto($url, $code = 303){
        if($code < 300||$code >= 400){
            $code = 303;
        }
        header("HTTP/1.1 303 Moved Permanently");
        header("Location:".$url);
        exit;
    }

    public static function renderStatus($title, $alias, $code, $pre, $message, $place){
        $icon = __BURL__.'/icon.php?o';
        echo <<<HTML
<!doctype html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>$title</title>
<style>
* { position: relative; margin: 0; padding: 0; border: none; }
body { color: #FFF; font-family: 'Microsoft Yahei', 'Microsoft Sans Serif', 'Hiragino Sans GB', 'sans-serif'; font-weight: lighter; }
.main { width: 80%; max-width: 800px; height: 80%; padding: 10%; cursor: default; }
.main > header.text-icon { width: 180px; height: 180px; letter-spacing: .2em; text-align: center; color: #FFF; line-height: 162px; font-size: 144px; }
.main > header.text-icon { background: url($icon) center no-repeat; }
.main > header.text-icon { width: 150px; height: 150px; padding: 15px; letter-spacing: 0; line-height: 132px; font-size: 114px; }
.main > article { margin-top: 20px; font-size: 14px; }
.main > article > header { border-bottom: #FFF 1px solid; margin-bottom: 10px; padding: 5px 0; color: #FFF;}
.main > article > header > strong { font-family: Impact; letter-spacing: .1em; font-size: 36px; line-height: 30px; font-weight: lighter; }
.main > article > header > span { font-weight: lighter; font-size: 21px; }
.main > article > header > span:before { content: "/"; margin: 0 3px 0 2px;}
.main > article > p { line-height: 24px; text-align: justify; margin-top: 10px; }
.main > article > ol { list-style-position: inside; }
.main > article > ol > li { line-height: 18px; text-align: justify; margin-top: 5px; }
.main > footer { min-height: 30px; max-height: 60px; overflow: hidden; margin-top: 10px; }
.main > footer { text-align: left; font-size: 12px; line-height: 30px; border-bottom: #FFF 1px solid; }
.main > footer > .alias { float: right; font-size: 16px; text-align: right; }

body { background: #33A5DD;	background: rgba(51,165,221,1); }
body > div > article { ccolor: #CEF; }
body > div > footer { border-top: #3DF 1px dashed; color: #3DF; }
</style>
</head>
<body>
<div class="main">
    <header class="text-icon">:&nbsp;(</header>
    <article><header><strong>$code</strong><span>$title</span></header>$message</article>
    <footer><span class="place">$place</span><span class="alias">$alias</span></footer>
</div>
</body>
</html>
HTML;
    }

    private
    $spCode = 200,
    $headers = [
        'NI-Response-Code' => 200
    ],
    $irreplaceable = [],
    $modelProperties = [
        'STATUS'    =>  200,
        'MIME'      =>  'text/html',
        'CHARSET'   =>  'utf-8'
    ];

    private function __construct($spCode){
        $this->statusCode = (string)$spCode;
        $this->headers['NI-Response-Code'] = (string)$spCode;
        $spCode = intval($spCode);
        if(isset(Status::$codes[$spCode])){
            if(($spCode>=200&&$spCode<300)||$spCode==304||$spCode==403||$spCode==404){
                $this->modelProperties['STATUS'] = $spCode;
                $this->modelProperties['MESSAGE'] = Status::$codes[$spCode];
            }else{
                $this->modelProperties['STATUS'] = 200;
                $this->modelProperties['MESSAGE'] = Status::$codes[200];
            }
        }else{
            $this->modelProperties['STATUS'] = 404;
            $this->modelProperties['MESSAGE'] = Status::$codes[404];
        }
        
    }

    public function get($name){
        if(isset($this->modelProperties[$name])){
            return $this->modelProperties[$name];
        }
        return NULL;
    }

	public function set($name, $value){
        if($name==='STATUS'){
            $this->statusCode = (string)$value;
            $this->headers['NI-Response-Code'] = (string)$value;
            $spCode = intval($value);
            if(isset(Status::$codes[$value])){
                if(($spCode>=200&&$spCode<300)||$spCode==304||$spCode==404){
                    $this->modelProperties['STATUS'] = $spCode;
                    $this->modelProperties['MESSAGE'] = Status::$codes[$spCode];
                }else{
                    $this->modelProperties['STATUS'] = 200;
                    $this->modelProperties['MESSAGE'] = Status::$codes[200];
                }
            }
        }
        if(in_array($name, ['MIME', 'CHARSET'])){
            $this->modelProperties[$name] = $value;
        }
    }

    /**  
	 * 删键方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return 回调函数无返值
	**/ 
    final public function uns($name){
        if(is_array($this->modelProperties)&&array_key_exists($name, $this->modelProperties)){
            unset($this->modelProperties[$name]);
        }
        return $this;
	}

    public function setHeaders($headers){
        if(is_array($headers)){
            $this->headers = $headers;
            $this->headers['NI-Response-Code'] = $this->statusCode;
        }
        return $this;
    }

    public function setHeader($name, $value, $replace = false){
        if(!!$replace){
            $this->headers[(string)$name] = (string)$value;
        }else{
            $this->irreplaceable[] = sprintf('%s: %s', $name, $value);
        }
        return $this;
    }

    public function setResourceCache($expires = 3153600000,  $cactrl = 'public'){
        $this->setHeader('Cache-Control', $cactrl)
            ->setHeader('Cache-Control', 'max-age='.$expires)
            ->setHeader('Expires', preg_replace('/.{5}$/', 'GMT', gmdate('r', intval(time() + $expires))))
            ->setHeader('Last-Modified', gmdate("D, d M Y H:i:s", time()).' GMT');
        return $this;
    }

    public function getHeaders(){
        $headers = [];
        foreach ($this->headers as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        }
        $str = $_SERVER['SERVER_PROTOCOL']."\s".$this->modelProperties['STATUS']."\s".$this->modelProperties['MESSAGE'];
        $str .= join("\r\n", $headers);
        $str .= join("\r\n", $this->irreplaceable);
        return $str;
    }

    final public function str(){
        return $this->getHeaders();
    }

    public function sendHeaders(){
        $body = ob_get_clean();
        header($_SERVER['SERVER_PROTOCOL']." ".$this->modelProperties['STATUS']." ".$this->modelProperties['MESSAGE']);
        header(sprintf("Content-Type: %s;charset=%s", $this->modelProperties['MIME'], $this->modelProperties['CHARSET']));
        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }
        foreach ($this->irreplaceable as $value) {
            header($value);
        }
        return $body;
    }

    public function send($body = NULL, $usePrevious = false){
        $cache = $this->sendHeaders();
        if(is_string($body)===false){
            $body = '';
        }
        if($usePrevious){
            echo $cache . $body;
        }else{
            echo $body;
        }
        die;
    }

    public function render($template, array $modelProperties = [], $usePrevious = false){
        if(is_string($template)&&is_file($template)){
            if(!is_array($modelProperties)){
                $modelProperties = [];
            }
            $cache = $this->sendHeaders();
            if($usePrevious){
                echo $cache;
            }
            extract($modelProperties, EXTR_PREFIX_SAME, 'CSTM');
            include $template;
        }else{
            if(isset($_SERVER['HTTP_ACCEPT'])&&(strpos($_SERVER['HTTP_ACCEPT'], 'html')||strpos($_SERVER['HTTP_ACCEPT'], 'xml'))){
				$this->modelProperties['MIME'] = self::XML;
				$this->sendHeaders();
				echo ObjectModel::arrayToXml($modelProperties);
			}else{
                $this->modelProperties['MIME'] = self::JSON;
                $this->sendHeaders();
                echo json_encode($modelProperties);
            }
            die;
        }
    }

    final public function has($name){
        if($this->get($name, $this->modelProperties)){
            return true;
        }
        return false;
    }
}