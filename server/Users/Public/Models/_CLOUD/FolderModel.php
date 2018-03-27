<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class FolderModel extends \AF\Models\BaseDeepModel {
	const
	ID_DESC = [['id', true, self::SORT_REGULAR]],
	ID_ASC = [['id', false, self::SORT_REGULAR]],
	MTIME_DESC = [['SK_MTIME', true, self::SORT_REGULAR]],
	MTIME_ASC = [['SK_MTIME', false, self::SORT_REGULAR]],
	NAME_DESC = [['name', true, self::SORT_REGULAR]],
	NAME_ASC = [['name', false, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, self::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, self::SORT_CONVERT_GBK]],

	UNRECYCLE = 0,
	RECYCLE = 1,
	HIDE = 2;

	protected static
	$fileStoragePath = DPATH.'CLOUDS/'.'folders/',
	$fileStoreLifetime = 0,
	$tablenameAlias = 'folders',
	$tablenamePrefix = DB_YUN,
	$defaultPorpertyValues  = [
		'id'				=>	0,
		'type'				=>	'file',
		'tablename'			=>	NULL,
		'name'				=>	'New Folder',
		'description'		=>	'',
        'parent'		    =>	6,
		'SK_IS_RECYCLED'	=>	0,
		'SK_MTIME'			=>	DATETIME
	];

	/**
	 * 检查文件夹是否存在
	 */
	private static function checkFolderExists($folder_id){
		// 获取默认数据行查询器
        $querier = static::initQuerier();
		$result = $querier->requires([
			'id'	=> $folder_id
		])->select('id');
		if($result&&($row = $result->item())){
			return true;
		}
		return false;
	}

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
			// var_dump('4', $parent->type, $folder->type);
			// 存在父级目录
			if($parent->type===$folder->type){
				// var_dump('5');
				// 且两个目录同类型
				if($parent->tablename==$folder->tablename){
					// var_dump('6');
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
	 * 检查并校正文件夹名称
	 */
	private static function correctSourcesFolderName($parent_id, $folder_id, $name = NULL){
		if(empty($name)){
			// 如果未指定文件夹名，则命名为New Folder
			$name = 'New Folder';
		}

		// 获取默认数据行查询器
		$querier = static::initQuerier();

		$result = $querier->requires()
		->where('parent', $parent_id)
		->where('id', $folder_id, '<>')
		->where('name', $name)
		->where('SK_IS_RECYCLED', 0)
		->select('name');

		if($row = $result->item()){
			// 查询所有同级文件夹名称
			$names = [];
			$result = $querier->requires()
			->where('parent', $parent_id)
			->where('id', $folder_id, '<>')
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
	 * 获取表格根目录
	 */
	public static function getRoots(array $options = [], array $orderby = self::ID_ASC){
		if(isset($options['tablename'])&&is_string($options['tablename'])){
			$tablenameAlias = $options['tablename'];
			if($tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
				return self::query("`tablename` = '$tablename' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , $orderby);
			}
		}
		// 文件的根目录其实固定的几个，实际上不用判断移除和隐藏
		return self::query("`type` = 'file' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , $orderby);
	}

	/**
	 * 获取子目录
	 */
	public static function getChildren($id, array $options = [], array $orderby = self::ID_ASC){
		return self::query(['parent' => $id, 'SK_IS_RECYCLED' => 0], $orderby);
	}

	/**
	 * 获取文件根目录
	 */
	public static function getFilsRootFolders(array $orderby = self::ID_ASC){
		return self::getRoots(NULL, $orderby);
	}

	/**
	 * 获取表格目录
	 */
	public static function getFoldersByTableName($tablename, array $orderby = self::ID_ASC){
		if(is_string($tablename)){
			if($tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
				return self::query("`tablename` = '$tablename' AND `SK_IS_RECYCLED` = 0" , $orderby);
			}
		}else{
			return self::query("`type` = 'file' AND `SK_IS_RECYCLED` = 0" , $orderby);
		}
		return [];
	}

	/**
	 * 获取表格首选目录
	 */
    public static function getDefaultFolder($tablename){
		if(is_string($tablename)&&$tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
			$array = self::query("`tablename` = '$tablename' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , [['id', false, self::SORT_REGULAR]], 1);
			if($array&&isset($array[0])){
				return $array[0];
			}
		}
		return NULL;
	}
	
	/**
	 * 创建新的归档文件夹
	 */
	public static function create(array $option = [0, NULL, NULL]){
		list($parent_id, $name, $tablename) = $option;
		if(is_numeric($parent_id)){
			$obj = new self;
			if(is_string($tablename)&&$tablemeta = TableMetaModel::byGUID($tablename)){
				if($parent_id==0||(($parent = self::byGUID($parent_id))&&($parent->tablename === $tablename))){
					$obj->readonly = false;
					$obj->type = $tablemeta->type;
					$obj->tablename = $tablename;
					$obj->name = $name;
					$obj->parent = $parent_id;
					$obj->readonly = true;
					return $obj;
				}else{
					return false;
				}
			}elseif($parent = self::byGUID($parent_id)){
				// 存在指定父级文件夹
				$obj->readonly = false;
				$obj->type = $parent->type;
				$obj->tablename = NULL;
				$obj->name = $name;
				$obj->parent = $parent_id;
				$obj->readonly = true;
				return $obj;
			}else{
				return false;
			}
		}else{
			return false;
		}
		return false;
	}

	/**
	 * 创建新的归档文件夹
	 */
	public static function postIfNotExists($parent_id, $name, $tablename = NULL, array $more = [], $getArrayCopy = true){
		if(is_string($tablename)){
			$array = self::query("`tablename` = '$tablename' AND `parent` = $parent_id AND `name` = '$name'" , [['id', false, self::SORT_REGULAR]], 1);	
		}else{
			$array = self::query("`tablename` = '' AND `parent` = $parent_id AND `name` = '$name'" , [['id', false, self::SORT_REGULAR]], 1);
		}
		if($array&&isset($array[0])){
			if($getArrayCopy){
				return $array[0];
			}
			$obj = new static;
			return $obj->__put($array[0], true);
		}
		if($obj = self::create([$parent_id, $name, $tablename])){
			if($obj->__put($more)->save()){
				if($getArrayCopy){
					return $obj->getArrayCopy();
				}
				return $obj;
			}
		}
		return false;
	}

	/**
	 * 创建新的归档文件夹
	 */
	public static function createAndSave($parent_id, $name = NULL, $tablename = NULL){
		if($obj = self::postIfNotExists($parent_id, $name, $tablename, false)){
			return $obj;
		}
		return false;
	}

	/**
	 * 创建新的归档文件夹
	 */
	public static function post(array $input){
		if(isset($input['parent'])){
			$parent_id = $input['parent'];
			unset($input['parent']);
			if(isset($input['name'])){
				$name = $input['name'];
				unset($input['name']);
			}else{
				$name = 'New Folder';
			}
			if(isset($input['tablename'])){
				$tablenameAlias = $input['tablename'];
				unset($input['tablename']);
			}elseif($parent_id){
				$tablenameAlias = NULL;
			}else{
				return false;
			}
			if($obj = self::postIfNotExists($parent_id, $name, $tablename, $input, false)){
				return $obj;
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
	
	public function save(){
		// 移除主键
		unset($this->modelProperties['id']);

        if($this->__guid&&$this->savedProperties){
			return $this->__update();
		}
		return $this->__insert();
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

	protected function __update(){
		// 已经存在的文件夹
		
		// 检查可写性
        if($this->__guid<7){
            return 0;
		}
			
		// 检查父级目录可用性
		if(!self::checkParentCanBeSet($this, $this->modelProperties['parent'])){
			return NULL;
		}
		
		// 检查并校正文件夹名称
		$this->modelProperties['name'] = self::correctSourcesFolderName($this->modelProperties['parent'], $this->__guid, $this->modelProperties['name']);

		// 检查变更
		$diff = self::array_diff($this->savedProperties, $this->modelProperties, self::DIFF_SIMPLE);
		$update = $diff['__M__'];
		
		// 移除主键，主键不可能被改写
		// unset($update['id']);
            
        if(count($update)){
			if($this->querier->requires()->where('id', $this->__guid)->update($update)){
				$this->modelProperties['id'] = $this->__guid;
                $this->savedProperties = $this->modelProperties;
				$this->files->store($this->__guid);
            }else{
                return false;
            }
        }
        return $this;
	}

	public function getParentObject(){
		if($this->modelProperties['parent']!=0){
			return self::byGUID($this->modelProperties['parent']);
		}
		return NULL;
	}

	/**
	 * 获取可选父目录
	 */
	public function getUsableParents(array $options = []){
		$folders = [];
		$roots = self::getRoots(array_merge($options, ['tablename'=> $this->tablename, 'SK_IS_RECYCLED' => 0]));
		foreach($roots as $root){
			if($root->id!==$this->modelProperties['id']){
				$root->__level = 0;
				$folders[] = $root;
				$folders = $root->getOffspring($folders, 1, $this->modelProperties['id']);
			}
		}
		return $folders;
	}

	/**
	 * 获取表信息
	 */
	public function getTableMeta(){
		if($this->modelProperties['tablename']){
			return TableMetaModel::byGUID($this->modelProperties['tablename']);
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
	 * 移除文件夹
	 * 同时隐藏文件夹中的内容、文件夹的子文件夹、子文件夹中的内容
	 */
	public function recycle($recycleType = self::RECYCLE){
		$recycleType = in_array($recycleType, [0, 1, 2]) ? $recycleType : 1;
		if($recycleType){
			// 隐藏子文件夹
			self::remove(['parent' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			if($this->type==='file'){
				// 隐藏文件
				FileMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			}else{
				// 隐藏表格行
				TableRowMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 0], self::HIDE);
			}	
		}else{
			// 恢复
			self::remove(['parent' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			if($this->type==='file'){
				FileMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			}else{
				TableRowMetaModel::remove(['FOLDER' => $this->__guid, 'SK_IS_RECYCLED' => 2], self::UNRECYCLE);
			}
		}
		$this->modelProperties['SK_IS_RECYCLED'] = $recycleType;
		return $this->save();
	}

	/**
	 * 删除文件夹
	 * 同时会删除文件夹中的内容、文件夹的子文件夹、子文件夹中的内容
	 */
	public function destroy(){
		if($this->savedProperties){
			$querier = $this->querier;
			#使用事务
			$__key = $querier->beginAndLock();
			self::delete(['parent' => $this->__guid]);
			if($this->type==='file'){
				$result = FileModel::delete("`FOLDER` = '$this->__guid'");
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