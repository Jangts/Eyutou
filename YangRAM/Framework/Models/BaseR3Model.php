<?php
namespace AF\Models;

use PDO;
use Status;
use App;

/**
 * @class AF\Models\BaseR3Model
 * RDB Record Row Data Model
 * 关系数据库行数据模型
 * 关系数据模型的进一步拓展，此模型更加
 * 紧密地与数据库中的某个表关联，一个
 * 实例代表了数据库中的一行数据
 * 本模型是用来拓展其他模型类的基类，属于抽象类，并不能直接使用
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseR3Model extends BaseModel {
    use traits\crud;

    const
    CONN_ROW = 0,
    CONN_TABLE = 1;

    private static
    // RDBQuerier实例缓存组，以类名为索引，以保证不同BaseR3Model子类有且仅有一个链接
    $privateRDBQueries = [];

    protected static
    // 使用的数据库链接索引
    $rdbConnectionIndex = 0,

    // 链接类型，1为高级，0为行级
    $rdbConnectionType = BaseR3Model::CONN_TABLE,

    // 数据表前缀(默认为应用数据表前缀)
    $tablenamePrefix = TP_CURR,

    // 数据表前缀(默认为应用数据表前缀)
    $tablenamePrefixRewritable = false,

    // 数据表名称（不包含表前缀）
    $tablenameAlias = '',

    // 唯一索引键组
    $uniqueIndexes = ['id'],

    // 自增键，如果无则为NULL
    $AIKEY = NULL,

    // 缓存位置
    $fileStoragePath = false,

    // 默认值，用于新增记录
    $defaultPorpertyValues  = [
        'id'    =>  0
    ],
    // 约束规则，不建议使用，可能会被废除的设计
    $constraint = [];

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
            // 确定存在索引

            // 小写化字段名
            $name = strtolower($name);

            // 如果索引键为主键，默认第一索引键必须是主键
            if($name===static::$uniqueIndexes[0]){
                return self::byGUID($arguments[0]);
            }

            // 其他索引键
            if(in_array($name, static::$uniqueIndexes)){
                $result = static::initQuerier()->requires()->where($name, $arguments[0])->select();
                if($result&&$modelProperties = $result->item()){
                    $obj = new static;
                    $obj->__put($modelProperties, true);
                    return $obj;
                }
            }
        }
        // 查询失败，返回false
        return false;
    }

    /**
	 * 更改数据表前缀
     * 当活动应用不是模型的所属应用时，允许矫正模型的数据表前缀
	 * 
	 * @access public
	 * @static
     * @param object(Tangram\MODEL\Application) $app
	 * @return object
	**/
    public static function __correctTablePrefix(App $app){
        if(static::$tablenamePrefixRewritable===true){
            static::$tablenamePrefix = $app->DBTPrefix;
        }
        return false;
    }

    protected
    // PDOX数据链接实例
    $querier,
    // 主键名
    $pk,
    // 存储仓，可以不使用默认仓
    $files,
    // 自动统计键，可与自定义统计键组合使用
    $ck = '__count';

    /**  
	 * 克隆实例
	 * 
	 * @access public
	 * @return object
	**/ 
    public function getCloneObject(){
        $obj = new self;
        if($this->__guid){
            unset($this->modelProperties[$this->pk]);
        }
		return $obj->__put($this->modelProperties, false);
    }
}