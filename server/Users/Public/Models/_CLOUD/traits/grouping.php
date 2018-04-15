<?php
namespace PM\_CLOUD\traits;

use Status;
use Lib\data\Transfer as Downloader;
use Lib\graphics\ImagePrinter;
use PM\_CLOUD\FileMetaModel;

trait grouping {
	/**
	 * 检查并校正文件夹名称
	 */
	private static function correctSourcesFolderName($parent_id, $folder_id/*$group_id*/, $name = NULL){
		if(empty($name)){
			// 如果未指定文件夹名，则命名为New Folder
			$name = self::NEW_ITEM_NAME;
		}

		// 获取默认数据行查询器
		$querier = static::initQuerier();

		$result = $querier->requires()
		->where('parent', $parent_id)
		->where('id', $folder_id/*$group_id*/, '<>')
		->where('name', $name)
		->where('SK_IS_RECYCLED', 0)
		->select('name');

		if($row = $result->item()){
			// 查询所有同级文件夹名称
			$names = [];
			$result = $querier->requires()
			->where('parent', $parent_id)
			->where('id', $folder_id/*$group_id*/, '<>')
			->where('SK_IS_RECYCLED', 0)
			->select('name');

			foreach($result as $row){
				$names[] = $row['name'];
			}
			
			$newname = $name.'(2)';
			$i = 2;
			while(in_array($newname, $names, true)){
				$newname = $name.'('.++$i.')';
			}
			return $newname;
		}
		return $name;
	}
	
	/**
	 * 获取子目录
	 */
	public static function getChildren($id, array $options = [], array $orderby = self::ID_ASC) : array {
		return self::query(['parent' => $id, 'SK_IS_RECYCLED' => 0], $orderby);
	}

	/**
	 * 创建新的归档文件夹
	 */
	public static function createAndSave($type/*$tablename*/, $parent_id, $name = self::NEW_ITEM_NAME){
		if($obj = self::postIfNotExists($name, $type/*$tablename*/, [
			'parent' => $parent_id
		], false)){
			return $obj;
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
	public static function removeById($folder_id/*$group_id*/, $recycleType = self::RECYCLE){
		$obj = self::byGUID($folder_id/*$group_id*/);
		if($obj->recycle($recycleType)){
			return $obj;
		}
		return false;
    }
    
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
				// 隐藏受保护的文件
				AttachmentMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			}elseif($this->type==='F'){
				// 隐藏文件
				FileMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			}else{
				// 隐藏表格行
                TableRowMetaModel::remove(['GROUPID' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
            }
		}else{
			// 恢复
			self::remove(['parent' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			if($this->type==='A'){
                AttachmentMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			}elseif($this->type==='F'){
				FileMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			}else{
                TableRowMetaModel::remove(['GROUPID' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
            }
		}
		$this->modelProperties['SK_IS_RECYCLED'] = $recycleType;
		return $this->save();
	}


	/**
	 * 删除分组或文件夹
	 * 同时会删除分组或文件夹中的内容、分组或文件夹的子分组或文件夹、子分组或文件夹中的内容
	 */
	public function destroy() : bool {
		if($this->savedProperties){
			$querier = $this->querier;
			#使用事务
			$__key = $querier->beginAndLock();
			self::delete(['parent' => $this->__guid]);
			if($this->type==='A'){
				$result = FileAttachmentModel::delete("`FOLDER` = '$this->__guid'");
	        }elseif($this->type==='F'){
				$result = FileModel::delete("`FOLDER` = '$this->__guid'");
			}else{
				$result = TableRowModel::delete("`GROUPID` = '$this->__guid'");
			}
			if($result){
				if($querier->requires()->where('id', $this->__guid)->delete()!==false){
					$querier->unlock($__key)->commit();
					if($this->files) {
						$this->files->store($this->__guid);
					}
        			return true;
    			}
			}
			$querier->unlock($__key)->rollBack();
		}
		return false;
	}
}