<?php
namespace Tangram\MODEL\interfaces;

/**
 * Model Interface
**/
interface model extends \IteratorAggregate, \ArrayAccess, \Countable {
    public function __get($name);

    public function __isset($name);

    public function __set($name, $value);

    public function __toString();

    public function __unset($name);

    public function get($name);
    
    public function has($name);

    public function set($name, $value);

    public function str();

    public function uns($name);
}
