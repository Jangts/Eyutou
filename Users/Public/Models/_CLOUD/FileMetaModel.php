<?php
namespace PM\_CLOUD;

/**
 * System File Resource Lite Data
 * 系统文件资源轻数据
 * 是一个忽略五类文件资源数据差异的精简的单一标准模型
**/
final class FileMetaModel extends BaseCloudItemModel {
	const
	CTIME_DESC = [['SRC_ID', true, self::SORT_REGULAR]],
	CTIME_ASC = [['SRC_ID', false, self::SORT_REGULAR]],
	MTIME_DESC = [['SK_MTIME', true, self::SORT_REGULAR]],
	MTIME_ASC = [['SK_MTIME', false, self::SORT_REGULAR]],
	FSIZE_DESC = [['FILE_SIZE', true, self::SORT_REGULAR]],
	FSIZE_ASC = [['FILE_SIZE', false, self::SORT_REGULAR]],
	FTYPE_DESC = [['SUFFIX', true, self::SORT_REGULAR]],
	FTYPE_ASC = [['SUFFIX', false, self::SORT_REGULAR]],
	FNAME_DESC = [['FILE_NAME', true, self::SORT_REGULAR]],
	FNAME_ASC = [['FILE_NAME', false, self::SORT_REGULAR]],
	FNAME_DESC_GBK = [['FILE_NAME', true, self::SORT_CONVERT_GBK]],
	FNAME_ASC_GBK = [['FILE_NAME', false, self::SORT_CONVERT_GBK]];
	
	protected static
	$fileStoragePath = DPATH.'CLOUDS/files/',
	$querier,
    $staticMemorizeStorage = [],
	$staticFileStorage,
	$tablenameAlias = 'filemeta',
	$defaultPorpertyValues = [
		'ID'         		=>  '',
		'SRC_ID'        	=>  0,
		'FOLDER'        	=>  6,
		'FILE_NAME'     	=>  '',
		'FILE_TYPE'     	=>  'archive',
		'FILE_SIZE'     	=>  0,
		'SUFFIX'        	=>  '',
		'SK_MTIME'   		=>  DATETIME,
        'SK_IS_RECYCLED' 	=>  0
    ];

	private static function correctFileTypeQuerying($type = NULL){
		if($type&&$type!=='all'){
			switch($type){
				case 'img':
				$type = 'image';
				break;

				case 'txt':
				$type = 'text';
				break;

				case 'vod':
				$type = 'video';
				break;

				case 'wav':
				$type = 'audio';
				break;
			}
			self::initQuerier()->requires()->where('FILE_TYPE', $type);
		}
	}
	/**
	 * 检查文件名
	 * 此方法还会自动校正文件名后缀
	 * 也就是说，YangRAM Cloud并不接受修改后缀名
	 * 如果试图修改后缀名，系统会自动把原后缀名不在后面
	 */
	private static function correctFileName($ID, $suffix, $folder, $id){
		// 剥离基础名
		if($suffix){
			$basename = preg_replace('/\\.'.$suffix.'$/', '', $ID);
			$suffix = '.'.$suffix;
		}else{
			$basename = $ID;
			$suffix = '';
		}

		$ID = $basename . $suffix;
		$result = self::$querier->requires()->where('FILE_NAME', $ID)->where('FOLDER', $folder)->where('SK_IS_RECYCLED', 0)->where('ID', $id, '<>')->take(1)->orderby(false)->count('ID');
		$num = 1;
		while($result){
			$ID = $basename . '(' . $num++ . ')' . $suffix;
			$result = self::$querier->requires()->where('FILE_NAME', $ID)->where('FOLDER', $folder)->where('SK_IS_RECYCLED', 0)->where('ID', $id, '<>')->take(1)->orderby(false)->count('ID');
		}
		return $ID;
	}

	public static function getSplitFileNameArray($filename, $mime = NULL){
		$array = explode('.', $filename);
		if(count($array)===1){
			return [$filename, ''];
		}
		$suffix = array_pop($array);
		$basename = implode('.', $array);
		if($mime==NULL){
			return [$basename, $suffix];
		}
		$array = explode('/', $mime);
		$type = $array[0];
		if($type==='application'){
			switch($suffix){
				case 'doc':
				case 'docx':
				case 'xls':
				case 'xlsx':
				case 'ppt':
				case 'pptx':
				case 'pdf':
				$type = 'document';
				break;
				case 'zip':
				case 'rar':
				case 'tar':
				case 'cab':
				case 'uue':
				case 'jar':
				case 'iso':
				case 'z':
				case '7-zip':
				case 'ace':
				case 'lzh':
				case 'arj':
				case 'gzip':
				case 'bz2':
				$type = 'compressed';
				break;
			}
		}
		return [$basename, $suffix, $type];
	}

	public static function getCOUNT($type = NULL){
		self::correctFileTypeQuerying($type);
		return self::$querier->count('ID');
	}

	public static function getCountOfSrouce($SRC_ID){
		return self::initQuerier()->requires("`SRC_ID` = $SRC_ID")->count('ID');;
	}

	public static function getALL($type = NULL){
		self::correctFileTypeQuerying($type);
		return self::$querier->select();
	}

	public static function getFilesBySrouceID($SRC_ID, $take = 0){
		self::initQuerier();
		if(is_numeric($take)){
			return self::query("`SRC_ID` = $SRC_ID", self::CTIME_ASC, [0, $take]);
		}
		return self::query("`SRC_ID` = $SRC_ID", self::CTIME_ASC);
	}

	/**
	 * 获取文件夹中的文件
	 * 只会返回正常的文件，已移除的和被隐藏的不会返回
	 */
	public static function getFilesByFolderID($FOLDER, $orderby = self::CTIME_ASC){
		self::initQuerier();
		return self::query("`FOLDER` = $FOLDER AND `SK_IS_RECYCLED` = 0", $orderby);
	}

	public static function byGUID($ID){
		if($ID){
			self::init();
			$obj = new static;
			if(isset(self::$staticMemorizeStorage[$ID])){
				$obj->modelProperties = $obj->savedProperties = self::$staticMemorizeStorage[$ID];
			}elseif($cache = self::$staticFileStorage->take($ID)){
				$obj->modelProperties = $obj->savedProperties = self::$staticMemorizeStorage[$ID] = $cache;
			}else{
				$result = self::$querier->requires()->where('ID', $ID)->take(1)->select();
				if($result&&$row = $result->item()){
					self::$staticFileStorage->store($ID, $obj->modelProperties = $obj->savedProperties = self::$staticMemorizeStorage[$ID] = $row);
				}else{
					return NULL;
				}
			}
			return $obj;
		}
		return NULL;
	}

	/**
	 * 创建新文件
	 */
	public static function create(array $input){
		$obj = new self;
		return $obj->__put($input);
	}

	/**
	 * 更新文件信息
	 */
	public static function updateBySource($SRC_ID, array $input){
		self::initQuerier();
		$objs = self::query(['SRC_ID' => $SRC_ID]);
		foreach($objs as $obj){
			$obj->put($input);
		}
		return $objs;
	}

	/**
	 * 按条件批量移除或隐藏文件
	 */
	public static function remove($require, $recycleType = self::RECYCLE){
		self::initQuerier();
		$__key = self::$querier->beginAndLock();
		$objs = self::query($require);
		foreach($objs as $obj){
			if($obj->recycle($recycleType)==false){
				self::$querier->unlock($__key)->rollBack();
                return false;
			}
		}
		self::$querier->unlock($__key)->commit();
		return $objs;
	}

	/**
	 * 按条件批量删除文件
	 */
	public static function delete($require){
		return FileModel::delete($require);
	}

	public function __construct(){
		self::init();
		$this->modelProperties = static::$defaultPorpertyValues;
	}

	protected function __put(array $input, $isSaved = false){
    	foreach(static::$defaultPorpertyValues as $key=>$val){
        	if(array_key_exists($key, $input)){
				$this->modelProperties[$key] = $input[$key];
			}
		}
        if($isSaved){
			$this->__guid = $this->modelProperties['ID'];
            $this->savedProperties = $this->modelProperties;
        }
		return $this;
	}

	public function put(array $input){
		return $this->__put($input);
	}

	/**
	 * 重命名
	 * 并返回校正后（如果是已存在的文件）的文件名
	 */
	public function rename($newname){
		$this->modelProperties['FILE_NAME'] =	$newname;
		$this->savedProperties&&$this->save();
		return $this->modelProperties['FILE_NAME'];
	}

	/**
	 * 秒传拷贝
	 */
	public function getCopy(){
		$obj = new self;
		$source = byGUID($modelProperties['SRC_ID']);
		$modelProperties = $this->modelProperties;
		$modelProperties['ID'] = substr(substr($source->HASH, 8, 16).intval(BOOTTIME).uniqid(), 0, 44);
		return $obj->__put($modelProperties)->__insert();
	}

	/**
	 * 移动文件夹
	 */
	public static function moveto($require, $folder){
        $objs = self::query($require);
		foreach($objs as $obj){
			$obj->FOLDER = $folder;
			$obj->save();
		}
		return $objs;
	}

	public function correctFolder(){
		if($this->modelProperties['FOLDER']<6){
			if($this->savedProperties['FOLDER']>5){
				$this->modelProperties['FOLDER'] = $this->savedProperties['FOLDER'];
			}else{
				$this->modelProperties['FOLDER'] = 6;
			}
		}
		if($folder = FolderModel::byGUID($this->modelProperties['FOLDER'])){
			if($folder->type==='file'){
				if($folder->SK_IS_RECYCLED){
					// 检查是否应该隐藏
					$this->modelProperties['SK_IS_RECYCLED'] = self::HIDE;
				}
			}else{
				$this->modelProperties['FOLDER'] = 6;
			}
		}else{
			$this->modelProperties['FOLDER'] = 6;
		}
		return $this->modelProperties['FOLDER'];
	}

	public function __update(){
		$querier = self::$querier;

		// 校正文件夹
		$this->correctFolder();
		// 矫正文件名
		$this->modelProperties['FILE_NAME'] = self::correctFileName($this->modelProperties['FILE_NAME'], $this->modelProperties['SUFFIX'], $this->modelProperties['FOLDER'], $this->modelProperties['ID']);
		// 更新修改时间
		$this->modelProperties['SK_MTIME']   =	DATETIME;

        if(empty($this->modelProperties['ID'])){
			return false;
		}
		// 移除不可改键
		unset($this->modelProperties['ID']);
		unset($this->modelProperties['FILE_TYPE']);
		unset($this->modelProperties['SUFFIX']);
		$diff = self::array_diff($this->savedProperties, $this->modelProperties, self::DIFF_SIMPLE);
		// 不支持同时更改源和名称与文件夹等信息
		if(isset($diff['FILE_NAME'])||isset($diff['FOLDER'])){
			$this->modelProperties['SRC_ID'] = $this->savedProperties['SRC_ID'];
			if(isset($diff['SRC_ID'])){
				unset($diff['SRC_ID']);
			}
			if(isset($diff['FILE_SIZE'])){
				unset($diff['FILE_SIZE']);
			}
		}
		// 
		$update = $diff['__M__'];
		$this->modelProperties['ID'] = $this->savedProperties['ID'];
		$this->modelProperties['FILE_TYPE'] = $this->savedProperties['FILE_TYPE'];
		$this->modelProperties['SUFFIX'] = $this->savedProperties['SUFFIX'];
		if(count($update)==0){
			return true;
		}
		if($querier->requires()->where('ID', $this->savedProperties['ID'])->update($update)){
			$this->savedProperties = $this->modelProperties;
		}else{
			return false;
		}
        self::$staticFileStorage->store($this->__guid);
        return $this;
	}

	public function __insert(){
		$querier = self::$querier;

		// 校正文件夹
		$this->correctFolder();
		// 矫正文件名
		$this->modelProperties['FILE_NAME'] = self::correctFileName($this->modelProperties['FILE_NAME'], $this->modelProperties['SUFFIX'], $this->modelProperties['FOLDER'], $this->modelProperties['ID']);
		// 更新修改时间
		$this->modelProperties['SK_MTIME']   =	DATETIME;
        if(!$querier->insert($this->modelProperties)){
            return false;
        }
        $this->savedProperties = $this->modelProperties;
        return $this;
	}

	/**
	 * 拓展为完整的文件模型
	 */
	public function getExtendedModel(){
		return FileModel::byGUID($this->modelProperties['ID']);
	}
	
	/**
	 * 移除或隐藏文件
	 */
	public function recycle($recycleType = self::RECYCLE){
		$recycleType = in_array($recycleType, [0, 1, 2]) ? $recycleType : 1;
		$this->modelProperties['SK_IS_RECYCLED'] =	$recycleType;
		if($this->savedProperties){
			return $this->save();
		}
		return $this;
	}
	/**
	 * 删除文件资源
	 */
	public function destroy(){
		return FileModel::byGUID($this->modelProperties['ID'])->destroy();
	}
}