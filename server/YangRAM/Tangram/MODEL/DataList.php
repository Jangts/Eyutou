<?php
namespace Tangram\MODEL;

/**
 * @class Tangram\MODEL\DataList
 * General Data Enclosure
 * 一般数据封装包
 * NI提供的数据封包工具，主要用来
 * 为数据提供格式化、读写保护、对比排差
 * 是NI为其他数据对象提供的基类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class DataList implements \Serializable, interfaces\collection {
    protected
    $storage,
    $readonly,
    $xml = false,
    $json = false;

    

    /**  
     * 数据闭包构造函数
     * 私有方法，只能通过静态方法self::enclose()调用
     * 
     * @access private
     * @param array $array       编码过的数据
     * @param bool $readonly    是否只读
     * @return 构造函数无返回值
    **/ 
    public function __construct(array $input, bool $readonly = false){
        $this->storage = $input;
        $this->readonly = $readonly;
    }

    /**
     * 新增元素
     * 追加新的值作为最后一个元素。
     * 
     * @access public
     * @param mixed $value
     * @return bool
     */
    public function append($value){
        if($this->readonly){
            return false;
        }
        $this->storage[] = $value;
        return true;
    }

    public function item($index){
        if(isset($this->storage[$index])){
			return $this->storage[$index];
		}
        return NULL;
    }

    public function count(){
        return count($this->storage);
    }

    public function getArrayCopy(){
        return $this->storage;
    }

    public function getIterator() {
        return new \ArrayIterator($this->storage);  
    }

    public function offsetExists($index){
        return isset($this->storage[$index]);
    }
    
    public function offsetSet($index, $value){
        if($this->readonly){
            return false;
        }
        $this->storage[$index] = $value;
        return true;
    }
    
    public function offsetGet($index){
        return $this->item($index);
    }
    
    public function offsetUnset($index){
        if($this->readonly){
            return false;
        }
        if(isset($this->storage[$index])){
            unset($this->storage[$index]);
            return true;
		}
        return false;
    }

    public function serialize() {
        return serialize($this->storage);
    }
    public function unserialize($data) {
        $this->storage = unserialize($data);
    }

    public function json_encode(){
        if($this->json===false){
            $this->json = json_encode($this->storage);
        }
        return $this->json;
    }

    public function xml_encode($root_tag = 'root', $item_tag = 'row', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml===false){
            $dom = new \DomDocument($version,  $encoding);
            $xml = '<'.$root_tag.'>';
            $xml .= self::xmlEncode($this->storage, $item_tag);
    		$xml .= '</'.$root_tag.'>';
    		$dom->loadXml($xml);
            $this->xml = $dom->saveXML();
        }
        return $this->xml;
    }

    final protected static function xmlEncode(array $array, $tag = 'li') {
        $xml = '';
        foreach($array as $key => $value){
            if(is_numeric($key)){
                $key = $tag;
            }
            if(is_scalar($value)){
                $xml .= '<'.$key.'>'.$value.'</'.$key.'>';
                continue;
            }
            if(is_array($value)){
        		$xml .= '<'.$key.'>'.self::xmlEncode($value).'</'.$key.'>';
                continue;
        	}
            if(is_object($value)){
        		$xml .= '<'.$key.'>'.self::xmlEncode(get_object_vars($value));
        		continue;
    		}
        }
        return $xml;
	}

    /**  
	 * 魔术取值方法（函数型）
	 * 
	 * @access public
     * @final
     * @param string $index
	 * @return mixed
	**/ 
    final public function __invoke($index){
         return $this->item($index);
    }
}
