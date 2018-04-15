<?php
namespace AF\Models;

use Status;
use Storage;
use App;
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

    final public static function __correctTablePrefix(App $app){
        return false;
    }

    final public static function getDirnameOfModel() : string {
        global $NEWIDEA;
        $classname = get_called_class();
        $filename = $NEWIDEA->getFilenameOfClass($classname);
        return dirname($filename);
    }

    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/) : string {
        new Status(1442, '', 'method "getFilename" must be redeclared.', true);
    }

    protected static function getFilenames(array $requires = []) : array {
        new Status(1442, '', 'method "getFilenames" must be redeclared.', true);
    }

    final protected static function getFileContent($filename){
        if(is_readable($filename)){
            return file_get_contents($filename);
        }
        return false;
    }

    final protected static function putFileContent($filename, $content, $properties) : bool {
        $path = dirname($filename);
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(file_put_contents($filename, static::buildFileContent($content, $properties))){
            return true;
        }
        return false;
    }
    
    protected static function buildModelProperties($filename, $content) : array {
        return [];
    }

    protected static function buildFileContent($content, $properties) : string {
        return $content;
    }

    final public static function query(array $requires = []) : array {
        $objs = [];
        $filenames = static::getFilenames($requires);
        foreach($filenames as $filename){
            if($content = self::getFileContent($filename)){
                $obj = new static($filename);
                $obj->put($content);
                $objs[] = $obj;
            }else{
                $objs[] = NULL;
            }
        }
        return $objs;
    }

    final public static function byGUID($guid = NULL/*单例数据文件不需要GUID*/){
        $filename = static::getFilename($guid);
        $content = self::getFileContent($filename);
        if($content!==false){
            $obj = new static($filename);
            $obj->put($content);
            return $obj;
        }
        return NULL;
    }

    final public static function create($guid = NULL/*单例数据文件不需要GUID*/, $content = NULL){
        $filename = static::getFilename($guid);
        $obj = new static($filename);
        if($content){
            $obj->put($content);
        }
        return $obj;
    }

    final public static function delete($guid = NULL/*单例数据文件不需要GUID*/){
        $filename = static::getFilename($guid);
        if(is_writable($filename)){
            return unlink($filename);
        }
        return false;
    }

    protected
    $filename = '',
    $content = '',
    $modelProperties = [];

    final protected function __construct($filename){
        $this->filename = $filename;
        $this->content = static::buildFileContent($this->content, $this->modelProperties);
    }

    final public function put($content){
        switch (gettype($content)) {
            case 'array':
            if(count($this->modelProperties)&&count($content)){
                $modelProperties = array_merge($this->modelProperties, array_intersect_key($content, $this->modelProperties));
                $this->content = static::buildFileContent($this->content, $modelProperties);
                return true;
            }
            return false;   

            case 'string':
                if($modelProperties = static::buildModelProperties($this->filename, $content)){
                    $this->content = $content;
                    $this->modelProperties = $modelProperties;
                    return true;
                }
        }
        return false;
    }

    final public function save(){
        return static::putFileContent($this->filename, $this->content, $this->modelProperties);
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
            if($modelProperties = static::buildModelProperties($this->filename, $content)){
                $this->content = $content;
                $this->modelProperties = $modelProperties;
                return true;
            }
        }elseif(isset($this->modelProperties[$name])){
            $this->modelProperties[$name] = $value;
            $this->content = static::buildFileContent($this->content, $this->modelProperties);
            return true;
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
    final public function uns($property){
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