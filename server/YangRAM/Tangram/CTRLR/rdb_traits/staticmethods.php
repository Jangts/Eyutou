<?php
// 核心控制模块公用特性所使用的命名空间，命名空间Tangram\CTRLR的子空间
namespace Tangram\CTRLR\rdb_traits;

// 引入相关命名空间，以简化书写
use Tangram\MODEL\RDBRowsCollection;

/**
 * @trait Tangram\CTRLR\rdb_traits\staticmethods
 * Static Methods For Querying RDB Fast
 * 快速查询关系数据的静态方法
**/
trait staticmethods {
    /**
     * 静态查询接口
     * 底层接口
     * 
     * @access private
     * @static
     * @param string $queryString                                           SQL语句
     * @param object(Tangram\CTRLR\rdb_drivers\_abstract) $pdox    PDO子类实例
     * @return object(Tangram\MODEL\RDBRowsCollection)|bool
    **/
    private static function query($queryString, $pdox){
        if($queryString==false){
            return false;
        }
        if($result = $pdox->query($queryString)){
            return new RDBRowsCollection($result);
        }
        return false;
    }
    
    /**
     * 链接数据库
     * 
     * @access public
     * @static
     * @param int|array $id 预设链接代号或自定义配置表
     * @return bool
    **/
    public static function connectPDOX($id = 0){
        if(self::$lastPDOXConn = self::conn($id)){
            if(is_numeric($id)){
                self::$id = $id;
            }else{
                self::$id = NULL;
            }
            return true;
        }
        self::$id = NULL;
        return false;
    }
    
    /**
     * 获取最近一次通过connectPDOX()方法链接的活动链接
     * 
     * @access public
     * @static
     * @return object|null
    **/
    public static function getLastPDOXConn(){
        // 拥有使用所有链接的权限
		if(self::$permissions->ALL_PDOX_USEABLE){
            return self::$lastPDOXConn;
        }

        // 拥有使用首选链接的权限
        if(self::$permissions->DEFAULT_PDOX_USEABLE){
            if(self::$id===0){
                return self::$lastPDOXConn;
            }
        }

        // 拥有使用当表链接的权限
        if(self::$permissions->ACTIVITY_PDOX_USEABLE){
            if(self::$id===CI_CURR){
                return self::$lastPDOXConn;
            }
		}
		return NULL;
    }

    /**
     * 标准快速查询接口
     * 返回一个Tangram\MODEL\RDBRowsCollection对象
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param string        $select         筛选字段，默认为所有字段
     * @return object(Tangram\MODEL\RDBRowsCollection)
    **/
    public static function get($table, $require = "1", $order = "1 ASC", $select = "*"){
		return self::query(self::staticGetQuerySelectString($table, $require, $order, 0, 0, $select), self::conn(0));
    }
    
    /**
     * 快速查询接口，以数组形式返回结果
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param string        $select         筛选字段，默认为所有字段
     * @return array
    **/
    public static function arr($table, $require = "1", $order = "1 ASC", $select = "*"){
		if($result = self::get($table, $require, $order, $select)){
			return $return->getArrayCopy();
		}
		return [];
    }
    
    /**
     * 快速查询接口，以XML格式的字串形式返回结果
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param string        $select         筛选字段，默认为所有字段
     * @return string
    **/
    public static function xml($table, $require = "1", $order = "1 ASC", $select = "*"){
		if($result = self::get($table, $require, $order, $select)){
			return $return->xml_encode();
		}
		return '<?xml version="1.0" encoding="utf-8"?><result></result>';
    }
    
    /**
     * 快速查询接口，以JSON格式的字串形式返回结果
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param string        $select         筛选字段，默认为所有字段
     * @return string
    **/
    public static function json($table, $require = "1", $order = "1 ASC", $select = "*"){
		if($result = self::get($table, $require, $order, $select)){
			return $return->json_encode();
		}
		return '[]';
    }
    
    /**
     * 有数量限制的快速查询接口
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param int           $num            返回结果的最大数量
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param string        $select         筛选字段，默认为所有字段
     * @return object(Tangram\MODEL\RDBRowsCollection)
    **/
    public static function tops($table, $require = "1", $num = "10", $order = "1 ASC", $select = "*"){
		return self::query(self::staticGetQuerySelectString($table, $require, $order, 0, $num, $select), self::conn(0));
    }
    
    /**
     * 单行数据快速查询接口
     * 仅查询并返回一行数据
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param string        $select         筛选字段，默认为所有字段
     * @return array
    **/
    public static function one($table, $require = "1", $order = "1 ASC", $select = "*"){
		$rows = self::query(self::staticGetQuerySelectString($table, $require, $order, 0, "1", $select), self::conn(0));
        if($rows&&$row = $rows->item()){
            return $row;
        }
        return false;
    }
    
    /**
     * 使用主键索引的单行数据快速查询接口
     * 仅对主键键名为id的数据表有效
     * 
     * @access public
     * @static
     * @param string        $table          要查询的表
     * @param string        $id             主键值
     * @param string        $select         筛选字段，默认为所有字段
     * @return array
    **/
	public static function id($table, $id, $select = "*"){
		$id = sprintf('%s', $id);
		return self::one($table, "id = '$id'", "1 ASC", $select);
    }
    
    /**
     * JION表链接语句生成接口
     * 
     * @access public
     * @static
     * @param array  $left          表一
     * @param array  $right         表二
     * @return string
     * 
     * 左右表的数组参数规范
     * table        表名
     * field        关联字段
     * as           表别名
    **/
    public static function multtable(array $left, array $right){
        $tableLeft = $left['table'];
        $fieldLeft = $left['field'];
        $aliasLeft = isset($left['as']) ? $left['as'] : 'L';
        $tableRight = $right['table'];
        $fieldRight = $right['field'];
        $aliasRight = isset($right['as']) ? $right['as'] : 'R';
		$table = "%s` AS %s RIGHT JOIN `%s` AS %s ON %s.`%s` = %s.`%s";
		return sprintf($table, $tableRight, $aliasRight, $tableLeft, $aliasLeft, $aliasRight, $fieldRight, $aliasLeft, $fieldLeft);
    }
    
    /**
     * 支持JION表链接的静态查询接口
     * 
     * @access public
     * @static
     * @param array     $left           表一
     * @param array     $right          表二
     * @param string    $require        查询条件段
     * @param string    $order          排序段
     * @param string    $select         筛选字段，默认为所有字段
     * @param int       $num            返回结果的最大数量
     * @return array
    **/
    public static function join(array $left, array $right, $require = "1", $order = "1 ASC", $select = "*", $num = 0){
		$table = self::multtable($left, $right);
		return self::query(self::staticGetQuerySelectString("`$table`", $require, $order, 0, $num, $select), self::conn(0));
    }
    
    /**
     * 快速查询数量的接口
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @return int
    **/
    public static function num($table, $require = "1"){
        if($result = self::query(self::staticGetQuerySelectString($table, $require, "1 ASC", NULL, 'count(*) as sum'), self::conn(0))){
            return intval($result->fetchColumn());
        }
        return 0;
	}
}