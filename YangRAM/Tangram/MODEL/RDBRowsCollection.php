<?php
namespace Tangram\MODEL;

use PDO;
use PDOStatement;

/**
 * @class Tangram\MODEL\RDBRowsCollection
 * RDBQuerier Select Result
 * Tangram\CTRLR\RDBQuerier与Tangram\CTRLR\RDBQuerierPlus执行查询成功后反对的结果对象
 * 可以更方便的提取到自己想要的格式
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class RDBRowsCollection extends DataList {
    protected
    $PDOStatement = false,
    $queryString = false,
    $readpnly = true,
    $storage = false,
    $xml = false,
    $json = false;

    public function __construct(PDOStatement $PDOStatement){
        if(is_a($PDOStatement, 'PDOStatement')){
            $this->PDOStatement = $PDOStatement;
            $this->querierString = $PDOStatement->queryString;
        }
    }

    /**
     * 新增元素
     * 因为Tangram\MODEL\RDBRowsCollection是个只读对象，所以不能新增元素
     * 
     * @access public
     * @param mixed $value
     * @return bool
     */
    public function append($value){
        return false;
    }

    public function item($index = 0){
        $this->getArrayCopy();
        if($this->storage&&is_numeric($index)){
            return isset($this->storage[$index]) ? $this->storage[$index] : false;
        }
        return false;
    }

    public function count(){
        return count($this->getArrayCopy());
    }

    public function getArrayCopy($indexField = false){
        if($this->storage===false&&($pdos = $this->PDOStatement)){
            $array = [];
            if($pdos){
                if($indexField){
                    while($row = $pdos->fetch(PDO::FETCH_ASSOC)){
                        if(isset($row[$indexField])){
                            $array[$row[$indexField]] = $row;
                        }
                    }
                }else{
                    $array = $pdos->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            $this->storage = $array;
        }
		return $this->storage;
	}

    public function getIterator(){
        return $this->PDOStatement;
    }

    public function offsetExists($index = 0){
        $this->getArrayCopy();
        if($this->storage&&is_numeric($index)){
            return isset($this->storage[$index]);
        }
        return false;
    }

    public function json_encode(){
        if($this->json===false&&($pdos = $this->PDOStatement)){
            $json = '[';
            $row = $pdos->fetch(PDO::FETCH_ASSOC);
            while($row){
                $json .= json_encode($row);
                if($row = $pdos->fetch(PDO::FETCH_ASSOC)){
                    $json .= ',';
                }
            }
            $json .= ']';
            $this->json = $json;
        }
        return $this->json;
    }

    public function xml_encode($root_tag = 'result', $item_tag = 'row', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml===false&&($pdos = $this->PDOStatement)){
            $dom = new \DomDocument($version,  $encoding);
    		$xml = '<'.$root_tag.'>';
    		while($row = $pdos->fetch(PDO::FETCH_ASSOC)){
    			$xml .= '<'.$item_tag.'>';
    			foreach($row as $tag=>$txt){
    				$xml .= '<'.$tag.'>'.$txt.'</'.$tag.'>';
    			}
    			$xml .= '</'.$item_tag.'>';
    		}
    		$xml .= '</'.$root_tag.'>';
    		$dom->loadXml($xml);
            $this->xml = $dom->saveXML();
        }
		return $this->xml;
    }
}
