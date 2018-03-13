<?php
namespace Lib\models;

/**
 * @class Lib\models\RestraintModel
 * Attribute Restraint Model
 * 拓展属性约束模型
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class RestraintModel implements \DataModel {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    private static
    $types = [
        'mixed'     =>  '',
        'all'       =>  '',
        'scalar'    =>  '',
        'radio'     =>  '',
        'string'    =>  '',
        'stringwithnull'    =>  NULL,
        'ip'        =>  '',
        'url'       =>  '',
        'text'      =>  '',
        'char'      =>  '',
        'varchar'   =>  '',
        'date'      =>  '',
        'file'      =>  '',
        'time'      =>  '',
        'email'     =>  '',
        'imgtext'   =>  '',
        'longtext'  =>  '',
        'datetime'  =>  DATETIME,
        'numeric'   =>  0,
        'numericwithnull'    =>  NULL,
        'number'    =>  0,
        'numberwithnull'    =>  NULL,
        'percentage'=>  0,
        'is'        =>  0,
        'int'       =>  0,
        'tinyint'   =>  0,
        'dayofweek' =>  0,
        'json'      =>  '{}',
        'bin'       =>  '0',
        'hex'       =>  '0',
        'color'     =>  'FFFFFF',
        'float'     =>  0,
        'float1'    =>  0.0,
        'float2'    =>  0.00,
        'float3'    =>  0.000,
        'stamp'     =>  BOOTTIME,
        'double'    =>  0,
        'bool'      =>  true,
        'boolean'   =>  1,
        'array'     =>  [],
        'tags'      =>  'tag1, tag2',
        'files'     =>  '[]',
        'checkbox'  =>  'opt1, opt2'
    ],
    $maxLengths = [
        'char'      =>  255,
        'file'      =>  512,
        'varchar'   =>  65532
    ],
    $patterns = [
        'ip'       =>   '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
        'ipv6'     =>   '/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/',
        'url'      =>   '/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/',
        'date'     =>   '/^\d{4}\-\d{1,2}\-\d{1,2}$/',
        'time'     =>   '/^\d{1,2}\:\d{1,2}\:\d{1,2}$/',
        'email'    =>   '/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',
        'imgtext'  =>   '/^data:image\/(jpeg|png|gif);base64,/',
        'datetime' =>   '/^\d{4}\-\d{1,2}\-\d{1,2}\s+\d{1,2}\:\d{1,2}\:\d{1,2}$/',
        'color'    =>   '/^[0-9a-fA-F]{6,8}$/',
        'bin'      =>   '/^[01]+$/',
        'hex'      =>   '/^[0-9a-fA-F]+$/'
    ];

    public static function typeExsit($type){
        if(isset(self::$types[$type])){
            return true;
        }
        return false;
    }

    private
    $type = 'mixed',
    $modelProperties = [
        'TYPE'          =>  '',
        'MAX_LENGTH'    =>  0,
        'FLOAT_LENGTH'  =>  false,
        'RANGE'         =>  [],
        'PATTERN'       =>  '/[\s\S]*/',
        'DEFAULT'       =>  ''
    ];

    public function __construct($type = 'mixed', $default_value = NULL){
        if(!array_key_exists($type, self::$types)){
            $type = 'mixed';
        }
        $this->__put($type);
        if($default_value===NULL){
            $this->modelProperties['DEFAULT'] = self::$types[$type];
        }else{
            $this->modelProperties['DEFAULT'] = $default_value;
        }
    }

    private function __put($type){
        $this->type = $type;
        $max_length = 0;
        $float_length = false;
        $range = [];
        $pattern = '/[\s\S]*/';
        switch($type){ 
            case 'all'      :
            $type = 'mixed';
            break;

            case 'radio'      :
            $type = 'scalar';
            $range = ['opt1', 'opt2'];
            break;

            case 'file'     :
            $pattern = self::$patterns['url'];
            case 'char'     :
            case 'varchar'  :
            $max_length = self::$maxLengths[$type];
        
            case 'text'     :
            $type = 'string';
            break;

            case 'ip'       :
            case 'ipv6'     :
            case 'url'      :
            case 'imgtext'  :
            case 'date'     :
            case 'time'     :
            case 'email'    :
            case 'datetime' :
            case 'color'    :
            case 'bin'      :
            case 'hex'      :
            // 基本为文本，属于预设了模式的文本
            // 只需再传入取值范围和文本长度仍然有效
            $pattern = self::$patterns[$type];
            $type = 'string';
            break;

            case 'is':
            $type = 'numberic';
            $max_length = 1;
            $float_length = 0;
            $range = ['0', '1'];
            break;

            case 'percentage':
            $type = 'numberic';
            $max_length = 3;
            $float_length = 0;
            $range = [0, 100];
            break;

            case 'dayofweek':
            $type = 'numberic';
            $max_length = 1;
            $float_length = 0;
            $range = [0, 6];
            break;

            case 'float1'   :
            $float_length = 1;
            $type = 'float';
            break;

            case 'float2'   :
            $float_length = 2;
            $type = 'float';
            break;

            case 'float3'   :
            $float_length = 3;
            $type = 'float';
            break;

            case 'stamp'    :
            $type = 'float';
            break;

            case 'files'    :
            $type = 'json';
            break;

            case 'boolean'  :
            $type = 'bool';
            case 'bool'  :
            $range = [0, 1, true, false, '0', '1', 'true', 'false'];
            break;

            case 'tags'     :
            $type = 'checkbox';
            $range = ['tag1', 'tag2', 'tag2'];
            break;

            case 'checkbox' :
            $range = ['opt1', 'opt2', 'opt2'];
            break;
        }

        $this->modelProperties = [
            'TYPE'          =>  $type,
            'MAX_LENGTH'    =>  $max_length,
            'FLOAT_LENGTH'  =>  $float_length,
            'RANGE'         =>  $range,
            'PATTERN'       =>  $pattern
        ];
    }

    public function get($name){
        if(isset($this->modelProperties[$name])){
            return $this->modelProperties[$name];
        }
        return NULL;
    }

    final public function has($name){
        if(array_key_exists($name, $this->modelProperties)){
            return true;
        }
        return false;
    }

    public function set($name, $value){
        switch($name){
            case 'MAX_LENGTH':
            case 'attribute_length':
            case 'length':
            return $this->setMaxLength($value);

            case 'FLOAT_LENGTH':
            case 'attribute_flote':
            case 'flote':
            return $this->setFloatLength($value);

            case 'RANGE':
            case 'attribute_values':
            case 'values':
            case 'radio_options':
            case 'select_options':
            case 'options':
            return $this->setRange($value);

            case 'PATTERN':
            case 'attribute_pattern':
            case 'pattern':
            return $this->setPattern($value);
        }
        return $this;
    }

    public function setMaxLength($value){
        $value = intval($value);
        if(is_int($value)&&in_array($this->type, ['text', 'string', 'url', 'file', 'email', 'numeric', 'number', 'int', 'bin', 'hex', 'float', 'float1', 'float2', 'float3', 'double'])){
            $this->modelProperties['MAX_LENGTH'] = $value;
        }
        return $this;
    }

    public function setFloatLength($value){
        $value = intval($value);
        if($this->type==='float'){
            if($value<=8){
                $this->modelProperties['FLOAT_LENGTH'] = $value;
            }
        }elseif($this->type==='double'){
            if($value<=16){
                $this->modelProperties['FLOAT_LENGTH'] = $value;
            }
        }
        return $this;
    }

    public function setRange($value){
        if(is_array($value)){
            $value = array_values($value);
        }else{
            return $this;
        }
        // 将可选数组分成三份，是为遵循常用优先
        // 建议设定范围，否则将失去实际意义
        if(in_array($this->type, ['radio', 'tags', 'checkbox'])){
            $this->modelProperties['RANGE'] = $value;
        }
        // 不建议修改取值范围
        elseif(in_array($this->type, ['percentage', 'dayofweek', 'bool', 'boolean'])){
            $this->modelProperties['RANGE'] = $value;
        }
        // 可以增加约束范围，但请慎用
        elseif(in_array($this->type, ['mixed', 'all', 'scalar', 'string', 'ip', 'url', 'text', 'char', 'varchar', 'date', 'file', 'time', 'email', 'datetime', 'numeric', 'number', 'int', 'bin', 'hex', 'color', 'float', 'float1', 'float2', 'float3', 'stamp', 'double'])){
            $this->modelProperties['RANGE'] = $value;
        }
        return $this;
    }

    public function setPattern($value){
        if(in_array($this->type, ['text', 'string', 'longtext'])){
            $this->modelProperties['PATTERN'] = $value;
        }
        return $this;
    }

    public function str(){
        return json_encode([$this->type, $this->modelProperties]);
    }

    public function uns($name){
        return false;
    }

    public function check($value){
        $resType = $this->matchType($value);
        if($resType===2){
            if($this->matchLength($value)){
                switch($this->modelProperties['TYPE']){
                    case 'array'    :
                    return true;

                    case 'stringwithnull'   :
                    if($value===NULL){
                        return true;
                    }
                    case 'string'   :
                    case 'checkbox' :
                    return $this->matchString($value);

                    case 'json'     :
                    return $this->matchJSON($value);

                    case 'numberwithnull'   :
                    if($value===NULL){
                        return true;
                    }
                    case 'numeric'  :
                    $value = floatval($value);
                    case 'numberwithnull'   :
                    if($value===NULL){
                        return true;
                    }
                    case 'number'   :
                    case 'int'      :
                    case 'float'    :
                    case 'double'   :
                    return $this->matchNumber($value);

                    default:
                    return $this->matchRange($value);
                }
            }
        }
        return false;
    }

    public function corrent($value = NULL){
        if($value===NULL){
            return $this->modelProperties['DEFAULT'];
        }
        $resType = $this->matchType($value);
        if($resType){
            if($this->matchLength($value)){
                switch($this->modelProperties['TYPE']){
                    case 'stringwithnull'   :
                    if($value===NULL){
                        return NULL;
                    }
                    case 'string'   :
                    case 'checkbox' :
                    $value = strval($value);
                    if($this->matchString($value)){
                        return $value;
                    }
                    return $this->modelProperties['DEFAULT'];

                    case 'array'    :
                    return $value;

                    case 'json'     :
                    if($this->matchJSON($value)){
                        return $value;
                    }
                    return $this->modelProperties['DEFAULT'];

                    case 'numericwithnull'   :
                    case 'numberwithnull'   :
                    if($value===NULL){
                        return NULL;
                    }
                    case 'numeric'  :
                    $value = floatval($value);
                    case 'number'   :
                    case 'int'      :
                    case 'float'    :
                    case 'double'   :
                    if($this->modelProperties['FLOAT_LENGTH']){
                        $value = floatval(sprintf('%.'.$this->modelProperties['FLOAT_LENGTH'].'f', $value));
                    }else{
                        $value = intval($value);
                    }
                    if($this->matchNumber($value)){
                        return $value;
                    }
                    return $this->modelProperties['DEFAULT'];

                    case 'bool':
                    if($this->matchRange($value)){
                        if(is_int($this->modelProperties['DEFAULT'])){
                            if($value==='true'){
                                return 1;
                            }
                            return intval($value);
                        }elseif(is_bool($this->modelProperties['DEFAULT'], true)){
                            if($value==='false'){
                                return false;
                            }
                            return !!intval($value);
                        }elseif(is_string($this->modelProperties['DEFAULT'])){
                            if($value==='false'||!intval($value)){
                                return '0';
                            }
                            return '1';
                        }
                        return $value;
                    }
                    return $this->modelProperties['DEFAULT'];

                    default:
                    if($this->matchRange($value)){
                        return $value;
                    }
                    return $this->modelProperties['DEFAULT'];
                }
            }
        }
        return $this->modelProperties['DEFAULT'];
    }

    private function matchType($value){
        switch($this->modelProperties['TYPE']){
            case 'mixed'    :
            return 2;

            case 'scalar'   :
            if(is_scalar($value)){
                return 2;
            }
            return 0;
            
            case 'string'   :
            case 'json'     :
            case 'array'    :
            case 'checkbox' :
            if(is_string($value)){
                return 2;
            }
            return 1;

            case 'numeric'  :
            case 'number'   :
            if(is_numeric($value)){
                if($this->modelProperties['TYPE']==='number'&&is_string($value)){
                    return 1;
                }
                return 2;
            }
            return 0;
            
            case 'int'      :
            case 'float'    :
            case 'double'   :
            $method = 'is_'.$this->modelProperties['TYPE'];
            if($method($value)){
                return 2;
            }
            if($method(floatval($value))){
                return 1;
            }
            return 0;

            case 'bool'     :
            if(in_array($value, [0, 1, true, false], true)){
                return 2;
            }
            if(in_array($value, ['0', '1', 'true', 'false'], true)){
                return 1;
            }
        }
        return 0;
    }

    private function matchLength($value){
        if($this->modelProperties['MAX_LENGTH']===0){
            return true;
        }
        if($this->modelProperties['TYPE']==='bool'){
            return true;
        }
        if($this->modelProperties['TYPE']==='checkbox'){
            $valArray = preg_split('/\s*\,+\s*/', $value);
            foreach($valArray as $val){
                if(strlen($val)>$this->modelProperties['MAX_LENGTH']){
                    return false;
                }
            }
            return true;
        }
        if(strlen($value)<=$this->modelProperties['MAX_LENGTH']){
            return true;
        }
        return false;
    }

    private function matchRange($value){
        if(empty($this->modelProperties['RANGE'])){
            return true;
        }
        if($this->modelProperties['TYPE']==='checkbox'){
            $valArray = preg_split('/\s*\,+\s*/', $value);
            foreach($valArray as $val){
                if(!in_array($value, $this->modelProperties['RANGE'])){
                    return false;
                }
            }
            return true;
        }
        if(in_array($value, $this->modelProperties['RANGE'])){
            return true;
        }
        return false;
    }

    private function matchString($value){
        if(!$this->matchRange($value)){
            return false;
        }
        if(preg_match($this->modelProperties['PATTERN'], $value)){
            return true;
        }
        return false;
    }

    private function matchJSON($value){
        return json_decode($value, true);
    }
        
    private function matchNumber($value){
        if(count($this->modelProperties['RANGE'])===2&&($this->modelProperties['RANGE'][1]>$this->modelProperties['RANGE'][0])){
            if($value < $this->modelProperties['RANGE'][0]){
                return false;
            }
            if($value > $this->modelProperties['RANGE'][1]){
                return false;
            }
        }else{
            if(!$this->matchRange($value)){
                return false;
            }
        }
        if($this->modelProperties['FLOAT_LENGTH']===false){
            return true;
        }
        $numArray = preg_split('/(\.|e|E)/', $value.'.');
        if(strlen($numArray[1])===$this->modelProperties['FLOAT_LENGTH']){
            return true;
        }
        return false;
    }
}