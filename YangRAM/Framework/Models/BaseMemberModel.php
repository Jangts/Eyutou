<?php
namespace AF\Models;

use Status;
use PDO;
use Tangram\CTRLR\RDBQuerierPlus;
use UserModel;

/**
 * @class AF\Models\BaseMemberModel
 * Application Member Information Model
 * 应用会员信息模型
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseMemberModel extends BaseModel {
    use traits\r3like;
    use traits\encryption;

    private static
    // RDBQuerier实例缓存组，以类名为索引，以保证不同BaseR3Model子类有且仅有一个链接
    $privateRDBQueries = [];

    protected static
    // 使用的数据库链接索引
    $rdbConnectionIndex = 0,

    // 数据表前缀(默认为应用数据表前缀)
    $tablename = TP_CURR.'members',

    // 唯一索引键组
    $uniqueIndexes = ['uid'],

    // 缓存位置
    $fileStoragePath = true,

    // 默认值，用于新增记录
    $defaultPorpertyValues  = [
        'uid'    =>  1
    ];

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
            $privateRDBQueries[$class] = new RDBQuerierPlus(static::$rdbConnectionIndex);
            if(empty(static::$tablename)){
                new Status(1422.1, 'Model File Be Tampered', 'Member Model Must have a rdb tablename.', true);
            }
        }
        return $privateRDBQueries[$class]->using(static::$tablename);
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
    public static function register(array $input){
        $obj = new static;
        $obj->__put($input, false);
        return $obj->__insert();
    }


    protected
    // PDOX数据链接实例
    $querier,
    // 主键名
    $pk,
    // 存储仓，可以不使用默认仓
    $files;

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
            $this->modelProperties = $this->savedProperties = $input;
            $this->__guid = $this->savedProperties[$this->pk];
            $this->__fix();
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
        return $this;
    }

    abstract public function checkPinCode($pin);

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
        if(!$data||!$querier->insert($data)){
            return false;
        }
        $this->modelProperties = $data;
        if(isset($this->modelProperties[$this->pk])){
            // 获取新数据的主键ID
            $this->__guid = $this->modelProperties[$this->pk];
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
        return $this->__fix();
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
        if(empty($this->__guid)){
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
        if($update&&$querier->requires()->where($this->pk, $this->__guid)->update($update)){
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
        return $this->__fix();
    }

    protected function __fix() {
        return $this;
    }

    public function getBaseInfo(){
        if($this->savedProperties){
            return [
                'uid'   =>  $this->__guid
            ];
        }
        return [
            'uid'   =>  static::$defaultPorpertyValues[$this->pk]
        ];
    }

    /**  
	 * 销毁会员
	 * 
	 * @access public
	 * @return bool
	**/ 
    public function destroy(){
        if($this->savedProperties&&($this->querier->requires()->where($this->pk, $this->__guid)->delete())){
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
