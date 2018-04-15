<?php
namespace PM\_CLOUD;

/**
 * Model Of Attached Resource Infomation
 * 资源资源信息模型
**/
class FileModel extends BaseCloudItemModel {
	use traits\transfer;

	protected static
	$extendedProperties = [],
	$fileStoragePath = DPATH.'CLOUDS/files/',
	$staticQuerier,
	$staticMemorizeStorage = [],
	$staticFileStorage,
	$tablenameAlias = 'filemeta_public';
	
	/**
	 * 真实删除
	 * delete是发起删除，除了删除文件外，还要做一些检查工作，比如是否有必要删除源等
	 */
	protected static function deleteFileMeta($id){
		static::initQuerier();
		if(static::$staticFileStorage){
			static::$staticFileStorage->store($id);
		}
		if(static::$staticQuerier->requires()->where('ID', $id)->delete()!==false){
			return true;
		}
		return false;
	}

	public static function byGUID($id){
		if($meta = FileMetaModel::byGUID($id)){
			if($source = FileSourceModel::byGUID($meta->SRC_ID)){
				$obj = new static;
				$obj->meta = $meta;
				$obj->source = $source;
				return  $obj->__put(array_merge($meta->getArrayCopy(), $source->getArrayCopy()), true);
			}
			static::deleteFileMeta($id);
		}
	}

	public static function post(array $input){
		list($basename, $extn, $type) = FileMetaModel::getSplitFileNameArray($input['FILE_NAME'], $input['MIME']);
		$srcInput = FileSourceModel::completeInput([
			'MIME'              =>  $input['MIME'],
			'DURATION' 	        =>	isset($input['DURATION']) ? $input['DURATION'] : 0,
			'WIDTH' 	        =>	isset($input['WIDTH']) ? $input['WIDTH'] : 0,
			'HEIGHT' 	        =>	isset($input['HEIGHT']) ? $input['HEIGHT'] : 0,
			'tmp_name'          =>  isset($input["tmp_name"]) ? $input["tmp_name"] : '',
			'blob'				=>  isset($input["blob"]) ? $input["blob"] : ''
		], $extn, $type);

		$metaInput = [
			'FOLDER'        	=>  $input['FOLDER'],
			'FILE_NAME'     	=>  $input['FILE_NAME'],
			'FILE_TYPE'     	=>  $type,
			'FILE_SIZE'     	=>  $input['FILE_SIZE'],
			'FILE_EXTN'        	=>  $ext
		];
		if($obj = FileModel::postByMateinfoAndSource($metaInput, $srcInput)){
			return $obj;
		}
		return false;
	}

	public static function postByMateinfoAndSource(array $metaInput, array $sourceInput){
		#使用事务
		#开启事务
		static::initQuerier();
		$__key = static::$staticQuerier->beginAndLock();
		if($source = FileSourceModel::postIfNotExists($sourceInput)){
			$metaInput['SRC_ID'] = $source->SID;
			if(isset($metaInput['ID'])){
				$id = $metaInput['ID'];
				$meta = new FileMetaModel($metaInput['ID']);
			}else{
				$metaInput['ID'] = substr(substr($source->HASH, 8, 16).intval(BOOTTIME).uniqid(), 0, 44);
				$meta = FileMetaModel::create($metaInput);
			}
			if($meta->put($metaInput)->save()){
				#提交事务
				static::$staticQuerier->unlock($__key)->commit();
				$obj = new static;
				$obj->meta = $meta;
				$obj->source = $source;
				return $obj->__put(array_merge($meta->getArrayCopy(), $source->getArrayCopy()), true);
			}
		}
		#回滚事务
		static::$staticQuerier->unlock($__key)->rollBack();
		\unlink(PUBL_PATH.$sourceInput['LOCATION']);
		return false;
	}

	public static function updateByID($id, array $input){
		#使用事务
		static::initQuerier();
		$__key = static::$staticQuerier->beginAndLock();
		if($source = FileSourceModel::postIfNotExists($input)){
			$input['SRC_ID'] = $source->SID;
			$meta = new FileMetaModel($id);
			//var_dump($id, $meta);
			if($meta->put($input)->save()){
				//var_dump($meta);
				static::$staticQuerier->unlock($__key)->commit();
				$obj = new static;
				$obj->meta = $meta;
				$obj->source = $source;
				return $obj->__put(array_merge($meta->getArrayCopy(), $source->getArrayCopy()), true);
			}
		}
		static::$staticQuerier->unlock($__key)->rollBack();
		return false;
	}

	/**
	 * 按指定条件批量移除或隐藏文件资源
	 */
	public static function remove($require, $recycleType = self::RECYCLE){
		if($metaobjs = FileMetaModel::remove($require, $recycleType)){
			$objs = [];
			foreach($metaobjs as $meta){
				$objs[] = $meta->getExtendedModel();
			}
			return $objs;
		}
		return ;
	}

	/**
	 * 按指定条件批量删除文件资源
	 */
	public static function delete($require){
		static::initQuerier();
        $__key = static::$staticQuerier->beginAndLock();
		$metainfos = static::query($require);
		foreach($metainfos as $meta){
            if($obj = $meta->extendedProperties()){
                $obj->destroy();
            }else{
                static::$staticQuerier->unlock($__key)->rollBack();
                return false;
            }
		}
        static::$staticQuerier->unlock($__key)->commit();
		return true;
	}

	protected $meta, $source;

	protected function __construct(){
        static::init();
    }

	protected function __put(array $input, bool $isSaved = false){
		$this->modelProperties = $input;
        if($isSaved){
			$this->__guid = $this->modelProperties['ID'];
            $this->savedProperties = $this->modelProperties;
        }
        $this->xml = NULL;
		return $this;
	}

	public function put(array $input){
		return $this;
	}

	public function getMetaInfo(){
		return $this->meta;
	}

	public function getSourceInfo(){
		return $this->source;
	}

	public function save(){
		return false;
	}

	public function recycle($recycleType = self::RECYCLE){
		if($this->meta){
			$this->meta->recycle($recycleType);
			return $this;
		}
		return false;
	}

	/**
	 * 删除文件资源
	 */
	public function destroy() : bool {
		if($this->source){
			$this->source->destroy();
			if($this->source->error_msg!=='SQL_ERROR'){
				static::deleteFileMeta($this->savedProperties['ID']);
				return true;
			}
		}
		return false;
	}
}