<?php
namespace AF\Models;

use PDO;
use Status;
use Tangram\MODEL\ObjectModel;
use Storage;
use DBQ;
use Tangram\CTRLR\RDBQuerierPlus;

/**
 * @class AF\Models\BaseDeepModel;
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseDeepModel extends BaseR3Model {
	protected static $__parentFieldName = 'parent';

	public static function getRoots(array $options = [], array $orderby = [['1', false, self::SORT_REGULAR]]) : array {
		return static::query(array_merge($options, [static::$__parentFieldName => 0]), $orderby);
	}
    
	public static function getChildren($guid, array $options = [], array $orderby = [['1', false, self::SORT_REGULAR]]) : array {
		return static::query(array_merge($options, [static::$__parentFieldName => $guid]), $orderby);
	}
	
	public $__level = 0;
    
	public function getAncestors(bool $containSelf = false) : array {
		if($containSelf){
			$path = [$this];
		}else{
			$path = [];
		}
		$parent_id = $this[static::$__parentFieldName];
		while($parent_id){
			if($parent = static::byGUID($parent_id)){
				$path[] = $parent;
				$parent_id = $parent[static::$__parentFieldName];
			}else{
				new Status(1460, 'Using Module Error', 'Parent ['.$parent_id.'] Not Found.', true);
			}
		}
		return array_reverse($path);
	}

	public function getOffspring($container = [], $level = 0, $ignore = 0, array $options = []) : array {
		$children = static::getChildren($this->__guid, $options);
		foreach($children as $child){
			if($child[$this->pk]!==$ignore){
				$child->__level = $level;
				$container[] = $child;
				$container = $child->getOffspring($container, $level + 1, $ignore, $options);
			}
		}
		return $container;
	}

	public function getParentObject(){
		if($this->modelProperties[static::$__parentFieldName]!=0){
			return self::byGUID($this->modelProperties[static::$__parentFieldName]);
		}
		return NULL;
	}

	public function getUsableParents(array $options = []) : array {
		$folders = [];
		$roots = static::getRoots($options);
		foreach($roots as $root){
			if($root[$this->pk]!==$this->__guid){
				$folders[] = $root;
				$folders = $root->getOffspring($folders, 0, $this->__guid, $options);
			}
		}
		return $folders;
	}

	protected function __checkInsertData(array $post){
        $post = self::__checkValues(self::correctArrayByTemplate($post, static::$defaultPorpertyValues), true);
        if(empty($post[static::$__parentFieldName])||static::byGUID($post[static::$__parentFieldName])){
            return $post;
		}
		return NULL;
    }

    protected function __checkUpdateData(array $update, array $savedProperties){
        $update = self::__checkValues(array_intersect_key($update, $savedProperties));
        if(empty($update[static::$__parentFieldName])||static::byGUID($update[static::$__parentFieldName])){
            return $update;
		}
		return NULL;
    }
}