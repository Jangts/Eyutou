<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class TRGroupModel extends \AF\Models\BaseDeepModel {
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
	$fileStoragePath = DPATH.'CLOUDS/'.'trgroups/',
	$fileStoreLifetime = 0,
	$tablenameAlias = 'groups',
	$tablenamePrefix = DB_YUN,
	$defaultPorpertyValues  = [
		'id'				=>	0,
		'tablename'			=>	NULL,
		'name'				=>	'New Group',
		'description'		=>	'',
        'parent'		    =>	6,
		'SK_IS_RECYCLED'	=>	0,
		'SK_MTIME'			=>	DATETIME
	];

	/**
	 * 检查文件夹是否存在
	 */
	private static function checkGroupExists($group_id){
		// 获取默认数据行查询器
        $querier = static::initQuerier();
		$result = $querier->requires([
			'id'	=> $group_id
		])->select('id');
		if($result&&($row = $result->item())){
			return true;
		}
		return false;
	}

	

	/**
	 * 检查并校正文件夹名称
	 */
	private static function correctSourcesGroupName($parent_id, $group_id, $name = NULL){
		if(empty($name)){
			// 如果未指定文件夹名，则命名为New Group
			$name = 'New Group';
		}

		// 获取默认数据行查询器
		$querier = static::initQuerier();

		$result = $querier->requires()
		->where('parent', $parent_id)
		->where('id', $group_id, '<>')
		->where('name', $name)
		->where('SK_IS_RECYCLED', 0)
		->select('name');

		if($row = $result->item()){
			// 查询所有同级文件夹名称
			$names = [];
			$result = $querier->requires()
			->where('parent', $parent_id)
			->where('id', $group_id, '<>')
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
	public static function getFilsRootGroups(array $orderby = self::ID_ASC){
		return self::getRoots(NULL, $orderby);
	}

	/**
	 * 获取表格目录
	 */
	public static function getGroupsByTableName($tablename, array $orderby = self::ID_ASC){
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
    public static function getDefaultGroup($tablename){
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
		list($name, $tablename, $parent_id) = $option;
		if(!is_numeric($parent_id)){
			$parent_id = 0;
		}
		$obj = new self;
		if(is_string($tablename)&&$tablemeta = TableMetaModel::byGUID($tablename)){
			if($parent_id===0||(($parent = self::byGUID($parent_id))&&($parent->tablename === $tablename))){
				$obj->readonly = false;
				$obj->tablename = $tablename;
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
	public static function postIfNotExists($name, $tablename = NULL, array $more = [], $getArrayCopy = true){
		if(isset($more['parent'])){
			$parent_id = $more['parent'];
		}else{
			$parent_id = 0;
		}
		unset($more['parent']);
		$array = self::query("`tablename` = '$tablename' AND `parent` = $parent_id AND `name` = '$name'" , [['id', false, self::SORT_REGULAR]], 1);	
		if($array&&isset($array[0])){
			if($getArrayCopy){
				return $array[0];
			}
			$obj = new static;
			return $obj->__put($array[0], true);
		}
		if($obj = self::create([$name, $tablename, $parent_id])){
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
	public static function createAndSave($tablename, $parent_id, $name = 'New Group'){
		if($obj = self::postIfNotExists($name, $tablename, [
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
			$name = 'New Group';
		}
		if(isset($input['tablename'])){
			$tablename = $input['tablename'];
			unset($input['tablename']);
		}else{
			return false;
		}
		if($obj = self::postIfNotExists($name, $tablename, $input, false)){
			return $obj;
		}
		return false;
	}

	protected function __update(){
		// 检查父级目录可用性
		if(!self::checkParentCanBeSet($this, $this->modelProperties['parent'])){
			return NULL;
		}
		
		// 检查并校正文件夹名称
		$this->modelProperties['name'] = self::correctSourcesGroupName($this->modelProperties['parent'], $this->__guid, $this->modelProperties['name']);

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
		$groups = [];
		$roots = self::getRoots(array_merge($options, ['tablename'=> $this->tablename, 'SK_IS_RECYCLED' => 0]));
		foreach($roots as $root){
			if($root->id!==$this->modelProperties['id']){
				$root->__level = 0;
				$groups[] = $root;
				$groups = $root->getOffspring($groups, 1, $this->modelProperties['id']);
			}
		}
		return $groups;
	}

	/**
	 * 获取表信息
	 */
	public function getTableMeta(){
		return TableMetaModel::byGUID($this->modelProperties['tablename']);
	}
}