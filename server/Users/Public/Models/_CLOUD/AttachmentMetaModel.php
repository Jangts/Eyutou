<?php
namespace PM\_CLOUD;

/**
 * System File Resource Lite Data
 * 系统文件资源轻数据
 * 是一个忽略五类文件资源数据差异的精简的单一标准模型
**/
final class AttachmentMetaModel extends FileMetaModel {
	protected static
	$fileStoragePath = false,
	$staticQuerier,
    $staticMemorizeStorage = [],
	$staticFileStorage,
	$tablenameAlias = 'filemeta_protected',
	$defaultPorpertyValues = [
		'ID'         		=>  '',
		'SRC_ID'        	=>  0,
		'FOLDER'        	=>  0,
		'FILE_NAME'     	=>  '',
		'FILE_TYPE'     	=>  'archive',
		'FILE_SIZE'     	=>  0,
		'FILE_EXTN'        	=>  '',
		'SK_MTIME'   		=>  DATETIME,
        'SK_IS_RECYCLED' 	=>  0
	];
	
	/**
	 * 按条件批量删除文件
	 */
	public static function delete($require){
		return AttachmentModel::delete($require);
	}

	public function correctFolder(){
		if($folder = FolderModel::byGUID($this->modelProperties['FOLDER'])){
			if($folder->type==='A'){
				if($folder->SK_IS_RECYCLED){
					// 检查是否应该隐藏
					$this->modelProperties['SK_IS_RECYCLED'] = self::HIDE;
				}
			}else{
				$this->modelProperties['FOLDER'] = 0;
			}
		}else{
			$this->modelProperties['FOLDER'] = 0;
		}
		return $this->modelProperties['FOLDER'];
	}

	/**
	 * 拓展为完整的文件模型
	 */
	public function getExtendedModel(){
		return AttachmentModel::byGUID($this->modelProperties['ID']);
	}
	
	/**
	 * 删除文件资源
	 */
	public function destroy(){
		return AttachmentModel::byGUID($this->modelProperties['ID'])->destroy();
	}
}