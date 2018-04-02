<?php
// 核心控制模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\CTRLR;

// 引入相关命名空间，以简化书写
use PDO;


/**
 * @class Tangram\CTRLR\RDBQuerier
 * NI RDBRow Level Querier
 * NI关系数据库行级数据查询器，主要
 * 用来对数据库中的行级单元进行增，删，改，查，其中
 * **  静态方法只用来快速查询
 * **  动态方法才能实现完整的增，删，改，查
 * **  ***  动态方法插入功能使用PDO模板技术，安全低耗
 * 
 * @author      Jangts
 * @version     5.0.0
**/
class RDBQuerier {
    use rdb_traits\common;
    use rdb_traits\staticmethods;

    const
    SQL_LEFT = "SELECT %s FROM `%s` WHERE %s",
    SQL_RIGHT = " UNION ALL SELECT %s FROM `%s` WHERE %s",
    SQL_SINGLE = "SELECT %s FROM %s WHERE %s ORDER BY %s",
    SQL_ORDER = " ORDER BY %s",
    SQL_LIMIT = " LIMIT %d, %d",
    SQL_DELETE = "DELETE FROM %s WHERE %s";

    protected static
    $id = NULL,
    $conn = NULL,
    $nullsymbols = [
        '='         =>  'IS',
        'IS'        =>  'IS',
        '<>'        =>  'IS NOT',
        'NOT'       =>  'IS NOT',
        'IS NOT'    =>  'IS NOT'
    ];

    public static $qs = [];

    protected
    $pdox = NULL,
	$tables = [],
    $requires = [],
    $order_fields = [],
    $orders = [],
    $select = NULL,
    $start = 0,
	$length = 0,
    $insertPrepare = [
        'queryString'   =>  NULL,
        'PDOStatement'  =>  NULL
    ];

    /**
	 * @var string $lastQueryString 上次查询的SQL语句
    **/
    public
    $errorMessage = 'unknow error',
    $lastQueryString = '';

    /**
     * SQL格式的查询条件生成方法
     * 
     * @final
	 * @access private
     * @return string
    **/
    private function condition(){
        $str = 1;
        $count = 0;
        foreach($this->requires as $require){
            if($count==0){
                $str = $require;
            }else{
                $str .= " AND ".$require;
            }
            $count++;
        }
        return $str;
    }

    /**
     * SQL格式的更新序列生成方法
     * 
     * @final
	 * @access private
     * @param array $data 需要更新的字段与值
     * @return string
    **/
    private function updateString(array $data){
		$arr = [];
		foreach($data as $key=>$val){
            if(is_string($val)){
                $arr[] = "`".self::escape($key)."`"." = '".self::escape($val)."'";
            }elseif(is_numeric($val)){
                $arr[] = "`".self::escape($key)."`"." = ".(string)$val;
            }elseif(is_bool($val)){
                $arr[] = "`".self::escape($key)."`"." = ".($val ? '1' : '0');
            }else{
                $arr[] = "`".self::escape($key)."`"." = ''";
            }

		}
		return join(", ", $arr);
	}
    
    /**
     * SQL命令生成方法
     * 
     * @final
	 * @access private
     * @return string
    **/
    private function getQuerySelectString(){
		$this->tables==NULL && die("No Database Table");
		$require = $this->condition();
        if(count($this->orders)>0){
            $order = join(',', $this->orders);
        }else{
            $order = '1 ASC';
        }
        $this->start = $this->start< 0 ? 0 : $this->start;
        $this->length = $this->length< 0 ? 0 : $this->length;
        if(($sql = self::staticGetQuerySelectString($this->tables, $require, $order, $this->length, $this->start, $this->select))){
            $this->errorMessage = '';
            return $sql;
        }
        if(self::$unreadableTable){
            $this->errorMessage = 'current actived application ['.CACAI.'] cannot read data from table ['.self::$unreadableTable.'].';
        }else{
            $this->errorMessage = 'there some errors with your given tablenames';
        }
		return false;
	}

    /**
	 * 查询器构造函数
	 * 
     * @final
	 * @access public
     * @param int|array $options 预设链接代号或自定义配置表
	 * @return 构造函数无返回值
	**/ 
    public function __construct($options = 0){
        if($queryx = self::conn($options)){
            $this->pdox = $queryx;
        }else{
            // 链接失败，数据库不存在或者权限不足
            $sp =  new StatusProcessor(1416.3, 'Database Connect Error', 'Please check your arguments for Tangram\RDBQuerier');
            return $sp->respond(StatusProcessor::LOG);
        }
    }

    /**
	 * 指定表
	 * 
     * @final
	 * @access public
     * @param string|array  $tablename1     表名1，或多表数组
     * @param string                        表名2
     * @param string                        表名3
     * ...
	 * @return object(Tangram\CTRLR\RDBQuerier)
	**/ 
    public function using($tablename1){
        // 参数可以是一个数组包含所有的表名，也可以是多个字符串参数传递多个表名
        // 两种传参方式不能兼容，以数组形式优先
        if(is_array($tablename1)){
            $this->tables = $tablename1();
        }else{
            $this->tables = func_get_args();
        }

        // 重新选择表时会重置查询条件和排序规则
        $this->requires = [];
        $this->order_fields = [];
        $this->orders = [];
        return $this;
    }

    /**
	 * 切换表
     * 切换表不会重置设置，但一次只允许使用一张表
	 * 
     * @final
	 * @access public
     * @param string|array  $tablename     表名
     * ...
	 * @return object(Tangram\CTRLR\RDBQuerier)
	**/ 
    public function switchTable($tablename){
        if(is_string($tablename)){
            $this->tables = [$tablename];
        }
        return $this;
    }

    /**
     * 重置查询条件
     * 
     * @final
	 * @access public
     * @param string|array|null     $require    查询条件，可以是手写的SQL条件，也可是键值对数组，为NULL则只清空之前的所有条件
     * @return object(Tangram\CTRLR\RDBQuerier)
    **/
    public function requires($require = NULL){
        $this->requires = [];
        if(is_string($require)){
            $this->requires[] = $require;
        }
        elseif(is_array($require)){
            foreach ($require as $key => $value) {
                $this->requires[] = "`$key`" . "=" . "'$value'";
            }
        }
        return $this;
    }

    /**
     * 新增一个查询条件
     * 
     * @final
	 * @access public
     * @param string                    $field  键名
     * @param string|int|null|array     $value  比对值
     * @param string                    $symbol 比对符
     * @return object(Tangram\CTRLR\RDBQuerier)
    **/
    public function where($field, $value = false, $symbol = '='){
        if(is_string($field)&&preg_match('/\w+/', $field)){
            $field = '`' . $field . '`';
        }
        else{
            return $this;
        }
        if($value!==false){
            if(is_string($value)){
                if(is_numeric($value)){
                    if(!in_array($symbol, ['=', 'LIKE', '>', '<', '<>'])){
                        $symbol = '=';
                    }
                }else{
                    $symbol = strtoupper($symbol);
                    if(!in_array($symbol, ['=', '<>', 'IN', 'LIKE'])){
                        $symbol = '=';
                    }
                    if($symbol!=='IN'){
                        $value = "'" . $this->escape($value) . "'";
                    }
                }
                $this->requires[] = $field . ' ' . $symbol . ' ' . $value;
            }
            elseif(is_numeric($value)){
                if(!in_array($symbol, ['=', '>', '<', '<>'])){
                    $symbol = '=';
                }
                $this->requires[] = $field . ' ' . $symbol . ' ' . $value;
            }
            elseif(is_null($value)){
                if(in_array($symbol, ['=', '<>', 'IS', 'NOT', 'IS NOT'])){
                    $symbol = self::$nullsymbols[$symbol];
                }else{
                    $symbol = 'IS';
                }
                $this->requires[] = $field . ' ' . $symbol . ' \'\'';
            }
            elseif(is_bool($value)){
                if(!in_array($symbol, ['=', '<>'])){
                    $symbol = '=';
                }
                $this->requires[] = $field . ' ' . $symbol . ' ' . intval($value);
            }
            elseif(is_array($value)){
                $this->requires[] = $field . " IN ('" . join("','", $value) . "')";
            }
        }
        return $this;
    }

    /**
     * 重置查询条件
     * 多个字段时，先进优先
     * 
     * @final
	 * @access public
     * @param string    $field      用做排序参考的字段
     * @param bool      $reverse    是否倒序
     * @return object(Tangram\CTRLR\RDBQuerier)
    **/
    public function orderby($field, $reverse = false){
        if(is_string($field)||is_numeric($field)){
            // 排除已有的字段
            if($field&&(!in_array($field, $this->order_fields))){
                $this->order_fields[] = $field;
                if($reverse){
                    $this->orders[] = $field . ' DESC';
                }else{
                    $this->orders[] = $field . ' ASC';
                }
            }
        }else{
            // 如果参数类型错误，则重置全部排序规则
            $this->order_fields = [];
            $this->orders = [];
        }
        // var_dump($this->orders);
        return $this;
    }

    /**
     * 设置选取的行数与起始行
     * 
     * @final
	 * @access public
     * @param int   $length 选取行数
     * @param int   $start  选取起始行，默认为0，即从第一行开始
     * @return object(Tangram\CTRLR\RDBQuerier)
    **/
    public function take($length, $start = 0){
        $this->start = intval($start);
        $this->length = intval($length);
        return $this;
    }

    /**
     * 设置选取的行数与起始行
     * 
     * @final
	 * @access public
     * @param string|array          $select         筛选字段，默认为所有字段，多个字符串可使用数组或者含有间隔符','的字符串
     * @param int|array             $range          选取片段，默认为选取全部，完整参数应该为含有起始位置与长度的数组，其实位置为0时，可以只传入长度值
     * @param bool                  $useescape      是否需要添加反引号'`'
     * @return object(Tangram\MODEL\RDBRowsCollection)|bool
    **/
    public function select($select = '*', $range = false, $useescape = true){
        if(is_numeric($select)||($select === '*')){
            $this->select = $select;
        }else{
            if(is_string($select)){
                if($useescape){
                    // 将字串中的多个字段分割为数组的不同元素
                    // 如果本身含有反引号'`'的，引号将被去掉
                    $array = preg_split('/`*\s*,\s*`*/', str_replace('`', '', $select));
                    // 将数组还原为字串，并重新添加反引号'`'
                    $this->select = preg_replace('/\s+AS\s+/i', '` AS `', '`' . join('`, `', $array) . '`');
                }else{
                    $this->select = $select;
                }
            }elseif(is_array($select)){
                $this->select = preg_replace('/\s+AS\s+/i', '` AS `', '`' . join('`, `', $select) . '`');
            }else{
                $this->select = '*';
            }
        }
        if($range){
            if(is_numeric($range)){
                $this->start = 0;
                $this->length = $range;
            }elseif(is_array($range)){
                $this->start = intval($range[0]);
                $this->length = intval($range[1]);
            }
        }
        $sql = $this->getQuerySelectString();
        self::$qs[] = $this->lastQueryString = $sql;
        return self::query($sql, $this->pdox);
    }
    
    /**
     * 选取符合条件的第一行记录
     * 
     * @final
	 * @access public
     * @return object(Tangram\MODEL\RDBRowsCollection)
    **/
    public function first(){
        $this->length = 1;
        $sql = $this->getQuerySelectString();
        return self::query($sql, $this->pdox);
    }
    
    /**
     * 查询符合条件的去重记录
     * 
     * @final
	 * @access public
     * @param string    $anykey         去重字段，多个字段请直接按照SQL语法书写
     * @return object(Tangram\MODEL\RDBRowsCollection)
    **/
    public function distinct($select){
        $this->select = 'DISTINCT `'.$select.'`';
        $sql = $this->getQuerySelectString();
        return self::query($sql, $this->pdox);
    }

    /**
     * 查询符合条件的记录数
     * 
     * @final
	 * @access public
     * @param string    $anykey         筛选字段，默认为所有字段，该参数不对结果产生影响，但是如果指定一个确切存在的字段名，可以减少资源浪费，增加查询速度
     * @return int
    **/
    public function count($anykey = '*'){
        $this->select = 'COUNT('.$anykey.')';

        $this->start = 0;
        $this->length = 0;
        $sql = $this->getQuerySelectString();
        self::$qs[] = $this->lastQueryString = $sql;
        if($result = $this->pdox->query($sql)){
            return intval($result->fetchColumn());
        }
        return 0;
    }

    /**
     * 插入单行数据
     * 由于使用了DPO的prepare特性，连续向同一表插入数据，不会重复创建PDOStatement实例，可以增加响应速度
     * 
     * @final
	 * @access public
     * @param array     $insert         要插入的数据
     * @param bool      $ignore         是否避免重复插入
     * @return bool
    **/
    public function insert(array $insert, $ignore = false){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])){
            $this->errorMessage = '';
            $keys = "(`".join("`, `", array_keys($insert))."`)";;
            $vals = "(:".join(", :", array_keys($insert)).")";
            if($ignore){
    			$sql = "INSERT IGNORE";
    		}else{
    			$sql = "INSERT";
    		}
            $sql .= " INTO `".self::escape($this->tables[0])."` $keys VALUES $vals;";
            if($this->insertPrepare['queryString']!=$sql){
                $this->insertPrepare['queryString'] = $sql;
                self::$qs[] = $this->lastQueryString = $sql;
                $this->insertPrepare['PDOStatement'] = $this->pdox->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            }
            if($this->insertPrepare['PDOStatement']){
                $array = [];
                foreach($insert as $key=>$val){
                    $array[':'.$key] = stripslashes($val);
                }
                $this->insertPrepare['PDOStatement']->execute($array);
                return true;
            }
            $this->errorMessage = 'unknow error';
        }
        elseif(self::$unwritableTable){
            $this->errorMessage = 'current actived application ['.CACAI.'] cannot write data to table ['.self::$unwritableTable.'].';
        }
		return false;
    }

    /**
     * 插入多行数据
     * 
     * @final
	 * @access public
     * @param array     $inserts        要插入的数据
     * @param bool      $ignore         是否避免重复插入
     * @return bool
    **/
    public function inserts(array $inserts, $ignore = false){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])){
            $this->errorMessage = '';
            $keys = "(`".join("`, `", array_keys($inserts[0]))."`)";;
            $vals = "(:".join(", :", array_keys($inserts[0])).")";
            if($ignore){
    			$sql = "INSERT IGNORE";
    		}else{
    			$sql = "INSERT";
    		}
            $sql .= " INTO `".self::escape($this->tables[0])."` $keys VALUES $vals;";
            if($this->insertPrepare['queryString']!=$sql){
                $this->insertPrepare['queryString'] = $sql;
                self::$qs[] = $this->lastQueryString = $sql;
                $this->insertPrepare['PDOStatement'] = $this->pdox->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            }
            if($this->insertPrepare['PDOStatement']){
                foreach($inserts as $insert){
                    $array = [];
                    foreach($insert as $key=>$val){
                        $array[':'.$key] = $val;
                    }
                    $this->insertPrepare['PDOStatement']->execute($array);
        		}
                return true;
            }
            $this->errorMessage = 'unknow error';
        }
        elseif(self::$unwritableTable){
            $this->errorMessage = 'current actived application ['.CACAI.'] cannot write data to table ['.self::$unwritableTable.'].';
        }
		return false;
    }

    /**
     * 返回插入操作最后插入数据的主键值
     * 主键自增的数据表中使用
     * 
     * @final
	 * @access public
     * @param string|null $name 主键字段名，可为空
     * @return int|string 主键值
    **/
    public function lastInsertId($name = NULL){
        if($this->insertPrepare['PDOStatement']!=NULL){
            return $this->pdox->lastInsertId($name);
        }
        return 0;
    }

    /**
     * 更新符合查询条件的记录
     * 此执行方法仅对数据表序列中的第一个表有效
     * 
     * @final
	 * @access public
     * @param array $data    需要更新的字段与值
     * @return int|bool 操作成功则返回影响行数，否则返回false，返回0行也属于操作成功
    **/
    public function update(array $data){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])){
            if(is_array($data)&&!empty($data)){
                $this->errorMessage = '';
                $sql = "UPDATE `%s` SET %s WHERE %s";
                $data = $this->updateString($data);
                $sql = sprintf($sql, self::escape($this->tables[0]), $data, self::escape($this->condition()));
               self::$qs[] = $this->lastQueryString = $sql;
                $num = $this->pdox->exec($sql);
                if(is_numeric($num)){
                    return $num;
                }
                $this->errorMessage = 'unknow error';
            }else{
                $this->errorMessage = 'empty update date';
            }
        }
        elseif(self::$unwritableTable){
            $this->errorMessage = 'current actived application ['.CACAI.'] cannot write data to table ['.self::$unwritableTable.'].';
        }
		return false;
	}

	/**
     * 删除符合查询条件的记录
     * 此执行方法仅对数据表序列中的第一个表有效
     * 
     * @final
	 * @access public
     * @return int|bool 操作成功则返回影响行数，否则返回false，返回0行也属于操作成功
    **/
    public function delete(){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])){
            $sql = sprintf(self::SQL_DELETE, $this->tables[0], $this->condition());
            self::$qs[] = $this->lastQueryString = $sql;
            $num = $this->pdox->exec($sql);
            if(is_numeric($num)){
                return $num;
            }
            $this->errorMessage = 'unknow error';
        }
        elseif(self::$unwritableTable){
            $this->errorMessage = 'current actived application ['.CACAI.'] cannot delete data to table ['.self::$unwritableTable.'].';
        }
        return false;
    }
}