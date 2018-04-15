<?php
namespace AF\Models;

use Status;
use Storage;
use Tangram\MODEL\ObjectModel;

/**
 * @class AF\Models\FileBasedCollection
 * File Based Model
 * 基于单一文件的数据模型
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
class FileBasedCollection  implements \Collection {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/) : string {
        new Status(1442, '', 'method "getFilename" must be redeclared.', true);
    }

    final protected static function getFileContent($filename){
        if(is_readable($filename)){
            return file_get_contents($filename);
        }
        return false;
    }

    final protected static function putFileContent($filename, array $array = []) : bool {
        $path = dirname($filename);
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(file_put_contents($filename, static::buildFileContent($array))){
            return true;
        }
        return false;
    }

    protected static function readCollection($content) : array {
        return json_decode($content, true) or [];
    }

    protected static function buildContent(array $array = []) : string {
        return json_encode($array);
    }

    protected static function createItem($item) : object {
        return $item;
    }

    protected
    $filename = '',
    $storage = [];

    public function __construct($guid, array $options = []){
        $this->filename = static::getFilename($guid);
        if($content=static::getFileContent($filename)){
            $this->storage = static::readCollection($content);
        }else{
            $this->storage = [];
        }
    }

    final public function push($item){
        return $this->storage[] = static::createItem($item);
    }

    final public function clear(){
        return $this->storage = [];
    }
    
    final public function save(){

        return putFileContent($this->filename, $this->storage);
    }
}