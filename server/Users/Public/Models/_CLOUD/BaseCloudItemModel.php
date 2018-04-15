<?php
namespace PM\_CLOUD;

use Storage;
use Tangram\CTRLR\RDBQuerierPlus;
use Tangram\MODEL\ObjectModel;

abstract class BaseCloudItemModel extends ObjectModel {
	use \AF\Models\traits\querying;
	
	const
	UNRECYCLE = 0,
	RECYCLE = 1,
	HIDE = 2;

	protected static $tablenameAlias = '';

	protected static function init(){
		if(!static::$staticQuerier){
			if(static::$fileStoragePath){
				static::$staticFileStorage = new Storage(static::$fileStoragePath, Storage::JSN, true);
			}
			self::initQuerier();
		}
	}

    protected static function initQuerier() : \DBQ {
        if(!static::$staticQuerier){
			static::$staticQuerier = new RDBQuerierPlus;
		}
        return static::$staticQuerier->using(DB_YUN.static::$tablenameAlias);
	}  

	public static function create(array $option){
		return false;
	}

	public static function post(array $input){
		return false;
	}

	final public static function updata($require, $input){
		return false;
	}

	public static function remove($require, $recycleType = 1){
		return false;
	}

	public static function delete($require){
		return false;
	}

	protected $__guid, $savedProperties, $readonly = false;

	public function save(){
		if($this->savedProperties){
            return $this->__update();
        }
        return $this->__insert();
	}

	protected function __insert(){
		return false;
	}

	protected function __update(){
		return false;
	}

	public function recycle($recycleType = 1){
		return false;
	}

	public function destroy() : bool {
		return $this->__afterDelete();
	}

	protected function __afterDelete() : bool{
        return $this->clearRelativeCache();
	}
	
	public function clearRelativeCache(){
        return true;
    }
}
