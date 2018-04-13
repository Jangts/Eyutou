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

    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/){

    }

    protected static function getFilenames($requires = []){

    }

    final protected static function getFileContent($filename){
        if(is_readable($filename)){
            return file_get_contents($filename);
        }
        return false;
    }

    final protected static function putFileContent($filename, $content, $properties){
        $path = dirname($filename);
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(file_put_contents($filename, self::buildFileContent($content, $properties))){
            return true;
        }
        return false;
    }
    
    protected static function buildModelProperties($content){
        return [];
    }

    protected static function buildFileContent($content, $properties){
        return $content;
    }

    

    final public static function query(){
        $contents
    }

    final public static function create($guid = NULL/*单例数据文件不需要GUID*/){
        $filename = buildFilename($guid);
    }

    final public static function delete($guid = NULL/*单例数据文件不需要GUID*/){
        $filename = buildFilename($guid);
        if(is_writable($filename)){
            return unlink($filename);
        }
        return false;
    }

    protected
    $filename = '',
    $content = '',
    $modelProperties = [];

    final protected function __construct($filename, $content, $properties){

    }

    public function put($content){
        switch (gettype($content)) {
            case 'array':
            
                return static::buildFileContent($content, $this->modelProperties);

            case 'string':
                return static::buildModelProperties($this->content);
        }
        return false;
    }

    public function save(){
        
    }

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
        $this->content = static::buildFileContent($this->content, $this->modelProperties);
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