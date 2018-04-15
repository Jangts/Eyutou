<?php
namespace AF\Models\traits;

use PDO;
use DBQ;
use Tangram\CTRLR\RDBQuerierPlus;

trait crud {
    use r3like;
    use querying;

    /**
	 * 查询全部记录并按指定字段值
	 * 
	 * @access public
	 * @static
     * @param string $selectField 选择字段
     * @param mixed $indexField 索引字段
	 * @return array
	**/
    public static function select($selectField, $indexField = NULL, $require = '1 = 1') : array {
        if($indexField&&!in_array($indexField, static::$uniqueIndexes, true)){
            $indexField = static::$uniqueIndexes[0];
        }
        $values = [];
        $result = static::initQuerier()->requires($require)->take(0)->orderby(false)->select([$indexField, $selectField]);
        if($result){
            $pdos = $result->getIterator();
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                if($indexField){
                    $values[$modelProperties[$indexField]] = $modelProperties[$selectField];
                }else{
                    $values[] = $modelProperties[$selectField];
                }
            }
        }
        return $values;
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
    public static function create(array $options = []){
        return new static;
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
        $obj = new static;
        $obj->__put($input, false);
        return $obj->__insert();
    }

    /**
	 * 更新某行或某些行的数据
     * 成功返回记录实例，失败返回false
	 * 
	 * @access public
     * @final
	 * @static
     * @param object(Tangram\CTRLR\RDBQuerier) $querier 查询器实例
     * @param array|object $input 源数据
	 * @return bool
	**/
    public static function update($require, array $input) : bool {
        // 获取默认数据行查询器
        $querier = static::initQuerier();
        if(is_array($input)&&$querier->requires($require)->update($input)!==false){
            // 因为有未知行数的数据修改了，所以清空模型的全部缓存
            self::cleanFileStorage();
            return true;
        }
        return false;
    }

    /**
	 * 删除一行或多行记录
	 * 
	 * @access public
     * @final
	 * @static
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
	 * @return bool
	**/
    public static function delete($require) : bool {
        // 获取默认数据行查询器
        $querier = static::initQuerier();

        $objs = self::query($require);

        // 开启事件，如果支持的话
        $__key = $querier->beginAndLock();
            
        // 遍历查询到的记录实例，使其自己销毁
        // 使用自身定义的destroy方法，有助于彻底销毁记录和周边
        foreach($objs as $obj){
            if($obj->destroy()){
                continue;
            }
            // 如果其中有一个失败，则回滚操作，并返回false
            $querier->unlock($__key)->rollBack();
            return false;
        }
        // 全部销毁成功，则提交事件
        $querier->unlock($__key)->commit();
        return true;     
    }

    /**  
	 * 更新属性数组
	 * 
	 * @access public
     * @param array|object $input 新数据
	 * @return object
	**/ 
    public function put(array $input){
        if($this->readonly===false){
            // 索引值不允许更新，如需更新索引，请另写方法
            foreach(static::$uniqueIndexes as $key){
                unset($input[$key]);
            }
            // 如果已存在记录，则与存在的记录对比，否则与默认值数组对比
            return $this->__put($input);
        }
        \Status::cast('cannot put data to a readonly model object.', 1413);
    }

    /**  
	 * 销毁记录
	 * 
	 * @access public
	 * @return bool
	**/ 
    public function destroy() : bool {
        if($this->savedProperties&&($this->querier->requires()->where($this->pk, $this->__guid)->delete())){
            if($this->files) $this->files->store($this->__guid);
            return $this->__afterDelete();
        }
        // var_dump($this->querier);
        // exit;
        return false;
    }
}