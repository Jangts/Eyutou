<?php
namespace AF\Models;

use Status;
use Storage;
use Tangram\MODEL\ObjectModel;

/**
 * @class AF\Models\FileBasedModel
 * File Based Model
 * 基于单一文件的数据模型
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
class FileBasedModel implements \DataModel {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    protected static function rebuildFileContent($content, $properties){
        return $content;
    }

    protected static function rewriteFileContent($filename, $content, $properties){
        return $content;
    }

    protected
    $filename = '',
    $content = '',
    $modelProperties = [];

    /**  
	 * 取值方法
	 * 
	 * @access public
     * @final
     * @param string $name 属性名称
	 * @return mixed
	**/ 
    final public function get($name){
        if($name==='filename'){
            return $this->content;
        }elseif($name==='content'){
            return $this->str();
        }elseif(isset($this->modelProperties[$name])){
            return $this->modelProperties[$name];
        }
        return NULL;
    }

    /**  
	 * 写值方法
     * 只读模式下不可用
     * 不可新增值
	 * 
	 * @access public
     * @final
     * @param string $name 属性名称
     * @param mixed $value 要写入的值
	 * @return mixed
	**/ 
    final public function set($name, $value){
        if($name==='content'){

        }elseif(isset($this->modelProperties[$name])){
            $this->modelProperties[$name] = $value;
        }
        return false;
    }

    /**  
	 * 删除属性
	 * 
	 * @access public
     * @param string $property 属性名称
	 * @return object
	**/ 
    public function uns($property){
        return $this;
    }

    /**  
	 * 将属性数组转化为字符串
	 * 
	 * @access public
	 * @return string
	**/
    final public function str(){
        # 重新整理$this->content
        $this->content = static::rebuildFileContent($this->content, $this->modelProperties);
        return $this->content;
    }

    /**  
	 * 查键方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return bool
	**/ 
    final public function has($name){
        if($name==='filename'){
            return true;
        }elseif($name==='content'){
            return true;
        }elseif(array_key_exists($name, $this->modelProperties)){
            return true;
        }
        return false;
    }
}