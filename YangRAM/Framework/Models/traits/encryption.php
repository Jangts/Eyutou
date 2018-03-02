<?php
namespace AF\Models\traits;

use PDO;
use DBQ;
use Tangram\CTRLR\RDBQuerierPlus;

trait encryption {
    // 加密字段，会在提交到数据库前进行加密
    protected static $encryptedFields = [];

    protected static function encrypt($value){
        return md5(hash('sha256', $value));
    }

    /**  
	 * 取值方法
	 * 
	 * @access public
     * @param string $name 属性名称
	 * @return mixed
	**/ 
    public function get($name){
        if(is_array($this->modelProperties)){
            if(isset($this->modelProperties[$name])&&!in_array($name, static::$encryptedFields)){
                return $this->modelProperties[$name];
            }
        }
        return NULL;
    }

    /**  
	 * 写值方法
     * 只读模式下不可用
     * 不可新增值
	 * 
	 * @access public
     * @param string $name 属性名称
     * @param mixed $value 要写入的值
	 * @return object
	**/ 
    public function set($name, $value){
        if(is_array($this->modelProperties)){
            if(array_key_exists($name, $this->modelProperties)){
                if(in_array($name, static::$encryptedFields)){
                    $value = static::encrypt($value);
                }
                $this->modelProperties[$name] = $value;
                $this->xml = '';
            }
            return $this;
        }
        \Status::cast('modelProperties of model object must be a array.', 1461);
    }

    public function setAttribute($name, $value){
        return $this;
    }

    public function removeAttribute($name, $value){
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
        foreach(static::$uniqueIndexes as $key){
            unset($input[$key]);
        }
        foreach(static::$encryptedFields as $field){
            if(isset($input[$field])){
                $input[$field] = static::encrypt($input[$field]);
            }
        }
        // 如果已存在记录，则与存在的记录对比，否则与默认值数组对比
        return $this->__put($input);
    }


    /**  
	 * 提取属性数组
	 * 
	 * @access public
	 * @return array
	**/ 
    final public function getArrayCopy(){
        $array = $this->modelProperties;
        foreach(static::$encryptedFields as $key){
            unset($array[$key]);
        }
        return $array;
    }

    public function getIterator() {
        return new \ArrayIterator($this->getArrayCopy());
    }
    
    /**  
	 * 将属性数组转化为JSON格式的文本
	 * 
	 * @access public
     * @param bool $trim 是否压缩
     * @param string $indent 格式化缩进，默认4空格，$trim为否时有效
	 * @return string
	**/ 
    public function json_encode($trim = true, $indent = '    '){
        if($trim){
            return self::jsonEncode($this->getArrayCopy());
        }
        return self::jsonToJson(self::jsonEncode($this->getArrayCopy()), false, $indent);
    }

    /**  
	 * 将属性数组转化为XML格式的文本
	 * 
	 * @access public
     * @param string $version xml版本
     * @param string $encoding 编码格式，默认为utf8
	 * @return string
	**/ 
    public function xml_encode($root = 'member', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml){
            return $this->xml->outputMemory(true);
        }
        return self::getXmlbyArray($this->getArrayCopy(), $root, $version, $encoding);
    }

    /**  
	 * 将属性数组转化为PHP序列化文本
	 * 
	 * @access public
	 * @return string
	**/ 
    public function str(){
        return json_encode($this->getArrayCopy());
    }

    /**  
	 * 将属性数组转化为QueryString
	 * 
	 * @access public
     * @param string $numericPrefix 数字键前缀
     * @param bool $encodeType 是否编码
	 * @return string
	**/ 
    public function toQueryString($numericPrefix = 'arg_', $encodeType = false){
        return self::arrayToQueryString($this->getArrayCopy(), $numericPrefix = 'arg_', $encodeType = false);
    }
}