<?php
namespace PM\_CLOUD;

/**
 * Model Of Attached Resource Infomation
 * 资源资源信息模型
**/
final class AttachmentModel extends FileModel {
	use traits\transfer;

	protected static
	$extendedProperties = [],
	$fileStoragePath = false,
	$staticQuerier,
	$staticMemorizeStorage = [],
	$staticFileStorage,
	$tablenameAlias = 'filemeta_protected';

	public static function byGUID($id){
		if($meta = AttachmentMetaModel::byGUID($id)){
			if($source = AttachmentSourceModel::byGUID($meta->SRC_ID)){
				$obj = new static;
				$obj->meta = $meta;
				$obj->source = $source;
				return  $obj->__put(array_merge($meta->getArrayCopy(), $source->getArrayCopy()), true);
			}
			self::deleteFileMeta($id);
		}
	}

	public static function post(array $input){
		list($basename, $extn, $type) = AttachmentMetaModel::getSplitFileNameArray($input['FILE_NAME'], $input['MIME']);
		$srcInput = AttachmentSourceModel::completeInput([
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
		if($obj = AttachmentModel::postByMateinfoAndSource($metaInput, $srcInput)){
			return $obj;
		}
		return false;
	}

	public static function postByMateinfoAndSource(array $metaInput, array $sourceInput){
		#使用事务
		#开启事务
		self::initQuerier();
		$__key = self::$staticQuerier->beginAndLock();
		if($source = AttachmentSourceModel::postIfNotExists($sourceInput)){
			$metaInput['SRC_ID'] = $source->SID;
			if(isset($metaInput['ID'])){
				$id = $metaInput['ID'];
				$meta = new AttachmentMetaModel($metaInput['ID']);
			}else{
				$metaInput['ID'] = substr(substr($source->HASH, 8, 16).intval(BOOTTIME).uniqid(), 0, 44);
				$meta = AttachmentMetaModel::create($metaInput);
			}
			if($meta->put($metaInput)->save()){
				#提交事务
				self::$staticQuerier->unlock($__key)->commit();
				$obj = new static;
				$obj->meta = $meta;
				$obj->source = $source;
				return $obj->__put(array_merge($meta->getArrayCopy(), $source->getArrayCopy()), true);
			}
		}
		#回滚事务
		self::$staticQuerier->unlock($__key)->rollBack();
		\unlink(PUBL_PATH.$sourceInput['LOCATION']);
		return false;
	}

	public static function updateByID($id, array $input){
		#使用事务
		self::initQuerier();
		$__key = self::$staticQuerier->beginAndLock();
		if($source = AttachmentSourceModel::postIfNotExists($input)){
			$input['SRC_ID'] = $source->SID;
			$meta = new AttachmentMetaModel($id);
			//var_dump($id, $meta);
			if($meta->put($input)->save()){
				//var_dump($meta);
				self::$staticQuerier->unlock($__key)->commit();
				$obj = new static;
				$obj->meta = $meta;
				$obj->source = $source;
				return $obj->__put(array_merge($meta->getArrayCopy(), $source->getArrayCopy()), true);
			}
		}
		self::$staticQuerier->unlock($__key)->rollBack();
		return false;
	}

	/**
	 * 按指定条件批量移除或隐藏文件资源
	 */
	public static function remove($require, $recycleType = self::RECYCLE){
		if($metaobjs = AttachmentMetaModel::remove($require, $recycleType)){
			$objs = [];
			foreach($metaobjs as $meta){
				$objs[] = $meta->getExtendedModel();
			}
			return $objs;
		}
		return ;
	}
}