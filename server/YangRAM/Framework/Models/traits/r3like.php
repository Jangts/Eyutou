<?php
namespace AF\Models\traits;

use PDO;
use DBQ;
use Tangram\CTRLR\RDBQuerierPlus;

trait r3like {
    /**
	 * 查询全部记录并返回模型实例
	 * 
	 * @access public
	 * @static
     * @param mixed $indexField 索引字段
	 * @return array
	**/
    public static function getALL($indexField = NULL){
        if($indexField&&!in_array($indexField, static::$uniqueIndexes, true)){
            $indexField = static::$uniqueIndexes[0];
        }
        $objs = [];
        $result = static::initQuerier()->requires()->take(0)->orderby(false)->select();
        if($result){
            $pdos = $result->getIterator();
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static;
                $obj->__put($modelProperties, true);
                if($indexField){
                    $objs[$modelProperties[$indexField]] = $obj;
                }else{
                    $objs[] = $obj;
                }
            }
        }
        return $objs;
    }

    /**
	 * 查询全部记录并按指定字段值分组模型实例
	 * 
	 * @access public
	 * @static
     * @param mixed $indexField 索引字段
	 * @return array
	**/
    public static function readInGroups($groupField){
        $groups = [];
        if(isset(static::$defaultPorpertyValues[$groupField])){
            $result = static::initQuerier()->requires()->take(0)->orderby(false)->select();
            if($result){
                $pdos = $result->getIterator();
                while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                    $obj = new static;
                    $obj->__put($modelProperties, true);
                    $index = $modelProperties[$groupField];
                    if(!isset($groups[$index])){
                        $groups[$index] = [];
                    }
                    $groups[$index][] = $obj;
                }
            }
        }
        return $groups;
    }

    /**
	 * 通过主键查询记录
	 * 
	 * @access public
	 * @static
     * @param string $guid 主机值
	 * @return object|bool
	**/
    public static function byGUID($guid){
        // 检查是否存在主键，默认第一索引键必须是主键
        if(count(static::$uniqueIndexes)>0){
            // 获取类的默认信息
            $pk = static::$uniqueIndexes[0];
            $querier = static::initQuerier();
            $staticFileStorage = self::getFileStorage();
            $fileStoreLifetime = self::getLifetime();

            // 检查是否支持缓存
            if(is_a($staticFileStorage, 'Tangram\CACHE\DataStorage')){
                // 检查是否存在缓存
                if($modelProperties = $staticFileStorage->take($guid)){
                    //var_dump($files, $modelProperties);
                    // 检查缓存是否过期，Memcache驱动的存储仓将会出错
                    if($fileStoreLifetime){
                        $time = $staticFileStorage->time($guid);
                        if($time&&($time + $fileStoreLifetime > time())){
                            $obj = new static;
                            $obj->__put($modelProperties, true);
                            return $obj;
                        }
                    }else{
                        $obj = new static;
                        $obj->__put($modelProperties, true);
                        return $obj;
                    }
                }

                // 查询数据并缓存之
                $result = $querier->requires()->where($pk, $guid)->select();
                //var_dump($result);
                if($result&&$modelProperties = $result->item()){
                    $staticFileStorage->store($guid, $modelProperties);
                    $obj = new static;
                    $obj->__put($modelProperties, true);
                    return $obj;
                }
            }else{
                // 查询数据
                $result = $querier->requires()->where($pk, $guid)->select();
                if($result&&$modelProperties = $result->item()){
                    $obj = new static;
                    $obj->__put($modelProperties, true);
                    return $obj;
                }
            }
            return false;
        }
        new Status(1422.2, 'Model File Be Tampered', 'R3like Model must have an index key as the primary key.', true);
    }
    
    /**  
	 * 关系数据库行数据对象构造函数
	 * 
	 * @access public
     * @final
	 * @return 构造函数无返回值
	**/ 
    final public function __construct(){
        // 获取默认数据行查询器，并寄存为querier属性
        $this->querier = static::initQuerier();
        // 获取默认数据存储仓，并寄存为storage属性
        $this->files = self::getFileStorage();
        // 通过数据默值数组初始化属性数组
        $this->modelProperties = static::$defaultPorpertyValues ;

        // 检查是否存在主键
        if(isset(static::$uniqueIndexes[0])){
            $this->pk = static::$uniqueIndexes[0];
        }else{
            // R3模型必需存在一个主键索引，否则系统将中断进程
            new Status(1460.4, 'Using Module Error', 'Must Have An Index Field!', true);
        }
    }
    
    /**  
	 * 创建属性数组
	 * 
	 * @access protected
     * @param array|object $input           源数据
     * @param bool|object $savedProperties  已存在的记录
	 * @return object
	**/ 
    protected function __put(array $input, $isSaved = false){
        $this->xml = NULL;
        // 如果已存在记录，则与存在的记录对比，否则与默认值数组对比
        if($isSaved){
            $this->modelProperties = $this->savedProperties = self::correctArrayByTemplate($input, static::$defaultPorpertyValues);
            $this->__guid = $this->savedProperties[$this->pk];
        }else{
            if($this->savedProperties){
                $defaultPorpertyValues  = $this->savedProperties;
            }elseif($this->modelProperties){
                $defaultPorpertyValues  = $this->modelProperties;
            }else{
                $defaultPorpertyValues  = static::$defaultPorpertyValues;
            }
            $this->modelProperties = self::correctArrayByTemplate($input, static::$defaultPorpertyValues, $defaultPorpertyValues, false);
        }
        if(static::$recordType){
            $this->modelProperties[$this->ck] = self::getRecordsCount($this->__guid);
        }
        return $this;
    }

    /**  
	 * 提交保存
	 * 
	 * @access public
	 * @return object|bool
	**/ 
    public function save(){
        if($this->savedProperties){
            return $this->__update();
        }
        return $this->__insert();
    }

    protected function __checkInsertData(array $post){
        $post = self::correctArrayByTemplate($post, static::$defaultPorpertyValues);
        return self::__checkValues($post, true);
    }

    protected function __checkUpdateData(array $update, array $savedProperties){
        $update = array_intersect_key($update, $savedProperties);
        return self::__checkValues($update);
    }

    /**  
	 * 销毁后的整理操作
	 * 
	 * @access protected
	 * @return bool
	**/ 
    protected function __afterDelete(){
        return true;
    }
}