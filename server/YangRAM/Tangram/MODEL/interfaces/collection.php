<?php
namespace Tangram\MODEL\interfaces;

/**
 * Model Interface
**/
interface collection extends \IteratorAggregate, \ArrayAccess, \Countable {
    public function item($index);
    
    public function append($value);

    public function json_encode();

    public function xml_encode($root_tag, $item_tag, $version, $encoding);
}
