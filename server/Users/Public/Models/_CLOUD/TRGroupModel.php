<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class TRGroupModel extends \AF\Models\BaseDeepModel {
	use traits\grouping;

	const
	ID_DESC = [['id', true, self::SORT_REGULAR]],
	ID_ASC = [['id', false, self::SORT_REGULAR]],
	SORT_DESC = [['SK_SORT_NUM', true, self::SORT_REGULAR]],
	SORT_ASC = [['SK_SORT_NUM', false, self::SORT_REGULAR]],
	MTIME_DESC = [['SK_MTIME', true, self::SORT_REGULAR]],
	MTIME_ASC = [['SK_MTIME', false, self::SORT_REGULAR]],
	NAME_DESC = [['name', true, self::SORT_REGULAR]],
	NAME_ASC = [['name', false, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, self::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, self::SORT_CONVERT_GBK]],

	UNRECYCLE = 0,
	RECYCLE = 1,
	HIDE = 2,
	
	NEW_ITEM_NAME = 'New Group';

	protected static
	$fileStoragePath = DPATH.'CLOUDS/'.'trgroups/',
	$fileStoreLifetime = 0,
	$tablenameAlias = 'groups',
	$tablenamePrefix = DB_YUN,
	$defaultPorpertyValues  = [
		'id'				=>	0,
		'tablename'			=>	NULL,
		'name'				=>	self::NEW_ITEM_NAME,
		'description'		=>	'',
		'parent'		    =>	6,
		'SK_SORT_NUM'			=>	0,
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
	 * 检查父级目录可用性
	 */
	private static function checkParentCanBeSet($group, $parent_id){
		if($parent_id===$group->id){
			// 父级id不能是当前id
            return false;
		}
		if($parent_id==0){
			return true;
		}
		if($parent = self::byGUID($parent_id)){
			// 存在父级目录
			if($parent->tablename==$group->tablename){
				// 检查指定目录的祖先目录中是否存在此目录
				$ancestors = $parent->getAncestors();
				foreach($ancestors as $ancestor){
					if($ancestor->id===$group->id){
						return false;
					}
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取表格根目录
	 */
	public static function getRoots(array $options = [], array $orderby = self::SORT_ASC){
		if(isset($options['tablename'])&&is_string($options['tablename'])){
			$tablenameAlias = $options['tablename'];
			if($tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
				return self::query("`tablename` = '$tablename' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , $orderby);
			}
		}
		return [];
	}

	/**
	 * 获取表格目录
	 */
	public static function getGroupsByTableName($tablename, array $orderby = self::SORT_ASC){
		if(is_string($tablename)){
			if($tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
				return self::query("`tablename` = '$tablename' AND `SK_IS_RECYCLED` = 0" , $orderby);
			}
		}
		return [];
	}

	/**
	 * 获取表格首选目录
	 */
    public static function getDefaultGroup($tablename){
		if(is_string($tablename)&&$tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
			$array = self::query("`tablename` = '$tablename' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , [['SK_SORT_NUM', false, self::SORT_REGULAR], ['id', false, self::SORT_REGULAR]], 1);
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
	public static function post(array $input){
		if(isset($input['name'])){
			$name = $input['name'];
			unset($input['name']);
		}else{
			$name = self::NEW_ITEM_NAME;
		}
		if(isset($input['tablename'])){
			$tablename = $input['tablename'];
			unset($input['tablename']);
				if($obj = self::postIfNotExists($name, $tablename, $input, false)){
				return $obj;
			}
		}
		
		return false;
	}

	// 设置模型为只读模型,从而关闭对象的set等可写方法
	protected $readonly = true;

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
			if($this->querier->requires()->where('id', $this->__guid)->update($update)!==false){
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