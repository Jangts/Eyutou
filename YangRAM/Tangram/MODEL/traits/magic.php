<?php
namespace Tangram\MODEL\traits;

/**
 * Magic Method Trait
**/

trait magic {
     /**  
	 * 魔术取值方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return mixed
	**/ 
    final public function __get($name){
        return $this->get($name);
    }

    /**  
	 * 魔术写值方法
	 * 
	 * @access public
     * @final
     * @param string $name
     * @param mixed $value
	 * @return mixed
	**/ 
    final public function __set($name, $value){
        $this->set($name, $value);
        return $value;
    }

    /**  
	 * 魔术查键方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return bool
	**/ 
    final public function __isset($name){
        return $this->has($name);
    }

    /**  
	 * 魔术删键方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return 回调函数无返值
	**/ 
    final public function __unset($name){
        $this->uns($name, $value);
        return true;
    }

    /**  
	 * 魔术转文本方法
	 * 
	 * @access public
     * @final
	 * @return string
	**/ 
    final public function __toString(){
        return $this->str();
    }
}