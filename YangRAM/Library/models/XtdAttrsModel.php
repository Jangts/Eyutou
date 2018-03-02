<?php
namespace Lib\models;

/**
 * @class Lib\models\XtdAttrsModel
 * Extended Attributes Model
 * 拓展属性组模型
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class XtdAttrsModel implements \DataModel {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    protected
    $storage = [],
    $modelProperties = [];

    public function __construct(array $input = []){
        foreach($input as $attr=>$option){
            $this->set($attr, $option);
        }
    }

    public function checkValue($attr, $value){
        if(isset($this->modelProperties[$attr])){
            return $this->modelProperties[$attr]->check($value);
        }
        return false;
    }

    public function correntValue($attr, $value){
        if(isset($this->modelProperties[$attr])){
            return $this->modelProperties[$attr]->corrent($value);
        }
        return false;
    }

    public function correntValues($values, $ignoreOtherAttrs = false, $format = false){
        foreach($values as $attr=>$value){
            if(isset($this->modelProperties[$attr])){
                $value = $this->modelProperties[$attr]->corrent($value);
                if($format){
                    $values[$attr] = $this->format($attr, $value);
                }else{
                    $values[$attr] = $value;
                }
            }else{
                if($ignoreOtherAttrs){
                    unset($values[$attr]);
                }
            }
        }
        return $values;
    }

    public function getDefaultValues($format = false){
        $modelProperties = [];
        foreach($this->modelProperties as $attr=>$restraint){
            $value = $restraint->corrent();
            if($format){
               $modelProperties[$attr] = $this->format($attr, $value);
            }else{
               $modelProperties[$attr] = $value;
            }
        }
        return $modelProperties;
    }

    public function format($attr, $value){
        switch($this->storage[$attr]['type']){
            case 'bool':
            $int = intval($value);
            if(isset($this->storage[$attr]['opt_map'])&&isset($this->storage[$attr]['opt_map'][$int])){
                return $this->storage[$attr]['opt_map'][$int];
            }
            return $value;

            case 'is':
            if(isset($this->storage[$attr]['opt_map'])&&isset($this->storage[$attr]['opt_map'][$value])){
                return $this->storage[$attr]['opt_map'][$value];
            }
            return $value;

            case 'radio':
            if(isset($this->storage[$attr]['opt_map'])){
                $index = array_search($value, $this->storage[$attr]['options']);
                if(isset($this->storage[$attr]['opt_map'][$index])){
                    return $this->storage[$attr]['opt_map'][$index];
                }
            }
            return $value;

            case 'checkbox':
            if(isset($this->storage[$attr]['opt_map'])){
                if(isset($this->storage[$attr]['split_tag'])){
                    $tag = $this->storage[$attr]['split_tag'];
                }else{
                    $tag = ',';
                }
                $valueArray = explode($tag, $value);
                foreach($valueArray as $i=>$val){
                    $index = array_search($value, $this->storage[$attr]['options']);
                    if(isset($this->storage[$attr]['opt_map'][$index])){
                        $valueArray[$i] = $this->storage[$attr]['opt_map'][$index];
                    }
                }
            }
            case 'tags':
            if(isset($this->storage[$attr]['split_tag'])){
                $tag = $this->storage[$attr]['split_tag'];
            }else{
                $tag = ',';
            }
            return explode($tag, $value);

            case 'json':
            case 'storage':
            return json_decode($value, true);

            case 'dayofweek':
            if(isset($this->storage[$attr]['opt_map'])&&count($this->storage[$attr]['opt_map'])>6){
                $map = $this->storage[$attr]['opt_map'];
            }else{
                $map = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            }
            if(isset($map[$index])){
                return $map[$index];
            }

            default:
            return $value;
        }
    }

    public function get($attr){
        if(isset($this->modelProperties[$attr])){
            return $this->modelProperties[$attr];
        }
        return NULL;
    }

    final public function has($attr){
        if(array_key_exists($attr, $this->modelProperties)){
            return true;
        }
        return false;
    }

    public function set($attr, $option){
        if(isset($option['type'])&&RestraintModel::typeExsit($option['type'])){
            if(isset($option['default_value'])){
                $default_value = $option['default_value'];
            }elseif(isset($option['default'])){
                $default_value = $option['default'];
            }else{
                $default_value = NULL;
            }
            $restraint = new RestraintModel($option['type'], $default_value);
            foreach($option as $key=>$value){
                $restraint->set($key, $value);
            }
            $this->storage[$attr] = $option;
            $this->modelProperties[$attr] = $restraint;
            return true;
        }
        return false;
    }

    public function str(){
        return json_encode($this->storage);
    }

    public function uns($attr){
        if(isset($this->modelProperties[$attr])){
            unset($this->storage[$attr]);
            unset($this->modelProperties[$attr]);
        }
        return NULL;
    }
}