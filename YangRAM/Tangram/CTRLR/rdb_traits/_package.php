<?php
// +-----------------------------------------------------------------------+
// | Module		核心控制模块公用特性包
// +-----------------------------------------------------------------------+
// | Contain	Tangram\CTRLR\rdb_traits\common 关系数据处理类的基础特性
// |            Tangram\CTRLR\rdb_traits\staticmethods 快速查询关系数据的静态方法
// |            Tangram\CTRLR\rdb_traits\field  关系数据库字段管理方法集
// |            Tangram\CTRLR\rdb_traits\transaction 关系数据处理类的事件处理方法集
// +-----------------------------------------------------------------------+
// | Author		Jangts
// +-----------------------------------------------------------------------+
// | Time		2018年1月11日
// +-----------------------------------------------------------------------+

// 核心控制模块公用特性所使用的命名空间，命名空间Tangram\CTRLR的子空间
namespace Tangram\CTRLR\rdb_traits;

// 引入相关命名空间，以简化书写
use PDO;
use RDBQuerier;
use Status;
use Tangram\MODEL\ApplicationPermissions;
use Tangram\MODEL\RDBRowsCollection;

/**
 * @trait Tangram\CTRLR\rdb_traits\common
 * Basics Methods For RDBQuerier
 * 关系数据处理类的基础方法
**/
trait common {
    // 公共表名单
    private static $public_tables;

    protected static
    $initialized = false,
    $conns = NULL,
    $lastPDOXConn = NULL,
    $permissions = NULL;

    public static
    $unreadableTable = '',
    $unwritableTable = '';
    
    /**
     * 检查子应用对公共表的维护权限
     * 
     * @access public
     * @static
     * @param string  $tablename    要检查的表
     * @return bool|null
     * 
    **/
    private static function service($tablename){
        if(self::$public_tables){
            $tables = self::$public_tables;
        }else{
            $storage = new \Storage(RUNPATH_SYS.'publictables/');
            $tables = $storage->take(DB_PUB);
            if(!$tables){
                $tables = [];
                $rows = self::get(DB_PUB.'tables', "`app_id` <> '0' AND relation_type > 0");
                foreach($rows as $row){
                    if(empty($tables[DB_PUB.$row['table_name']])){
                        $tables[DB_PUB.$row['table_name']] = [];
                    }
                    $tables[DB_PUB.$row['table_name']][$row['app_id']] = $row['relation_type'];
                }
                $storage->store(DB_PUB, $tables);
            }
            self::$public_tables = $tables;
        }
        if(isset($tables[$tablename])){
            // 所用应用有权维护
            if(isset($tables[$tablename]['all'])){
                return true;
            }
            // 当前应用有权维护
            if(isset($tables[$tablename][AI_CURR])){
                return true;
            }
            return false;
        }else{
            new Status(1443, 'Nonregister Tablename', 'Sorry, The Public Table ['.$tablename.'] has not been register.', true);
        }
    }

     /**
     * 数据库链接相关类的通用初始化方法
     * 
	 * @access public
     * @static
     * @param object(Tangram\MODEL\ApplicationPermissions) $permissions    拷贝一份权限表
     * @param array $conns                                      拷贝一份PDOX链接配置表
     * @return bool
    **/
    public static function initialize(ApplicationPermissions $permissions, array $conns){
		if(self::$initialized==false){
            self::$permissions = $permissions;
            self::$conns = $conns;
			self::$initialized = true;
            return true;
		}
        return false;
    }

    /**
     * 链接指定数据库
     * 
	 * @access public
     * @static
     * @param int|array     $options    预设链接代号或自定义配置表
     * @return bool
    **/
    private static function conn($options){
        if(is_numeric($options)&&isset(self::$conns[$options])){
            if(self::$conns[$options]['instance']){
                return self::$conns[$options]['instance'];
            }else{
                include_once(CPATH.'CTRLR/rdb_drivers/'.self::$conns[$options]['driver'].'.php');
			    $class = 'Tangram\CTRLR\rdb_drivers\\'.self::$conns[$options]['driver'];
                return self::$conns[$options]['instance'] = $class::instance(self::$conns[$options]['options']);
            }
        }elseif(is_array($options)&&$options['driver']&&is_file(CPATH.'CTRLR/rdb_drivers/'.$options['driver'].'.php')){
            include_once(CPATH.'CTRLR/rdb_drivers/'.$options['driver'].'.php');
			$class = 'Tangram\CTRLR\rdb_drivers\\'.$options['driver'];
            return $class::instance($options);
        }
        return NULL;
    }

    /**
     * 对SQL语句或片段进行检查与转码
     * 此方法本来是用来进行引号处理的，但现在已经删除了相关代码，目前只能做是否文本的判断
     * 此方法随时可能废弃
     * 
	 * @access private
     * @static
     * @param mixed $str    要检查的文本
     * @return string
    **/
    private static function escape($str){
        if(is_string($str)){
            return $str;
        }
        return '';
    }

    /**
     * 检查数据表名称
     * 
     * @access private
     * @static
     * @param string    $str    要检查的文本
     * @return string
    **/
    private static function tablename($str){
        if(preg_match("/^\w+$/", $str)){
            return '`' . $str . '`';
        }
        return self::escape($str);
    }

    /**
     * 生成SQL语句
     * 
     * @access public
     * @static
     * @param string|array  $table          要查询的表，多个表可使用数组传入，多表传入要求各表结构一致
     * @param string        $require        查询条件段
     * @param string        $order          排序段
     * @param int           $num            返回结果的最大数量
     * @param int           $start          返回结果的起始位置
     * @param string        $select         筛选字段，默认为所有字段
     * @return string|bool
    **/
    private static function staticGetQuerySelectString($tables, $require = "1", $order = "1 ASC", $num = 0, $start = 0, $select = "*"){
        if(is_array($tables)){
			foreach($tables as $n=>$table){
                if(self::readable($table)){
                    if($n==0){
    					$sql = sprintf(self::SQL_LEFT, $select, $table, $require);
    				}else{
    					$sql .= sprintf(self::SQL_RIGHT, $select, $table, $require);
    				}
                }else{
                    return false;
                }
			}
            if($order){
                $sql .= sprintf(self::SQL_ORDER, $order);
            }
            if($num){
                $sql .= sprintf(self::SQL_LIMIT, $start, $num);
            }
			return $sql;
		}
		if(is_string($tables)){
            $table = self::tablename($tables);
            if(self::readable($table)){
                $sql = sprintf(self::SQL_SINGLE, self::escape($select), self::tablename($tables), self::escape($require), self::escape($order));
                if($num){
                    $sql .= sprintf(self::SQL_LIMIT, $start, $num);
                }
                return $sql;
            }
		}
		return false;
    }
    
    /**
     * 获取数据表操作的权限代号
     * 
     * @access public
     * @static
     * @param string  $table          要查询的表
     * @return int
     * 
     * 代号说明
     * 0：无权读写
     * 1：对该表可写
     * 2：对该表可读写
     * 3：对该表可读
    **/
    private static function getApplicationPermissionsCode($table){
        // 进行处于核心态时，所有表可读写
        if(!defined('TP_CURR')){
            // 返回2
            return 2;
        }

        // 对应用自建表始终可读写
        if(strpos($table, DB_PUB) === 0||strpos(strtolower($table), strtolower(TP_CURR)) === 0){
            // 返回2
            return 2;
        }

        // 开始比对权限表
        $permissions = self::$permissions;

        // 如果应用可读所有表
        if($permissions->ALL_RDBTABLE_READABLE){
            // 且如果同时可写所有表
            if($permissions->ALL_RDBTABLE_WRITEABLE){
                // 即为可读写所有表，返回2
                return 2;
            }
            // 返回3
            return 3;
        }

        // 如果应用可写所有表
        if($permissions->ALL_RDBTABLE_WRITEABLE){
            // 返回1
            return 1;
        }

        // 如果数据表为用户信息表
        if(strpos($table, DB_USR) === 0){
            // 用户套件始终可读写用户信息表
            if(strpos(AI_CURR, 'USERS') === 0){
                // 返回2
                return 2;
            }
            // 如果应用有权读取用户信息表
            if($permissions->USR_RDBTABLE_READABLE){
                // 返回3
                return 3;
            }
        }

        // 如果数据表为云盘表
        elseif(strpos($table, DB_YUN) === 0){
            // 云盘套件始终可读写云盘表
            if(strpos(AI_CURR, 'CLOUD') === 0){
                // 返回2
                return 2;
            }
            // 如果应用有权读取云盘表
            if($permissions->CLOUD_RDBTABLE_READABLE){
                // 返回3
                return 3;
            }
        }

        // 如果数据表为注册表
        elseif(strpos($table, DB_REG) === 0){
            // 如果应用有权写入注册表
            if($permissions->REG_RDBTABLE_WRITEABLE){
                // 返回2
                return 2;
            }
            // 注册表始终可读，返回3
            return 3;
        }

        // 如果数据表为公共表
        if(strpos($table, DB_PUB) === 0){
            // 如果应用有权写入公共表
            if($permissions->PUBLIC_RDBTABLE_WRITEABLE){
                // 返回2
                return 2;
            }
            if(self::service($table)){
                // 返回2
                return 2;
            }
            // 公共表始终可读，返回3
            return 3;
        }

        // 对数据表不可读写，返回0
        return 0;
    }

    /**
     * 检查进程对数据表的可读性
     * 
     * @access public
     * @static
     * @param string  $table          要查询的表
     * @return bool|null
     * 
    **/
    private static function readable($table){
        $code = self::getApplicationPermissionsCode($table);
        if($code>1){
            self::$unreadableTable = '';
            return true;
        }
        if(_USE_DEBUG_MODE_){
            return new Status(1411, '', 'Application ['.AI_CURR.'] has no access to read data from the table ['.$table.']', true);
        }
        self::$unreadableTable = $table;
        return false;
    }

    /**
     * 检查进程对数据表的可写性
     * 
     * @access public
     * @static
     * @param string  $table          要查询的表
     * @return bool|null
     * 
    **/
    private static function writeable($table){
        $code = self::getApplicationPermissionsCode($table);
        if($code&&$code<3){
            self::$unwritableTable = '';
            return true;
        }
        if(_USE_DEBUG_MODE_){
            return new Status(1411, '', 'Application ['.AI_CURR.'] has no access to write data to the table ['.$table.']', true);
        }
        self::$unwritableTable = $table;
        return false;
        
    }
}

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
		return self::query(self::staticGetQuerySelectString($table, $require, $order, 0, 0, $select), self::$conn);
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
		return self::query(self::staticGetQuerySelectString($table, $require, $order, 0, $num, $select), self::$conn);
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
		$rows = self::query(self::staticGetQuerySelectString($table, $require, $order, 0, "1", $select), self::$conn);
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
		return self::query(self::staticGetQuerySelectString("`$table`", $require, $order, 0, $num, $select), self::$conn);
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
        if($result = self::$conn(self::staticGetQuerySelectString($table, $require, "1 ASC", NULL, 'count(*) as sum'))){
            return intval($result->fetchColumn());
        }
        return 0;
	}
}

/**
 * @trait Tangram\CTRLR\rdb_traits\field
 * RDB Fields Manage Methods
 * 关系数据库字段管理方法集
**/
trait field {
    public function getFields(){
        if(count($this->tables)===1){
            return $this->pdox->getTableFields($this->tables[0]);
        }
        $fields = [];
        foreach($this->tables as $tablename){
            $fields[$tablename] = $this->pdox->getTableFields($tablename);
        }
        return $fields;
    }

    public function addField($fieldname, $fieldtype = 'INT NOT NULL', $otherset = ''){
        $fieldset = $this->pdox->trueSetString([$fieldset, $otherset]);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` ADD `%s` %s;', $this->tables[0], $fieldname, $fieldset);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` ADD `%s` %s;', $fieldname, $fieldset);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function changeField($fieldname, $fieldset = '', $newname = NULL){
        $newname = ($newname?$newname:$fieldname);
        $fieldset = $this->pdox->trueSetString([$fieldset]);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` CHANGE `%s` `%s` %s;', $this->tables[0], $fieldname, $newname, $fieldset);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` CHANGE `%s` `%s` %s;', $fieldname, $newname, $fieldset);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function renameField($fieldname, $newname){
        $newname = ($newname?$newname:$fieldname);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` CHANGE `%s` `%s`;', $this->tables[0], $fieldname, $newname);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` CHANGE `%s` `%s`;', $fieldname, $newname);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function modifyField($fieldname, $fieldset = ''){
        $fieldset = $this->pdox->trueSetString([$fieldset]);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` MODIFY `%s` %s;', $this->tables[0], $fieldname, $fieldset);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` MODIFY `%s` %s;', $fieldname, $fieldset);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function dropField($fieldname){
        $fieldset = $this->pdox->trueSetString($fieldset);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` DROP `%s`;', $this->tables[0], $fieldname);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` DROP `%s`;', $fieldname);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function setDefault($fieldName, $value){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->addDefaultValue($tablename, $fieldName, $value);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function unsetDefault($fieldName){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropDefaultValue($tablename, $fieldName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function setPrimaryKey($primaryKey){
        $num = func_num_args();
        $results = [];
        if($num>1){
            $args = func_get_args();
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->addUnionPrimaryKey($tablename, $args);
            }
        }else{
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->addPrimaryKey($tablename, $primaryKey);
            }
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function unsetPrimaryKey($primaryKeyName = NULL){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropPrimaryKey($tablename, $primaryKeyName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function setIncrement($int = 1){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->setIncrement($tablename, $int);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function setForeignKey($field, $foreignTable, $foreignField, $foreignKeyName = NULL){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropForeignKey($tablename, $field, $foreignTable, $foreignField, $foreignKeyName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function unsetForeignKey($foreignKeyName){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropForeignKey($tablename, $foreignKeyName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function addIndex($fieldName, $unique = false){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->createIndexConstraint($tablename, $fieldName, $fieldName, $unique);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function addUnionIndex($indexName, array $fields, $unique = false){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->createIndexConstraint($tablename, $indexName, $fields, $unique);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function dropIndex($indexname){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropIndexConstraint($tablename, $indexname);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function addCheck($condition, $checkName = NULL){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->addCheckConstraint($tablename, $condition, $checkName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function dropCheck($checkName){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropCheckConstraint($tablename, $checkName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }
}


/**
 * @trait Tangram\CTRLR\rdb_traits\transaction
 * Transaction Methods For Dealing Relational Data
 * 关系数据处理类的事件处理方法集
**/
trait transaction {
    public function lock(){
        return $this->pdox->lock();
    }

    public function unlock($__key){
        $this->pdox->unlock($__key);
        return $this;
    }

    public function beginAndLock($commitPrevious = true, $rollBackPrevious = true){
        $this->begin($commitPrevious, $rollBackPrevious);
        return $this->pdox->lock();
    }

    public function begin($commitPrevious = true, $rollBackPrevious = true){
        if($this->pdox->locked()){
            return false;
        }
        if($this->pdox->inTransaction()){
            if($commitPrevious){
                $this->pdox->commit();
                return $this->pdox->beginTransaction();
            }
            if($rollBackPrevious){
                $this->pdox->rollBack();
                return $this->pdox->beginTransaction();
            }
            return true;
        }
        return $this->pdox->beginTransaction();
    }

    public function is_intrans(){
        return $this->pdox->inTransaction();
    }

    public function rollBack(){
        if($this->pdox->locked()){
            return false;
        }
        if($this->pdox->inTransaction()){
            return $this->pdox->rollBack();
        }
        return false;
    }

    public function commit(){
        // var_dump($this->pdox->locked());
        //             exit;
        if($this->pdox->locked()){
            return false;
        }
        // var_dump($this->pdox->inTransaction());
        //             exit;
        if($this->pdox->inTransaction()){
            return $this->pdox->commit();
        }
        return false;
    }
}