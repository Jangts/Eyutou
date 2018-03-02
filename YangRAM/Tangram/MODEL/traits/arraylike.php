<?php
namespace Tangram\MODEL\traits;

/**
 * Array Like Method Trait
**/

trait arraylike {
    public function count(){
        return count($this->modelProperties);
    }

    public function getIterator() {
        return new \ArrayIterator($this->modelProperties);
    }

    public function offsetExists($name){
        return $this->__isset($name);
    }

    public function offsetGet($name){
        return $this->__get($name);
    }
    
    public function offsetSet($name, $value){
        return $this->__set($name, $value);
    }

    public function offsetUnset($name){
        return $this->__unset($name, $value);
    }
}