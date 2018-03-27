<?php
namespace AF\Models;

use PDO;
use Status;
use Tangram\MODEL\ObjectModel;
use Storage;
use DBQ;

/**
 * @class AF\Models\BaseDeepModel;
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseMapModel extends BaseModel {
	private static
    // RDBQuerier实例缓存组，以类名为索引，以保证不同BaseR3Model子类有且仅有一个链接
    $privateRDBQueries = [];

    protected static
    // 使用的数据库链接索引
    $rdbConnectionIndex = 0,

    // 数据表前缀(默认为应用数据表前缀)
    $tablenamePrefix = TP_CURR,

    // 数据表前缀(默认为应用数据表前缀)
    $tablenamePrefixRewritable = false,

    // 数据表名称（不包含表前缀）
    $tablenameAlias = '',

    // 唯一索引键组
	$uniqueIndexes = [],

	// 自增键，如果无则为NULL
    $AIKEY = NULL,
	
	// 联合索引键组
    $jointIndexes = [],

    // 缓存位置
    $fileStoragePath = false,

    // 默认值，用于新增记录
    $defaultPorpertyValues  = [];

    /**
	 * 获取默认PDOX数据连接
     * 不调用则不会生成
	 * 
	 * @access protected
	 * @static
	 * @return object
	**/
    protected static function initQuerier(){
        $class = strtolower(get_called_class());
        if(empty($privateRDBQueries[$class])){
            $privateRDBQueries[$class] = new DBQ(static::$rdbConnectionIndex);
            if(empty(static::$tablenameAlias)){
                $classname = explode("\\", $class);
                static::$tablenameAlias = str_replace('model', '', end($classname));
            }
        }
        $tablename = static::$tablenamePrefix . static::$tablenameAlias;
        return $privateRDBQueries[$class]->using($tablename);
    }

    public static function pickKeys(array $input){
        // 检查是否存在联合索引键组
        if(count(static::$jointIndexes)>1){
            $keys = [];
            foreach(static::$jointIndexes as $key){
                if(empty($input[$key])){
                    new Status(1415, '', json_encode($input), true);
                }
                $keys[$key] = $input[$key];
            }
            return $keys;
            
        }
        new Status(1422.2, 'Model File Be Tampered', 'Map Model Must Have Joint Keys.', true);
        
    }

    public static function joinKeys2guid(array $keys, $pickfirst = false){
        // 检查是否存在联合索引键组
        if($pickfirst){
            $keys = static::pickKeys($keys);
        }
        if(count($keys)===count(static::$jointIndexes)){
            return md5(json_encode($keys));
        }
        new Status(1415, true);        
    }

    /**
	 * 查询数据，并返回整理后的结果
	 * 
	 * @access public
	 * @static
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
     * @param array $orderby                            排序规则，一个或多个条件数组形如[fieldname, isreverse, sortby]的数组构成
     * @param int $returnFormat                         返回列表类型，请使用模型自带的常量来标记
	 * @return array
	**/
    public static function query($require = "1 = 1", array $orderby = [['1', false, self::SORT_REGULAR]], $returnFormat = self::LIST_AS_OBJS, $selecte = '*'){
        // 获取默认数据行查询器
        $querier = static::initQuerier();

        // 准备一个空数组，用来存放查询结果
        $objs = [];
        // 整理查询条件
		if(is_string($require)||is_array($require)){
            $require = $require;
        }else{
            $require = "1";
        }
        // 依次整理并录入排序规则到查询器
        $querier->orderby(false);
        foreach ($orderby as $order) {
            static::setQuerySelectOrder($querier->requires($require), $order);
        }
        // 查询数据库
        $result = $querier->select($selecte);

        if($result){
            if($returnFormat===self::LIST_AS_ARRS){
                return $result->getArrayCopy();
            }
            while(($pdos = $result->getIterator())&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $objs[] = new static($modelProperties, static::joinKeys2guid($modelProperties, true));
            }
        }
        return $objs;
	}
	
	/**
	 * 依次整理并录入排序规则到查询器
	 * 
	 * @access public
	 * @static
     * @param array $order                              组形如[fieldname, isreverse, sortby]的数组构成
     * @param object(Tangram\CTRLR\RDBQuerier) $querier 查询器实例
	 * @return object
	**/
    public static function setQuerySelectOrder(\Tangram\CTRLR\RDBQuerier $querier, $order){
        if(isset($order[0])&&isset($order[1])){
            if(isset($order[2])){
                switch($order[2]){
                    // 为汉字增加拼音方式的排序
                    case self::SORT_CONVERT_GBK:
                    $orderFieldName = 'CONVERT('.(string)$order[0].' USING gbk)';

                    default:
                    $orderFieldName = (string)$order[0];
                }
            }else{
                $orderFieldName = (string)$order[0];
            }
            $querier->orderby($orderFieldName, !!$order[1]);
        }
        return $querier;
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
                    $obj = new static($modelProperties, static::joinKeys2guid($modelProperties, true));
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
	 * 以唯一索引键为方法名的查询单行记录的魔术方法
	 * 
	 * @access public
	 * @static
     * @param string $name 唯一索引键名
     * @param array $arguments 魔术方法的实参数组，其中第一个元素即为要比对的索引值
	 * @return object|bool
	**/
    public static function __callStatic($name, array $arguments){
        if(count(static::$uniqueIndexes)>0&&count($arguments)>0){
            // 确定存在索引，并小写化
            $name = strtolower($name);
            
            if(in_array($name, static::$uniqueIndexes)){
                $result = static::initQuerier()->requires()->where($name, $arguments[0])->select();
                if($result&&$modelProperties = $result->item()){
                    return new static($modelProperties, static::joinKeys2guid($modelProperties, true));
                }
            }
        }
        // 查询失败，返回false
        return false;
	}
	
	/**
	 * 通过主键查询记录
	 * 
	 * @access public
	 * @static
     * @param string $guid 主机值
	 * @return object|bool
	**/
    public static function byGUID($keys){
        // 连接并哈希化主键
        $keys = static::pickKeys($keys);
        $guid = static::joinKeys2guid($keys);

        $querier = static::initQuerier();
        $result = $querier->requires($keys)->select();
        if($result&&$modelProperties = $result->item()){
            return new static($modelProperties, $guid);
        }
        return false;
	}
	
	/**
	 * 新增记录
     * 成功返回记录实例，失败返回false
	 * 
	 * @access public
     * @final
	 * @static
     * @param array|object $modelProperties 源数据
	 * @return object|bool
	**/
    public static function create(array $modelProperties = []){
        $modelProperties = self::correctArrayByTemplate($modelProperties, static::$defaultPorpertyValues);
        return new static($modelProperties);
    }

    protected static function __checkPostData(array $input){
        return self::correctArrayByTemplate($input, static::$defaultPorpertyValues);
    }

    /**
	 * 新增记录
     * 成功返回记录实例，失败返回false
	 * 
	 * @access public
     * @final
	 * @static
     * @param array|object $input 源数据
	 * @return object|bool
	**/
    public static function post(array $input){ 
        $data = static::__checkPostData($input);
        // 如果为新增记录
        if(static::$AIKEY) {
            // 剔除掉自增见
            unset($data[static::$AIKEY]);
        }
        if($data){
            $querier = static::initQuerier();
            try {
                if($querier->insert($input)){
                    $keys = static::pickKeys($input);
                    $modelProperties = $querier->requires($keys)->select()->item();
                    return new static($modelProperties, static::joinKeys2guid($keys));
                }
            } catch (\Exception $e) {
                
            }
        }        
        return false;
    }
    
    public static function postIfNotExists(array $input){
        if($obj = static::byGUID($input)){
            return $obj;
        }
        return static::post($input);
	}
	
	/**
	 * 删除一行或多行记录
	 * 
	 * @access public
     * @final
	 * @static
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
	 * @return int
	**/
    public static function delete($require){
        // 获取默认数据行查询器
        $querier = static::initQuerier();

        if($querier->requires($require)->delete()!==false){
            self::cleanFileStorage();
            return true;
        }
        return false;        
    }

    protected
    // PDOX数据链接实例
    $querier,
    // 存储仓，可以不使用默认仓
    $files;
    
    /**  
	 * 关系数据库行数据对象构造函数
	 * 
	 * @access public
     * @final
	 * @return 构造函数无返回值
	**/ 
    final protected function __construct($modelProperties, $guid = NULL){
        // 获取默认数据行查询器，并寄存为querier属性
        $this->querier = static::initQuerier();
        // 获取默认数据存储仓，并寄存为storage属性
        $this->files = self::getFileStorage();

        if($guid){
            $this->modelProperties = $this->savedProperties = $modelProperties;
            $this->__guid = $guid;
        }else{
            $this->modelProperties = $modelProperties;
        }
    }
	
	/**  
	 * 销毁记录
	 * 
	 * @access public
	 * @return bool
	**/ 
    public function destroy(){
        $keys = static::pickKeys($this->savedProperties);
        if($this->savedProperties&&($this->querier->requires($keys)->delete()!==false)){
            if($this->files) $this->files->store($this->__guid);
            return $this->__afterDelete();
        }
        return false;
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