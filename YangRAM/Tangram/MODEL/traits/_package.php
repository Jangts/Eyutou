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
        $this->uns($name);
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

/**
 * Format Conversion Trait
 * 格式转换特性
**/
trait conversion {
    private static $roman = [
        'M' => 1000,
        'D' => 500,
        'C' => 100,
        'L' => 50,
        'X' => 10,
        'V' => 5,
        'I' => 1
    ];

    final protected static function fillXMLElement(array $arr, $dom, $xml){
        if(isset($arr['tag'])){
            if(isset($arr['value'])&&is_scalar($arr['value'])){
                $ele = $dom->createElement($arr['tag'], $arr['value']);;
            }else{
                $ele = $dom->createElement($arr['tag']);
                if(isset($arr['value'])&&is_array($arr['value'])){
                    foreach ($arr['value'] as $child) {
                        self::fillXMLElement($child, $dom, $ele);
                    }
                }
            }
            if(isset($arr['attr'])&&is_array($arr['attr'])){
                foreach ($arr['attr'] as $attr => $value) {
                    $ele->setAttribute($attr, $value);
                }
            }
            $xml->appendchild($ele);
        }
    }

    final protected static function readXMLElement($node){
        $array = [
            'tag' => $node->nodeName
        ];
        if ($node->hasAttributes()) {
            $array['attr'] = [];
            foreach ($node->attributes as $attr) {
                $array['attr'][$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            $arr = [];
            $txt = '';
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    $arr[] = self::readXMLElement($childNode);
                }else{
                    $txt .= $childNode->nodeValue;
                }
            }
            if(count($arr)){
                $array['value'] = $arr;
            }else{
                $array['value'] = $txt;
            }
        } else {
            $array['value'] = $node->nodeValue;
        }
        return $array;
    }

    final public static function decToRoman($num){
        if(!is_numeric($num) || $num > 3999 || $num <= 0){
            return false;
        }
        foreach(self::$roman as $k => $v){
            if(($amount[$k] = floor($num / $v)) > 0){
                $num -= $amount[$k] * $v;
            }
        }
        $return = '';
        foreach($amount as $k => $v){
            $return .= $v <= 3 ? str_repeat($k, $v) : $k . $old_k;
            $old_k = $k;
        }
        return str_replace(['VIV','LXL','DCD'],['IX','XC','CM'],$return);
    }

    final public static function romanToDec($str = ''){
        if(is_numeric($str)){
            return false;
        }
        $range = str_split($str);
        foreach($range as $s){
            if(isset(self::$roman[strtoupper($s)])){
                $values[] = self::$roman[strtoupper($s)];
            }
        }
        $sum = 0;
        while($current = current($values)){
            $next = next($values);
            $next > $current ? $sum += $next - $current + 0 * next($values) : $sum += $current;
        }
        return $sum;
    }

    final public static function arrayToXml(array $array, $version = '1.0', $encoding = 'UTF-8'){
        $dom = new \DomDocument($version, $encoding);
        self::fillXMLElement($array, $dom, $dom);
		return $dom->saveXML();
    }

    
    final public static function xmlToArray($source){
        if(is_string($source)&&$source!=''){
            $dom = new \DOMDocument();
            $dom->loadXML($source);
            $array = self::readXMLElement($dom);
            if(($array['tag'] === '#document')&&is_array($array['value'])&&isset($array['value'][0])){
                return $array['value'][0];
            }
        }
        return [];
    }

    final public static function arrayToQueryString($data, $numericPrefix = 'arg_', $encodeType = false){
        switch($encodeType){
            case PHP_QUERY_RFC1738:
            case PHP_QUERY_RFC3986:
            return http_build_query($data, $numericPrefix, '&', $encodeType);

            default:
            $array = [];
            foreach($data as $key=>$val){
                if($val&&is_string($val)){
                    $val = '='.$val;
                }else{
                    $val = '';
                }
                if(is_numeric($key)){
                    $array[] = $numericPrefix.$key.$val;
                }else{
                    $array[] = $key.$val;
                }
            }
            return join('&', $array);
        }
    }

    final public static function jsonToJson($data, $trim = true, $indent = '   '){
        if($trim){
            return json_encode(json_decode($data));
        }
        $data = urldecode($data);

        $ret = '';
        $pos = 0;
        $length = strlen($data);
        $newline = "\n";
        $prevchar = '';
        $outofquotes = true;

        for($i=0; $i<=$length; $i++){
            $char = substr($data, $i, 1);
            if($char=='"' && $prevchar!='\\'){
                $outofquotes = !$outofquotes;
            }elseif(($char=='}' || $char==']') && $outofquotes){
                $ret .= $newline;
                $pos --;
                for($j=0; $j<$pos; $j++){
                    $ret .= $indent;
                }
            }
            $ret .= $char;
            if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
                $ret .= $newline;
                if($char=='{' || $char=='['){
                    $pos ++;
                }
                for($j=0; $j<$pos; $j++){
                    $ret .= $indent;
                }
            }
            $prevchar = $char;
        }
        return $ret;
    }

    public static function arraySort($array, array $orderBy = [false, false, self::SORT_REGULAR]){
        # 暂时不实现
    }
}

/**
 * Data Formatting Trait
 * 数据格式化特性
**/
trait formatting {
    final protected static function correctArrayByTemplate(array $input, array $template, $defaults = NULL, $checkDefaults = true){
        if(is_array($defaults)){
            if($checkDefaults){
                $defaults = array_merge($template, array_intersect_key($defaults, $template));
            }
        }else{
            $defaults = $template;
        }
        return array_merge( $defaults, array_intersect_key($input, $template));
    }

    final protected static function getArrayByXml($source, $root = 'root') {
        $xml = new \XMLReader();
        $xml->xml($source);
        $data = self::xmlDecode($xml);
        $xml->close();
        if(isset($data[$root])){
            return $data[$root];
        }
        return [];
    }

    final protected static function getXmlbyArray($data, $root = 'data', $version = '1.0', $encoding = 'UTF-8', $style = NULL, $xml_use_numeric_key = false){
        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->startDocument($version, $encoding);
        $xml->startElement($root);
        if(is_array($data)){
            self::xmlEncode($xml, $data, $xml_use_numeric_key);
        }elseif(is_object($data)){
            self::xmlEncode($xml, get_object_vars($data), $xml_use_numeric_key);
        }
        $xml->endElement();
        return $xml->outputMemory(true);
    }

    final protected static function arrayEncode($data){
        if(is_array($data)){
            return $data;
        }
        elseif(is_object($data)){
            if(function_exists([$data, 'getArrayCopy'])){
                return $data->getArrayCopy();
            }
            return get_object_vars($data);
        }
        elseif(is_scalar($data)){
            return [
                'type'  =>  'Scalar '.gettype($data),
                'value' =>  $data
            ];
        }
        else{
            return [
                'type'  =>  'Unknown Data',
                'value' =>  $data
            ];
        }
    }

    final protected static function jsonEncode($data){
        if($json = json_encode($data)){
            return $json;
        }
        return '{"type":"Unknown Type", "value":""}';
    }

    final protected static function jsonDecode($data){
        if($obj = json_decode($data, true)){
            return $obj;
        }
        return [
            'type'  =>  'Error JSON',
            'value' =>  $data
        ];
    }

    final protected static function xmlEncode($xml, array $data, $xml_use_numeric_key) {
        foreach($data as $key => $value){
            if(is_numeric($key)&&$xml_use_numeric_key!==true){
                $key = 'li';
            }
            if(is_scalar($value)){
                $xml->writeElement($key, $value);
                continue;
            }
            if(is_array($value)){
        		$xml->startElement($key);
    			self::xmlEncode($xml, $value);
    			$xml->endElement();
        		continue;
        	}
            if(is_object($value)){
    			$xml->startElement($key);
    			self::xmlEncode($xml, get_object_vars($value));
    			$xml->endElement();
        		continue;
        	}
		}
	}

    final protected static function xmlDecode($xml) {
        $ele = [];
        $txt = [];
        while(@$xml->read()){
            switch ($xml->nodeType) {
                case \XMLReader::END_ELEMENT:
                if(empty($ele)){
                    return join('', $txt);
                }else {
                    return $ele;
                }

                case \XMLReader::ELEMENT:
                $ele[$xml->name] = $xml->isEmptyElement ? '' : self::xmlDecode($xml);
                break;

                case \XMLReader::TEXT:
                $txt[] = $xml->value;
                break;
            }
        }
        if(empty($ele)){
            return join('', $txt);
        }
        return $ele;
    }
}