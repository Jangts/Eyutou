<?php
namespace AF\Models\traits;

use Storage;

trait caching {
    /**
	 * 获取实例备份
	 * 
	 * @access protected
     * @final
	 * @static
     * @param string|int|null $guid 如需提取单个实例，请传入索引值
	 * @return mixed
	**/
    final protected static function getMemoStorage($guid = NULL){
        $class = strtolower(get_called_class());
        if(empty(self::$privateMemorizeStorages[$class])){
            self::$privateMemorizeStorages[$class] = [];
        }
        if($guid===NULL){
            return self::$privateMemorizeStorages[$class];
        }
        return isset(self::$privateMemorizeStorages[$class][$guid]) ? self::$privateMemorizeStorages[$class][$guid] : NULL;
    }

    /**
	 * 获取默认储存仓
     * 不调用则不会生成
	 * 
	 * @access protected
     * @final
	 * @static
	 * @return object|null
	**/
    final protected static function getFileStorage(){
        $class = strtolower(get_called_class());
        if(empty(self::$privateFileStorages[$class])){
            if(isset(static::$fileStoragePath)){
                if(static::$fileStoragePath===true){
                    if(stripos($class, 'pm\\')===0){
                        self::$privateFileStorages[$class] = new Storage(RUNPATH_M.'Public/'.$class.'/', static::$fileStorageEncodeMode, true);
                    }elseif(is_subclass_of($class, 'AF\Models\BaseR3Model')||is_subclass_of($class, 'AF\Models\BaseMapModel')){
                        self::$privateFileStorages[$class] = new Storage(RUNPATH_M.'Protected/'.static::$tablenamePrefix.'/'.static::$tablenameAlias.'/', static::$fileStorageEncodeMode, true);
                    }else{
                        self::$privateFileStorages[$class] = new Storage(RUNPATH_M.'Pravite/_'.CACAI.'/'.$class.'/', static::$fileStorageEncodeMode, true);
                    }
                }elseif(static::$fileStoragePath===1){
                    if(is_subclass_of($class, 'AF\Models\BaseR3Model')||is_subclass_of($class, 'AF\Models\BaseMapModel')){
                        self::$privateFileStorages[$class] = new Storage(RUNPATH_M.'Protected/'.static::$tablenamePrefix.'/'.static::$tablenameAlias.'/'.$class.'/', static::$fileStorageEncodeMode, true);
                    }else{
                        self::$privateFileStorages[$class] = new Storage(RUNPATH_M.'Pravite/_'.CACAI.'/'.$class.'/', static::$fileStorageEncodeMode, true);
                    }
                }elseif(static::$fileStoragePath&&is_string(static::$fileStoragePath)){
                    self::$privateFileStorages[$class] = new Storage(static::$fileStoragePath, static::$fileStorageEncodeMode, true);
                }else{
                    return self::$privateFileStorages[$class] = NULL;
                }    
            }else{
                return self::$privateFileStorages[$class] = NULL;
            }
        }
        return self::$privateFileStorages[$class];
    }

    /**
	 * 设置超时时间
	 * 
	 * @access public
     * @final
	 * @static
     * @param int $time 超时时间数值
     * @param int $unit 超时时间单位，其实是倍数
	 * @return bool
	**/
    final public static function setLifetime($time = 2, $unit = self::H) : bool {
		if(is_numeric($time)&&is_numeric($unit)){
            $class = strtolower(get_called_class());
            self::$privateFilestoreLifetimes[$class] = $time * $unit;
            return true;
        }
        return false;
    }
    
    /**
	 * 查看超时时间
	 * 
	 * @access public
     * @final
	 * @static
	 * @return int
	**/
    final public static function getLifetime(){
        $class = strtolower(get_called_class());
        if(empty(self::$privateFilestoreLifetimes[$class])){
            self::$privateFilestoreLifetimes[$class] = static::$fileStoreLifetime;
        }
        return self::$privateFilestoreLifetimes[$class];
    }

    /**
	 * 清空存储仓
	 * 
	 * @access protected
     * @final
	 * @static
	 * @return mixed
	**/
    final public static function cleanFileStorage(){
        $staticFileStorage = self::getFileStorage();
        if(is_a($staticFileStorage, '\Tangram\CACHE\DataStorage')){
            $staticFileStorage-> clean();
        }
    }
}