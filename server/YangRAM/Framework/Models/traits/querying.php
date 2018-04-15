<?php
namespace AF\Models\traits;

use PDO;
use Status;
use DBQ;
use Tangram\CTRLR\RDBQuerierPlus;

trait querying {
    protected static $tablename,
    /**
     * 值的约束规则
     * 
     * [a, b, c]    表示值仅能为a, b, c中的一个
     * ''           表示值必须是字符串（数将被转为字符串），可为空
     * 'a'          表示值必须是字符串（数将被转为字符串），不可为空
     * 0            表示值必须是数字（数字字符串也可以），可为空
     * 1            表示值必须是数字（数字字符串也可以），不可为空
     */
    $constraints = [];

    final public static function __checkValue($name, $value) : bool {
        if(isset(static::$constraints[$name])){
            switch(static::$constraints[$name]){
                case '':
                // 可空字符串
                if($value===NULL||$value===''){
                    return true;
                }
                case 'a':
                // 非空字符串
                if($value&&is_string($value)){
                    return true;
                }
                return false;

                case 'd':
                // 非空DATE字符串
                if($value&&is_string($value)&&$date = strtotime($value)){
                    return date('Y-m-d', $date);
                }
                return false;

                case 't':
                // 非空DATETIME字符串
                if($value&&is_string($value)&&$time = strtotime($value)){
                    return date('Y-m-d H:i:s', $time);
                }
                return false;

                case 0:
                // 可空数或数字
                if($value===NULL){
                    return true;
                }
                case 1:
                // 非空数或数字
                if(is_numeric($value)){
                    return true;
                }
                return false;
            }
            if(is_array(static::$constraints[$name])){
                if(in_array($value, static::$constraints[$name])){
                    return true;
                }
                return false;
            }
        }
        return true;
    }

    final protected static function throwValueError($name, $value){
        switch(static::$constraints[$name]){
            case '':
            $type = 'a string';
            break;

            case 'a':
            $type = 'a string not null';
            break;

            case 0:
            $type = 'a numeric';
            break;

            case 1:
            $type = 'a numeric not null';
            break;

            default:
            $type = 'in ["'.join('","', static::$constraints[$name]).'"]';
        }
        new Status(1415, '', 'Property ["'.$name.'"] of calss '.get_called_class().' must be '.$type.', "'.$value.'" given.',true);
    }

    public static function __checkValues(array $values, bool $isPost = false) : array {
        foreach($values as $name=>$value){
            if(!self::__checkValue($name, $value)){
                if(isset(static::$defaultPorpertyValues[$name])&&self::__checkValue($name, static::$defaultPorpertyValues[$name])){
                    $values[$name] = static::$defaultPorpertyValues[$name];
                }else{
                    if($isPost){
                        self::throwValueError($name, $value);
                    }
                    unset($values[$name]);
                }
            }
        }
        return $values;
    }

    public static function __checkOrderFields(array $orderby) : bool {
		foreach ($orderby as $fieldExpression) {
			if(!array_key_exists($fieldExpression[0], static::$defaultPorpertyValues)){
				return false;
			}
		}
		return true;
    }

    /**
	 * 获取默认PDOX数据连接
     * 不调用则不会生成
	 * 
	 * @access protected
	 * @static
	 * @return object
	**/
    protected static function initQuerier() : DBQ {
        $class = strtolower(get_called_class());
        if(empty($privateRDBQueries[$class])){
            if(self::$rdbConnectionType){
                $privateRDBQueries[$class] = new RDBQuerierPlus(static::$rdbConnectionIndex);
            }else{
                $privateRDBQueries[$class] = new DBQ(static::$rdbConnectionIndex);
            }
            if(empty(static::$tablenameAlias)){
                $classname = explode("\\", $class);
                static::$tablenameAlias = str_replace('model', '', end($classname));
            }
        }
        $tablename = static::$tablenamePrefix . static::$tablenameAlias;
        return $privateRDBQueries[$class]->using($tablename);
    }

    /**
	 * 查询数据，并返回整理后的结果
	 * 
	 * @access public
	 * @static
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
     * @param array $orderby                            排序规则，一个或多个条件数组形如[fieldname, isreverse, sortby]的数组构成
     * @param array|int $range                          截取片段，由数组或整型构成，数组为[length, start]，整型为长度，相当于[length, 0]
     * @param int $returnFormat                         返回列表类型，请使用模型自带的常量来标记
	 * @return array
	**/
    public static function query($require = "1 = 1", array $orderby = [['1', false, self::SORT_REGULAR]], $range = 0, $returnFormat = self::LIST_AS_OBJS, $selecte = '*') : array{
        // 获取默认数据行查询器
        $querier = static::initQuerier();

        // 准备一个空数组，用来存放查询结果
        $objs = [];
        // 查询数据库
        $result = self::executeQuerySelect($querier, $require, $orderby, $range, $selecte);
        if($result){
            if($returnFormat===self::LIST_AS_ARRS){
                return $result->getArrayCopy();
            }
            $pdos = $result->getIterator();
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static;
                $obj->__put($modelProperties, true);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    /**
	 * 单字段值快速查询方法
     * 返回查询结果的指定行
	 * 
	 * @access public
	 * @static
     * @param string            $key    字段名
     * @param string|int        $key    要来比对的值
     * @param int|string|bool   $index  指定行，正数为顺序行序号，负数为倒序行序号，true为正序全部行，false为倒序全部行，其他值为正序全部行
     * @param string            $ok     排序字段
	 * @return array
	**/
    public static function find ($key, $val, $index = false, $ok = '1') : array {
        // 数或数字字串
        if(is_numeric($index)){
            // 自然数
            if($index>=0){
                return self::query([$key=>$val], [[$ok, false, self::SORT_REGULAR]], [1, intval($index)]);
            }
            // 负数，用-1剪去该数以获得倒序序数
            // 如$index = -1，则-1-$index = 0，即倒序的第一行即为所查询的行
            // 又如$index = -10，则-1-$index = 9，即倒序的第十行即为所查询的行
            return self::query([$key=>$val], [[$ok, true, self::SORT_REGULAR]], [1, -1 - intval($index)]);
        }
        // 布尔值
        if(is_bool($index)){
            // 取$index的反值来指定是否倒序
            return self::query([$key=>$val], [[$ok, !$index, self::SORT_REGULAR]], 0);
        }
        // 相当于$index=true
        return self::query([$key=>$val], [[$ok, false, self::SORT_REGULAR]], 0);
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
    public static function setQuerySelectRange(DBQ $querier, $range){
        if(is_numeric($range)){
			$querier->take($range);
		}elseif(is_array($range)){
			$querier->take($range[1], $range[0]);
		}else{
			$querier->take(0);
        }
        return $querier;
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
    public static function setQuerySelectOrder(DBQ $querier, $order) : DBQ {
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
	 * 通过查询器查询数据库
	 * 
	 * @access protected
	 * @static
     * @param object(Tangram\CTRLR\RDBQuerier) $querier 查询器实例
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
     * @param array $orderby                            排序规则，一个或多个条件数组形如[fieldname, isreverse, sortby]的数组构成
     * @param array|int $range                          截取范围，由数组或整型构成，数组为[length, start]，整型为长度，相当于[length, 0]
     * @param string|array $selecte                     选择字段，可以为整理好的SQL语句片段，也可以是由字段名构成的数组，数组名可以包括名别语法
	 * @return object(Tangram\MODEL\RDBRowsCollection)|bool
	**/
    protected static function executeQuerySelect(DBQ $querier, $require, $orderby, $range, $selecte = '*'){
        // 整理查询条件
		if(is_numeric($require)){
            $range = $require;
            $require = "1";
        }elseif(is_string($require)||is_array($require)){
            $require = $require;
        }else{
            $require = "1";
        }

        // 整理并录入截取范围到查询器
        static::setQuerySelectRange($querier->requires($require), $range);
        
        // 依次整理并录入排序规则到查询器
        $querier->orderby(false);
        foreach ($orderby as $order) {
            static::setQuerySelectOrder($querier, $order);
        }

        // 返回查询结果
        return $querier->select($selecte);
    }

    /**  
	 * 提交插入
	 * 
	 * @access public
	 * @return object|bool
	**/ 
    protected function __insert(){
        // 获取数据库链接
        $querier = $this->querier;
        
        // 直接将数据插入到数据表
        $data = $this->__checkInsertData($this->modelProperties);
        // 如果为新增记录
        if(static::$AIKEY) {
            // 剔除掉自增见
            unset($data[static::$AIKEY]);
        }
        if(!$data||!$querier->insert($data)){
            return false;
        }
        $this->modelProperties = $data;
        if(isset($this->modelProperties[$this->pk])){
            // 获取新数据的主键ID
            $this->__guid = $this->modelProperties[$this->pk];
        }elseif($this->pk===static::$AIKEY){
            // 获取新数据的自增ID
            // R3模型对应的数据表的自增键必须是主键
            $this->__guid = $querier->lastInsertId(static::$AIKEY);
        }else{
            // 没有主键，报错
            // R3模型的ID即为数据表主键值，没有主键的表不能对应R3模型
            new Status(1422);
        }
        // 获取完整的新纪录，以补全缺省值
        $result = $querier->requires()->where($this->pk, $this->__guid)->select();
        $modelProperties = $result->item();
        // 标记为已存
        $this->savedProperties = $this->modelProperties = $modelProperties;
        return $this;
    }

    /**  
	 * 提交更新
	 * 
	 * @access protected
	 * @return object|bool
	**/ 
    protected function __update(){
        // 获取数据库链接
        $querier = $this->querier;

        // 如果为只读，或找不到ID
        if($this->readonly||empty($this->__guid)){
            return false;
        }
        // 比对更改
        $diff = self::array_diff($this->savedProperties, $this->modelProperties, self::DIFF_SIMPLE);
        $update = $diff['__M__'];

        if(count($update)==0){
            // 如果并无更新，则返回实例，意即成功
            return $this;
        }

        // 删除主键
        if(isset($update[$this->pk])){
            unset($update[$this->pk]);
        }
        // 将更新数据提交到数据库
        $update = $this->__checkUpdateData($update, $this->savedProperties);
        if($update&&$querier->requires()->where($this->pk, $this->__guid)->update($update)!==false){
            foreach ($update as $key => $val) {
                $this->savedProperties[$key] = $val;
            }
        }else{
            // 如果失败，返回false
            return false;
        }

        if($this->files&&$this->savedProperties){
            // 如果支持缓存，则缓存一个
            $this->files->store($this->__guid);
        }
        return $this;
    }
}