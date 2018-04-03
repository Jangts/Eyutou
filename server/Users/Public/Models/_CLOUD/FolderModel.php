<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class FolderModel extends \AF\Models\BaseDeepModel {
	use traits\grouping;
	
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
	HIDE = 2,
	
	NEW_ITEM_NAME = 'New Folder';

	protected static
	$fileStoragePath = DPATH.'CLOUDS/'.'folders/',
	$fileStoreLifetime = 0,
	$tablenameAlias = 'folders',
	$tablenamePrefix = DB_YUN,
	$defaultPorpertyValues  = [
		'id'				=>	0,
		'type'				=>	'F',
		'name'				=>	self::NEW_ITEM_NAME,
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
			if($folder->type==='F'){
				return false;
			}
			return true;
		}
		if($parent = self::byGUID($parent_id)){
			// 存在父级目录
			// 且两个目录同类型
			if($parent->type==$folder->type){
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
		return false;
	}

	/**
	 * 获取表格根目录
	 */
	public static function getRoots(array $options = [], array $orderby = self::ID_ASC){
		if(isset($options['type'])&&in_array($options['type'], ['A', 'F'])){
			$type = $options['type'];
			return self::query("`type` = '$type' AND `parent` = 0 AND `SK_IS_RECYCLED` = 0" , $orderby);
		}
		return [];
	}

	/**
	 * 获取文件根目录
	 */
	public static function getFilsRootFolders(array $orderby = self::ID_ASC){
		return self::getRoots(['type'=>'F'], $orderby);
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
	public static function post(array $input){
		if(isset($input['name'])){
			$name = $input['name'];
			unset($input['name']);
		}else{
			$name = self::NEW_ITEM_NAME;
		}
		if(isset($input['type'])&&in_array($input['type'], ['A', 'F'])){
			$type = $input['type'];
			unset($input['type']);
			if($obj = self::postIfNotExists($name, $type, $input, false)){
				return $obj;
			}
		}
		return false;
	}

	// 设置模型为只读模型,从而关闭对象的set等可写方法
	protected $readonly = true;

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
		$folders = [];
		$roots = self::getRoots(array_merge($options, ['type'=> $this->type, 'SK_IS_RECYCLED' => 0]));
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