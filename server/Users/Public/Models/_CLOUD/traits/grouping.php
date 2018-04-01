<?php
namespace PM\_CLOUD\traits;

use Status;
use Lib\data\Transfer as Downloader;
use Lib\graphics\ImagePrinter;
use PM\_CLOUD\FileMetaModel;

trait grouping {
    /**
	 * 检查父级目录可用性
	 */
	private static function checkParentCanBeSet($folder, $parent_id){
		if($parent_id===$folder->id){
			// 父级id不能是当前id
            return false;
		}
		if($parent_id==0){
			if($folder->tablename){
				return true;
			}
			return false;
		}
		if($parent = self::byGUID($parent_id)){
			// 存在父级目录
			if($parent->type===$folder->type){
				// var_dump('5');
				// 且两个目录同类型
				if($parent->tablename==$folder->tablename){
					// 且两个目录同表(文件类型没有表，但都为NULL)

					// 检查指定目录的祖先目录中是否存在此目录
					$ancestors = $parent->getAncestors();
					foreach($ancestors as $ancestor){
						if($ancestor->id===$folder->id){
							return false;
						}
					}
					return true;
				}
			}
		}
		return false;
    }

    /**
	 * 按条件批量移除
	 */
	public static function remove($require, $recycleType = self::RECYCLE){
		$objs = self::query($require);
		foreach($objs as $obj){
			$obj->recycle($recycleType);
		}
		return $objs;
	}

	/**
	 * 按ID移除
	 */
	public static function removeById($folder_id, $recycleType = self::RECYCLE){
		$obj = self::byGUID($folder_id);
		if($obj->recycle($recycleType)){
			return $obj;
		}
		return false;
    }
    
    // 设置模型为只读模型,从而关闭对象的set等可写方法
	protected $readonly = true;

    protected function __insert(){
		// 检查父级目录可用性
		if(!self::checkParentCanBeSet($this, $this->modelProperties['parent'])){
			return NULL;
		}
		// 检查并校正文件夹名称
		$this->modelProperties['name'] = self::correctSourcesFolderName($this->modelProperties['parent'], 0, $this->modelProperties['name']);
		if($this->querier->insert($this->modelProperties)){
			$this->__guid = $this->querier->lastInsertId('id');
			$this->savedProperties = $this->modelProperties = $this->querier->requires()->where('id', $this->__guid)->select()->item();
			$this->files->store($this->__guid);
			return $this;
		}
		return false;
	}

    public function getParentObject(){
		if($this->modelProperties['parent']!=0){
			return self::byGUID($this->modelProperties['parent']);
		}
		return NULL;
	}

    /**  
	 * 禁用更新属性数组
	**/ 
    public function put(array $input){
		$input = array_intersect_key($input, [
			'name'				=>	'',
			'description'		=>	'',
        	'parent'		    =>	0,
		]);
		$this->__put($input);
        return $this;
	}
	
	/**
	 * 修改文件夹名称
	 */
	public function modifyFolderName($name){
		$this->modelProperties['name'] = $name;
		if($obj->save()){
			return true;
		}
		return false;
	}
    
    /**
	 * 移除分组或文件夹
	 * 同时隐藏分组或文件夹中的内容、分组或文件夹的子分组或文件夹、子分组或文件夹中的内容
	 */
	public function recycle($recycleType = self::RECYCLE){
		$recycleType = in_array($recycleType, [0, 1, 2]) ? $recycleType : 1;
		if($recycleType){
			// 隐藏子分组或文件夹
			self::remove(['parent' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			if($this->type==='A'){
				// 隐藏文件
				AttachmentMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			}elseif($this->type==='F'){
				// 隐藏表格行
				FileMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			}else{
                TableRowMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
            }
		}else{
			// 恢复
			self::remove(['parent' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			if($this->type==='F'){
                AttachmentMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			}elseif($this->type==='F'){
				FileMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			}else{
                TableRowMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
            }
		}
		$this->modelProperties['SK_IS_RECYCLED'] = $recycleType;
		return $this->save();
	}


	/**
	 * 删除分组或文件夹
	 * 同时会删除分组或文件夹中的内容、分组或文件夹的子分组或文件夹、子分组或文件夹中的内容
	 */
	public function destroy(){
		if($this->savedProperties){
			$querier = $this->querier;
			#使用事务
			$__key = $querier->beginAndLock();
			self::delete(['parent' => $this->__guid]);
			if($this->type==='A'){
                $result = FileModel::delete("`FOLDER` = '$this->__guid'");
            if($this->type==='F'){
				$result = FileAttachmentModel::delete("`FOLDER` = '$this->__guid'");
			}else{
				$result = TableRowModel::delete("`FOLDER` = '$this->__guid'");
			}
			if($result){
				if($querier->requires()->where('id', $this->__guid)->delete()!==false){
					$querier->unlock($__key)->commit();
					if($this->files) $this->files->store($this->__guid);
            		return true;
        		}
			}
			$querier->unlock($__key)->rollBack();
		}
        return false;
	}
}