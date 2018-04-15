<?php
namespace AF\Models;

use Tangram\MODEL\ObjectModel;
use Storage;

/**
 * @class AF\Models\BaseModel
 * Relational Data Model
 * 关系数据模型，简称模型
 * 一般数据封装包的拓展，相比于一般数据封装包数据结构的灵活
 * 模型数据结构固定，一个
 * 模型类的所有实例拥有着相同的结构，因而能
 * 提供一些专门争对某类数据操作的接口
 * 本模型是用来拓展其他模型类的基类，属于抽象类，并不能直接使用
 * 直接继承自一般数据封装包，且固定的数据结构的类，也可视其为模型
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseModel extends ObjectModel {
    use traits\caching;
    use traits\counting;

    const
	S = 1,                              // 时长常量，单位为妙（Second）
    M = 60,                             // 时长常量，单位为妙，60秒为1分（Minute）
	H = 3600,                           // 时长常量，单位为妙，3600秒为1小时（Hour）
	D = 86400,                          // 时长常量，单位为妙，86400秒为1天（Day）
	W = 604800,                         // 时长常量，单位为妙，604800秒为1周（Week）

    // 统计方式必须在数据设计之处便指定，且中途不宜修改，以确保统计数据之有效性
    RECORD_BY_DEV = 0,                  // 以用户设计的方式统计，意即不使用模型统计
    RECORD_TO_RDB = 1,                  // 将访问数据记录到数据库的专门表
    RECORD_TO_MRC = 2,                  // 将访问数据记录到文件缓存专区
    RECORD_TO_MEM = 3;                  // 将访问数据记录到内存缓存专区
    
    private static
    // 一般存储仓，缓存在文件系统或内存数据库中，进程结束后不销毁，今后仍可直接读取
    $privateFileStorages = [],
    // 类自带的静态缓存，缓存在定义类的内存段中，读取速度比一般存储仓快，但进程结束时会销毁，同一分数据会在单个进程里多次调用的话，使用此缓存比较合适
    $privateMemorizeStorages = [],
    // 缓存超时时间，以类名为索引，使不同子类可以有不同超时时间
    $privateFilestoreLifetimes = [];

    protected static
    $staticFileStorage,
    // 缓存路径，模型数据使用文件系统的缓存仓，以确保数据隔离
    // 如需使用非文件系统的缓存仓，请在子类中关闭默认缓存，然后自建存储仓
    // 当赋值为字符串时，则直接作为缓存地址；为布尔值true时，则使用系统为模型专辟的空间；为其他值时表示不使用存储仓
    $fileStoragePath = true,
    // 缓存编码格式
    $fileStorageEncodeMode = Storage::JSN,
    // 缓存有效期，0为长期，默认7200秒，即2小时
    $fileStoreLifetime = 7200,
    $recordType = self::RECORD_BY_DEV,
    // 默认值，新建数据时，可能会与此数组进行比对
    $defaultPorpertyValues  = [];
    
    /**
	 * 获取该模型的全部实例
	 * 
	 * @access protected
     * @final
	 * @static
	 * @return array
	**/
    public static function getALL() : array {
        return [];
    }

    /**
	 * 通过主键获取实例
	 * 
	 * @access protected
	 * @static
     * @param string|int $guid 主标识值，又名主键索引值，也可简称索引值
	 * @return mixed
	**/
    public static function byGUID($guid){
        return new self($guid);
    }

    public static function create (array $options = []){
        return false;
    }

    protected
    // ID，模型必须有一个ID，可以是某个属性，也可以是任何自定义组合
    $__guid,
    // 是否只读
    $readonly = false,
    // 数据渲染模板
    $template = '',
    // 属性数组
    // 与Tangram\MODEL\ObjectModel不同的是，它必须是索引数组，
    // 且数组的索引键格式有限制，这是模型又叫关系数据模型原因
    $modelProperties = [],
    // 记录数组（即普通模型的属性数组）备份
    $savedProperties;

    /**  
	 * 模型实例构造函数
	 * 
	 * @access protected
     * @param string|int $guid 主标识值，又名主键索引值，也可简称索引值
	 * @return 构造函数无返回值
	**/ 
    protected function __construct($guid){
        $this->__guid = $guid;
    }
    /**  
	 * 创建属性数组
	 * 
	 * @access protected
     * @param array|object $modelProperties 源数据
	 * @return object
	**/ 
    protected function __put(array $input, bool $initialize = true){
        $this->xml = NULL;
        if(is_array($input)||is_object($input)){
            foreach ($input as $key => $value) {
                if(array_key_exists($key, static::$defaultPorpertyValues )){
                    $this->modelProperties[$key] = $value;
                }
            }
        }
        if($initialize){
            $this->savedProperties = $this->modelProperties;
        }
        return $this;
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
            return $this->__put($input, false);
        }
        \Status::cast('cannot put data to a readonly model object.', 1413);
    }

    /**  
	 * 缓存实例数据
	 * 
	 * @access public
	 * @return object
	**/ 
    final public function cac(){
        if(is_a(static::$staticFileStorage, '\Tangram\CACHE\DataStorage')){
            static::$staticFileStorage->store($this->__guid, $this->modelProperties);
        }
        return $this;
    }

    /**  
	 * 追加属性，写值方法（新增）
     * 基类不可用
	 * 
	 * @access public
     * @param string $property 属性名称
     * @param mixed $value 要写入的值
	 * @return object
	**/
    final public function add($property, $value){
        return $this;
    }

    /**  
	 * 删除属性
     * 基类不可用
	 * 
	 * @access public
     * @param string $property 属性名称
	 * @return object
	**/ 
    final public function uns($property){
        return $this;
    }

    /**  
	 * 将属性数组转化为XML格式的文本
	 * 
	 * @access public
     * @param string $version xml版本
     * @param string $encoding 编码格式，默认为utf8
	 * @return string
	**/ 
    public function xml_encode($root = 'data', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml){
            return $this->xml->outputMemory(true);
        }
        return self::getXmlbyArray($this->modelProperties, $root, $version, $encoding);
    }

    public function countit($sync = false){
        self::recordByGUID($this->__guid);
        return self::getRecordsCount($this->__guid);
    }

    /**  
	 * 渲染属性数组
	 * 
	 * @access public
	 * @return string
	**/ 
    public function render(){
        if(is_file($this->template)){
            if(is_array($this->modelProperties)){
                extract($this->modelProperties, EXTR_PREFIX_SAME, 'CSTM');
            }
            include_once $template;
            return true;
        }
        return false;
    }
}