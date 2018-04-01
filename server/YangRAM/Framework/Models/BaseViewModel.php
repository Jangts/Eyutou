<?php
namespace AF\Models;

use Status;
use Storage;
use Tangram\MODEL\ObjectModel;

/**
 * @class AF\Models\BaseViewModel
 * Data View Model
 * 数据视图模型，带有页面渲染能力的模型
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseViewModel extends \Packages\NIML implements \DataModel {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    public static function updateTemplateCache($templates=NULL){
		return false;
    }

    public static function __loadData($name, $dir = __DIR__){
		return json_decode(file_get_contents($dir.'/providers/'.$name.'.json'), true);
    }
    
    protected 
    $readonly = false,
    $template = '',
    $vars = [],
    $mime = 'text/html',
	$theme = 'default';

    public function __construct(array $input = [], $readonly = false){
        $this->vars = array_merge($this->vars, $input);
        $this->readonly = $readonly;
    }

    public function count(){
        return count($this->vars);
    }
    
	public function getFilenames($template, $is_include = false){
        new Status(1422, '', 'Must redeclare a getFilenames function in your extended viewmodel.');
    }

    public function getIterator() {
        return new \ArrayIterator($this->vars);   
    }
    
    /**  
	 * 渲染属性数组
	 * 
	 * @access public
	 * @return void
	**/ 
    abstract public function render();

    /**  
	 * 取值方法
	 * 
	 * @access public
     * @final
     * @param string $name 属性名称
	 * @return mixed
	**/ 
    final public function get($name){
        if(is_array($this->vars)&&isset($this->vars[$name])){
            return $this->vars[$name];
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
        return $this->assign($name, $value);
    }

    /**  
	 * 删除属性
	 * 
	 * @access public
     * @param string $property 属性名称
	 * @return object
	**/ 
    public function uns($property){
        if(is_array($this->vars)){
            if(array_key_exists($name, $this->vars)){
                unset($this->vars[$property]);
            }
            return $this;
        }
        \Status::cast('vars of view model object must be a array.', 1461);
    }

    /**  
	 * 将属性数组转化为字符串
	 * 
	 * @access public
	 * @return string
	**/
    public function str(){
        self::arrayToXml($this->vars, $version, $encoding);
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
        if(is_array($this->vars)&&array_key_exists($name, $this->vars)){
            return true;
        }
        return false;
    }
}