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
	$typePrefix = DB_YUN,
	$defaultPorpertyValues  = [
		'id'				=>	0,
		'type'				=>	'F',
		'name'				=>	'New Folder',
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
			if($folder->type){
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
				if($parent->type==$folder->type){
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
		if(isset($options['type'])&&is_string($options['type'])){
			$tablenameAlias = $options['type'];
			if($type&&($tablemeta = TableMetaModel::byGUID($type))){
				return self::query("`type` = '$type' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , $orderby);
			}
		}
		// 文件的根目录其实固定的几个，实际上不用判断移除和隐藏
		return self::query("`type` = 'F' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , $orderby);
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
	 * 创建新的归档文件夹
	 */
	public static function create(array $option = [0, NULL, NULL]){
		list($name, $type, $parent_id) = $option;
		if(is_numeric($parent_id)){
			$obj = new self;
			if($type==='F'){
				if($parent_id===0){
					return false;
				}
			}else{
				$type==='A';
			}
			if($parent_id===0||(($parent = self::byGUID($parent_id))&&($parent->type === $type))){
				$obj->readonly = false;
				$obj->type = $parent->type;
				$obj->type = $type;
				$obj->name = $name;
				$obj->parent = $parent_id;
				$obj->readonly = true;
				return $obj;
			}
		}
		return false;
	}

	/**
	 * 创建新的归档文件夹
	 */
	public static function postIfNotExists($name, $type = 'F', array $more = [], $getArrayCopy = true){
		if(isset($more['parent'])){
			$parent_id = $more['parent'];
		}else{
			$parent_id = ($type==='F' ? 6 : 0);
		}
		unset($more['parent']);
		$array = self::query("`type` = '$type' AND `parent` = $parent_id AND `name` = '$name'" , [['id', false, self::SORT_REGULAR]], 1);
		if($array&&isset($array[0])){
			if($getArrayCopy){
				return $array[0];
			}
			$obj = new static;
			return $obj->__put($array[0], true);
		}
		if($obj = self::create([$name, $type, $parent_id])){
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
	public static function createAndSave($type, $parent_id, $name = 'New Folder'){
		if($obj = self::postIfNotExists($name, $type, [
			'parent' => $parent_id
		], false)){
			return $obj;
		}
		return false;
	}

	/**
	 * 创建新的归档文件夹
	 */
	public static function post(array $input){
		if(isset($input['name'])){
			$name = $input['name'];
			unset($input['name']);
		}else{
			$name = 'New Folder';
		}
		if(isset($input['type'])){
			$type = $input['type'];
			unset($input['type']);
		}else{
			$type = 'F';
		}
		if($obj = self::postIfNotExists($name, $type, $input, false)){
			return $obj;
		}
		return false;
	}

	protected function __update(){
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

	/**
	 * 获取可选父目录
	 */
	public function getUsableParents(array $options = []){
		$folders = [];
		$roots = self::getRoots(array_merge($options, ['SK_IS_RECYCLED' => 0]));
		foreach($roots as $root){
			if($root->id!==$this->modelProperties['id']){
				$root->__level = 0;
				$folders[] = $root;
				$folders = $root->getOffspring($folders, 1, $this->modelProperties['id']);
			}
		}
		return $folders;
	}
}