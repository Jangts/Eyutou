<?php
namespace Tangram\MODEL;

/**
 * @class Tangram\MODEL\ObjectModel
 * General Data Enclosure
 * 一般数据封装包
 * NI提供的数据封包工具，主要用来
 * 为数据提供格式化、读写保护、对比排差
 * 是NI为其他数据对象提供的基类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class ObjectModel implements \Serializable, interfaces\model {
    use traits\magic;
    use traits\arraylike;
    use traits\conversion;
    use traits\formatting;

    const
    JSON                =   'json',
    XML                 =   'xml',
    SE                  =   'serialized',
    QS                  =   'querystring',
    MIX                 =   'mixed',

    SORT_REGULAR        =   0,              // 正常比较单元（不改变类型）
    SORT_NUMERIC        =   1,              // 单元被作为数字来比较
    SORT_STRING         =   2,              // 单元被作为字符串来比较
    SORT_LOCALE_STRING  =   3,              // 根据当前的区域（locale）设置来把单元当作字符串比较，可以用 setlocale() 来改变
    SORT_NATURAL        =   4,              // 和 natsort() 类似对每个单元以“自然的顺序”对字符串进行排序。 PHP 5.4.0 中新增的
    SORT_FLAG_CASE      =   5,              // 能够与 SORT_STRING 或 SORT_NATURAL 合并（OR 位运算），不区分大小写排序字符串
    SORT_CONVERT_GBK    =   700,            // 将单元转换为GBK编码格式，并按找GBK编码顺序排序

    LIST_AS_OBJS = 0,                    // 列表类型常量，0为实例数组
    LIST_AS_ARRS = 123,                  // 列表类型常量，123为纯数组

    DIFF_SIMPLE         =   0,              // 简单比较，只比较最外层的元素的增删改，且忽略类型
    DIFF_STRICT         =   1,              // 严谨比较，
    DIFF_DEEP           =   2;              // 深度比较

    final public static function getFilenameOfCalledClass(){
        $classloader = \Tangram\ClassLoader::instance();
        $calssname = get_called_class();
        return $classloader->getilenameOfClass($classname);
    }

    /**
	 * 打包任意数据为Tangram\MODEL\ObjectModel
	 * 
	 * @access public
     * @final
	 * @static
     * @param mixed $modelProperties       要打包的数据
     * @param bool $readonly    是否只读
     * @param string $type      指定类型，默认为mixed
	 * @return object
	**/
    final public static function enclose($modelProperties, $readonly = true, $type = self::MIX){
        switch ($type) {
            case self::JSON:
                $array = self::jsonDecode($modelProperties);
                break;

            case self::XML:
                $array = self::getArrayByXml($modelProperties);
                break;

            case self::SE:
                $array = self::arrayEncode(unserialize($modelProperties));
                break;

            case self::QS:
                parse_str($modelProperties, $array);
                break;

            default:
                $array = self::arrayEncode($modelProperties);
                break;
        }
        return new self($array, $readonly);
    }

   /**
	 * 比对数组改变量
     * 可接受双外部数据
	 * 
	 * @access public
     * @final
     * @static
     * @param array $array1     被比较的数组
     * @param array $array2     要比较的数组
     * @param string $mode      指定模式，默认为严谨模式
	 * @return array
	**/
    final public static function array_diff(array $array1, array $array2, $mode = self::DIFF_STRICT){
        $ints = array_intersect_key($array1, $array2);
        $diff = [
            '__A__' => array_diff_key($array2, $array1),
            '__D__' => array_diff_key($array1, $array2),
            '__M__' => [],
            '?????' => []
        ];
        foreach($ints as $key => $val){
            if($mode){
                // 严谨或深度模式
                // 值相等，且类型相同的跳过
                if($array2[$key]===$val) continue;

                // 类型相同的
                if(gettype($array2[$key])===gettype($val)){
                    // 如果值为数组
                    if(is_array($val)){
                        // 深度模式下，类型相同值不等，数组比对内部元素
                        if($mode===self::DIFF_DEEP){
                            $result = self::array_diff($val, $array2[$key], $mode);
                            // 如有新增键，则记录到'A'下的子元素里
                            if(count($result['__A__'])) $diff['__A__'][$key] = $result['__A__'];
                            // 如有删除键，则记录到'D'下的子元素里
                            if(count($result['__D__'])) $diff['__D__'][$key] = $result['__D__'];
                            // 如有修改键，则记录到'M'下的子元素里
                            if(count($result['__M__'])) $diff['__M__'][$key] = $result['__M__'];
                            // 如有不明键，则记录到'?'下的子元素里
                            if(count($result['?????'])) $diff['?????'][$key] = $result['?????'];
                            continue;
                        }
                        // 非深度模式下，类型相同值相等，数组跳过
                        if($array2[$key]==$val) continue;
                        #6
                    }
                    // 深度模式下，类型相同且值相等，非数组跳过
                    if($mode===self::DIFF_DEEP&&$array2[$key]==$val) continue;
                    #4
                    #5
                }
                // 深度模式下，类型不同但值相等，记为'?'
                if($mode===self::DIFF_DEEP&&$array2[$key]==$val){
                    $diff['?????'][$key] = $array2[$key];
                    continue;
                }
                #2
                #3
            }else{
                // 简单模式下，值相等，跳过
                if($array2[$key]==$val) continue;
                #1
            }

            // 简单模式下，值不等，记为'M'
            // 非深度模式下，类型不同但值相等，记为'M'
            // 深度模式下，类型不同且值不等，记为'M'
            // 非深度模式下，类型相同但值不等，非数组记为'M'
            // 深度模式下，类型相同但值不等，非数组记为'M'
            // 非深度模式下，类型相同但值不等，数组记为'M'
            $diff['__M__'][$key] = $array2[$key];
        }
        /**
         * 综上:
         * 简单模式下，不论类型，值等，跳过；值不等，记为M
         * 严谨模式下，类型相同，值相等，跳过；其他，记为M
         * 深度模式下，类型相同，值相等，跳过；类型相同，值不等的，数组比对内部元素，其他记为M；类型不同，值相等，记为?；类型不同，值不等，记为M
         */
        
        return $diff;
    }

    public $error_msg;

    protected
    $readonly = false,
    $modelProperties,
    $xml;

    /**  
	 * 数据闭包构造函数
     * 私有方法，只能通过静态方法self::enclose()调用
	 * 
	 * @access private
     * @param array $modelProperties       编码过的数据
	 * @param bool $readonly    是否只读
	 * @return 构造函数无返回值
	**/ 
    private function __construct(array $input, $readonly){
        $this->modelProperties = $input;
        $this->readonly = $readonly;
    }

    /**  
	 * 查键方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return bool
	**/ 
    final public function has($name){
        if(is_array($this->modelProperties)&&array_key_exists($name, $this->modelProperties)){
            return true;
        }
        return false;
    }

    /**  
	 * 写值方法
     * 只读模式下不可用
     * 不可新增值
	 * 
	 * @access public
     * @param string $name 属性名称
     * @param mixed $value 要写入的值
	 * @return object
	**/ 
    public function set($name, $value){
        if($this->readonly===false){
            if(is_array($this->modelProperties)){
                if(($name!=='__attributes')&&array_key_exists($name, $this->modelProperties)){
                    $this->modelProperties[$name] = $value;
                    $this->xml = '';
                }
                if(isset($this->modelProperties['__attributes'])&&is_array($this->modelProperties['__attributes'])&&array_key_exists($name, $this->modelProperties['__attributes'])){
                    $this->modelProperties['__attributes'][$name] = $value;
                    $this->xml = '';
                }
                return $this;
            }
            \Status::cast('modelProperties of model object must be a array.', 1461);
        }
        \Status::cast('cannot reset property of a readonly model object.', 1413);
    }    

    /**  
	 * 取值方法
	 * 
	 * @access public
     * @param string $name 属性名称
	 * @return mixed
	**/ 
    public function get($name){
        if(is_array($this->modelProperties)){
            if(($name!=='__attributes')&&isset($this->modelProperties[$name])){
                return $this->modelProperties[$name];
            }
            if(isset($this->modelProperties['__attributes'])&&is_array($this->modelProperties['__attributes'])&&isset($this->modelProperties['__attributes'][$name])){
                return $this->modelProperties['__attributes'][$name];
            }
        }
        return NULL;
    }

    /**  
	 * 追加属性，写值方法（新增）
     * 只读模式下不可用
	 * 
	 * @access public
     * @param string $name 属性名称
     * @param mixed $value 要写入的值
	 * @return object
	**/ 
    public function add($name, $value){
        if($this->readonly===false){
            if(is_array($this->modelProperties)){
                if(!array_key_exists($name, $this->modelProperties)){
                    $this->xml = NULL;
                    $this->modelProperties[$name] = $value;
                }
                return $this;
            }
            \Status::cast('modelProperties of model object must be a array.', 1461);
        }
        \Status::cast('cannot add property to a readonly model object.', 1413);
    }

    /**  
	 * 删除属性
	 * 
	 * @access public
     * @param string $name 属性名称
	 * @return object
	**/ 
    public function uns($name){
        if($this->readonly===false){
            if(is_array($this->modelProperties)){
                if(($name!=='__attributes')&&array_key_exists($name, $this->modelProperties)){
                    unset($this->modelProperties[$name]);
                }
                return $this;
            }
            \Status::cast('modelProperties of model object must be a array.', 1461);
        }
        \Status::cast('cannot remove property of a readonly model object.', 1413);
    }

    public function setAttribute($name, $value){
        if($this->readonly===false){
            if(is_array($this->modelProperties)){
                if(isset($this->modelProperties['__attributes'])&&is_array($this->modelProperties['__attributes'])){
                    $this->xml = NULL;
                    $this->modelProperties['__attributes'][$name] = $value;
                    return $this;
                }
                \Status::cast('cannot find propertiy "__attributes" of this model object.', 1461);
            }
            \Status::cast('modelProperties of model object must be a array.', 1461);
        }
        \Status::cast('cannot add property to a readonly model object.', 1413);
        return $this;
    }

    public function removeAttribute($name, $value){
        if($this->readonly===false){
            if(is_array($this->modelProperties)){
                if(isset($this->modelProperties['__attributes'])&&is_array($this->modelProperties['__attributes'])){
                    if(array_key_exists($name, $this->modelProperties['__attributes'])){
                        $this->xml = NULL;
                        unset($this->modelProperties['__attributes'][$name]);
                    }
                    return $this;
                }
                \Status::cast('cannot find propertiy "__attributes" of this model object.', 1461);
            }
            \Status::cast('modelProperties of model object must be a array.', 1461);
        }
        \Status::cast('cannot remove attribute of a readonly model object.', 1413);
    }

    /**  
	 * 提取属性数组
	 * 
	 * @access public
	 * @return array
	**/ 
    public function getArrayCopy(){
        if(is_array($this->modelProperties)){
            return $this->modelProperties;
        }
        return [
            'type'  =>  'Unknown Type',
            'value' =>  $this->modelProperties
        ];
    }

    public function serialize(){
        return serialize($this->modelProperties);
    }

    public function unserialize($serialized){
        $this->modelProperties = unserialize($serialized);
    }
    
    /**  
	 * 将属性数组转化为JSON格式的文本
	 * 
	 * @access public
     * @param bool $trim 是否压缩
     * @param string $indent 格式化缩进，默认4空格，$trim为否时有效
	 * @return string
	**/ 
    public function json_encode($trim = true, $indent = '    '){
        if($trim){
            return self::jsonEncode($this->modelProperties);
        }
        return self::jsonToJson(self::jsonEncode($this->modelProperties), false, $indent);
    }

    /**  
	 * 将属性数组转化为XML格式的文本
	 * 
	 * @access public
     * @param string $version xml版本
     * @param string $encoding 编码格式，默认为utf8
	 * @return string
	**/ 
    public function xml_encode($version = '1.0', $encoding = 'UTF-8'){
        if(empty($this->xml)){
            $this->xml = self::arrayToXml($this->modelProperties, $version, $encoding);
        }
        return $this->xml;
    }

    /**  
	 * 将属性数组转化为PHP序列化文本
	 * 
	 * @access public
	 * @return string
	**/ 
    public function str(){
        return serialize($this->modelProperties);
    }

    /**  
	 * 将属性数组转化为QueryString
	 * 
	 * @access public
     * @param string $numericPrefix 数字键前缀
     * @param bool $encodeType 是否编码
	 * @return string
	**/ 
    public function toQueryString($numericPrefix = 'arg_', $encodeType = false){
        return self::arrayToQueryString($this->modelProperties, $numericPrefix = 'arg_', $encodeType = false);
    }

    /**
	 * 比对数组改变量
     * 可接受双外部数据
	 * 
	 * @access public
     * @final
     * @param array $array      要比较的数组
     * @param array|bool $modelProperties  被比较的数组，默认为使用实例自己的de数据
     * @param string $mode      指定模式，默认为严谨模式
	 * @return array
	**/
    final public function diff(array $array, $mode = self::DIFF_STRICT){
        if(is_array($this->modelProperties)){
            return self::array_diff($this->modelProperties, $array, $mode);
        }
        return [
            '__A__' => $array,
            '__D__' => [],
            '__M__' => [],
            '?????' => $this->modelProperties
        ];
    }
}
