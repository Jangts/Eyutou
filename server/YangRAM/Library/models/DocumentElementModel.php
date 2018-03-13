<?php
namespace Lib\models;

/**
 * @class Lib\models\DocumentElementModel
 * Document Model
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
class DocumentElementModel implements \DataModel {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    private static $instances = [];

    private static function reg($instance){
        self::$instances[] = $instance;
        return count(self::$instances);
    }

    private static function formatClassAttr($value, $old = []){
        if(is_string($value)){
            $value = preg_split('/\s+/', $value);
        }
        if(!is_array($value)){
            $value = [];
        }
        return array_unique(array_merge($value, $old));
    }

    private static function formatStyleAttr($value){
        if(is_array($value)){
            return $value;
        }
        $value = [];
        if(is_string($value)){
            $styles = preg_split('/\s*\;[\;\s]*/', $value);
            foreach($styles as $style){
                $style = preg_split('/\:\s*/', $style);
                if(count($style)>1){
                    $value[$style[1]] = $style[2];
                }
            }
        }
        return $value;
    }

    private static function formatAttrs($attributes){
        if(!is_array($attributes)){
            return [
                'class' =>  [],
                'style' =>  []
            ];
        }
        if(empty($attributes['class'])){
            $attributes['class'] = [];
        }else{
            $attributes['class'] = self::formatClassAttr($attributes['class']);
        }

        if(empty($attributes['style'])){
            $attributes['style'] = [];
        }else{
            $attributes['style'] = self::formatStyleAttr($attributes['style']);
        }
        return $attributes;
    }

    private static function buildElementArray($tagname, $content, $attributes = []){
        return [
            'tagname'   =>  $tagname,
            'attributes'     =>  self::formatAttrs($attributes),
            'children'  =>  [$content]
        ];
    }

    private static function buildElementString($properties){
        $tagname = $properties['tagname'];

        $attributes = $properties['attributes'];
        if(empty($attributes['class'])){
            unset($attributes['class']);
        }else{
            $attributes['class'] = implode(' ', $attributes['class']);
        }
        if(empty($attributes['style'])){
            unset($attributes['style']);
        }else{
            $styles = [];
            foreach($attributes['style'] as $prop=>$value){
                $styles[] = $prop . ': ' . $value . ';';
            }
            $attributes['style'] = implode(' ', $styles);
        }

        $strings = [$tagname];
        if($tagname==='input'){
            if(!isset($attributes['type'])){
                if(isset($attributes['hidden'])){
                    $attributes['type'] = 'hidden';
                }else{
                    $attributes['type'] = 'text';
                }
            }
            if(!isset($attributes['value'])){
                $attributes['value'] = $properties['children'][0];
            }
        }

        foreach($attributes as $attr=>$value){
            $strings[] = $attr . '="' . $value . '"';
        }

        switch($tagname){
            case 'br': case 'hr':
            return '<' . $tagname . ' />';

            case 'link': case 'meta': case 'img': case 'input':
            case 'area': case 'base': case 'col': case 'command': case 'embed': case 'keygen': case 'param': case 'source': case 'track': case 'wbr':
            return '<' . implode(' ', $strings) . ' />';

            default:
            $content = '';
            foreach($properties['children'] as $child){
                if(is_array($child)){
                    $content .= self::buildElementString($child);
                }else{
                    $content .= $child;
                }
            }
            if(empty(trim($content))&&$tagname==='script'){
                return '<' . implode(' ', $strings) . ' />';
            }
            return '<' . implode(' ', $strings) . '>' . $content . '</' . $tagname . '>';
        }
    }

    protected
    // ID，模型必须有一个ID，可以是某个属性，也可以是任何自定义组合
    $__guid,
    // 子元素数组
    $modelProperties = [];

    /**  
	 * 模型实例构造函数
	 * 
	 * @access public
     * @param string|int $tagname 主标识值，又名主键索引值，也可简称索引值
	 * @return 构造函数无返回值
	**/ 
    final public function __construct($tag, $content = ''){
        $this->__guid = self::reg($this);
        $array = explode('#', $tag);
        $classnames = explode('.', $array[0]);
        $tagname = array_shift($classnames);
        $this->modelProperties = self::buildElementArray($tagname, $content);
        if(isset($array[1])){
            $classnames1 = explode('.', $array[1]);
            $this->addAttr('id', array_shift($classnames1));
            $this->modelProperties['attributes']['class'] = array_merge($classnames, $classnames1);
        }else{
            $this->modelProperties['attributes']['class'] = $classnames;
        }
    }

    final public function getGuid(){
        return $this->__guid;
    }

    final public function getCOUNT(){
        return count($this->modelProperties['children']);
    }

    final public function appendElement($tag, $content = ''){
        if(is_a($tag, 'Lib\models\DocumentElementModel')){
            $this->modelProperties['children'][] = $tag;
        }
        if(is_string($tag)){
            $this->modelProperties['children'][] = self::buildElementArray($tag, $content);
        }
        if(is_array($tag)){
            if(isset($tag['tagname'])){
                if(empty($tag['attributes'])){
                    $tag['attributes'] = [
                        'class' =>  [],
                        'style' =>  []
                    ];
                }else{
                    $tag['attributes'] = self::formatAttrs($tag['attributes']);
                }
                if(empty($tag['children'])){
                    $tag['children'] = [$content];
                }
                $this->modelProperties['children'][] = $tag;
            }
        }
        return $this;
    }

    final public function appendContent($el){
        $this->modelProperties['children'][] = $el;
        return $this;
    }

    final public function get($index){
        if(isset($this->modelProperties['children'][$index])){
            return $this->modelProperties['children'][$index];
        }
        return false;
    }

    final public function has($index){
        if(isset($this->modelProperties['children'][$index])){
            return true;
        }
        return false;
    }

    final public function set($index, $el){
        if(isset($this->modelProperties['children'][$index])){
            $this->modelProperties['children'][$index] = $tag;
        }
        return false;
    }

    final public function uns($index){
        if(isset($this->modelProperties['children'][$index])){
            unset($this->modelProperties['children'][$index]);
        }
        return false;
    }

    final public function setAttr($attr, $value){
        if($attr === 'class'){
            $value = self::formatClassAttr($value);
        }
        if($attr === 'style'){
            $value = self::formatStyleAttr($value);
        }
        $this->modelProperties['attributes'][$attr] = $value;
        return $this;
    }

    final public function unsetAttr($attr, $value){
        if($attr === 'class'||$attr === 'style'){
            $this->modelProperties['attributes'][$attr] = [];
            return $this;
        }
        if(isset($this->modelProperties['attributes'][$attr])){
            unset($this->modelProperties['attributes'][$attr]);
        }
        return $this;
    }

    final public function setStyle($prop, $value){
        $this->modelProperties['attributes']['style'][$prop] = $value;
        return $this;
    }

    final public function unsetStyle($prop, $value){
        if(isset($this->modelProperties['attributes']['style'][$prop])){
            unset($this->modelProperties['attributes']['style'][$prop]);
        }
        return $this;
    }

    final public function addClass($classname){
        $this->modelProperties['attributes']['class'] = self::formatClassAttr($classname, $this->modelProperties['attributes']['class']);
        return $this;
    }
    
    final private function unsetSingleClass($classname){
        $index = array_search($classname, $this->modelProperties['attributes']['class']);
        if($index!==false){
            unset($this->modelProperties['attributes']['class'][$index]);
        }
        return $this;
    }

    final public function unsetClass($classname){
        $classnames = self::formatClassAttr($classname);
        foreach($classnames as $classname){
            $this->unsetSingleClass($classname);
        }
        return $this;
    }

    final public function str(){
        return self::buildElementString($this->modelProperties);;
    }
}