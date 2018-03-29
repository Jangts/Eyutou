<?php
// 核心控制模块公用特性所使用的命名空间，命名空间Tangram\CTRLR的子空间
namespace Tangram\CTRLR\rdb_traits;

// 引入相关命名空间，以简化书写
use Status;
use Tangram\MODEL\ApplicationPermissions;

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
            if(isset($tables[$tablename][CACAI])){
                return true;
            }
            return false;
        }else{
            new Status(1461, 'Nonregister Tablename', 'Sorry, The Public Table ['.$tablename.'] has not been register.', true);
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
        new Status(400, true);
        if(is_numeric($options)&&isset(self::$conns[$options])){
            // var_dump($options, self::$conns[$options]['instance']);
            if(self::$conns[$options]['instance']!=NULL){
                return self::$conns[$options]['instance'];
            }else{
                // var_dump(CPATH.'CTRLR/rdb_drivers/'.self::$conns[$options]['driver'].'.php');
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
            if(self::readable($tables)){
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
        if(!defined('CACAT')){
            // 返回2
            return 2;
        }

        // 对应用自建表始终可读写
        if(strpos($table, DB_PUB) === 0||strpos(strtolower($table), strtolower(CACAT)) === 0){
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
            // 用户应用始终可读写用户信息表
            if(strpos(CACAI, 'USERS') === 0){
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
            if(strpos(CACAI, 'CLOUD') === 0){
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
        elseif(strpos($table, DB_REG.'user') === 0){
            // 用户注册表

            // 用户应用始终可读写用户注册表
            if(strpos(CACAI, 'USERS') === 0){
                // 返回2
                return 2;
            }
            // 注册表始终可读，返回3
            return 3;
        }
        elseif(strpos($table, DB_REG) === 0){
            // 常规注册表

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
            return new Status(1411.5, '', 'Application ['.CACAI.'] has no access to read data from the table ['.$table.']', true);
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
            return new Status(1411.6, '', 'Application ['.CACAI.'] has no access to write data to the table ['.$table.']', true);
        }
        self::$unwritableTable = $table;
        return false;
        
    }
}