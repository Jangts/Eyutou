<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

/**
 * @class Tangram\MODEL\FormDataModel
 * StandardizedForm Submission Data
 * 标准表单数据对象
 * 一个通过安全检查和专用通道重写$_GET+$_POST+$_COOKIE的整合对象
 * 
 * 因此数据对象的属性名不定，这里不作一一备解
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class FormDataModel extends ObjectModel {
    /**
     * 分析非GET和POST方法的输入
     * 
     * @access private
     * @static
     * @return void
    **/
    private static function parseInputRaw() {
        // 读取传入数据
        $input = file_get_contents('php://input');

        // 获取CONTEN-TYPE中的formdata分隔符
        if(empty($_SERVER['CONTENT_TYPE'])||!preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches)||!count($matches)){           
            // 如果不存在分隔符，则可认为是QueryString格式的数据
            // 直接解析并返回
            parse_str(urldecode($input), $_POST);
            return;
        }
        
        // 赋值分隔符变量
        $boundary = $matches[1];
        // 分断字符串为数组，并删除最后的元素
        $blocks = preg_split("/-*$boundary/", $input);
        // var_dump($blocks);
        array_pop($blocks);

        foreach ($blocks as $block) {
            // 修建值
            
            // var_dump($block);
            if (empty($block)){
                // 跳过控制
                continue;
            }
            
            if (strpos($block, 'filename=') !== FALSE){
                if(preg_match("/name=\"([^\"]*)\[\]\".*filename=\"([^\"].*?)\".*Content-Type:\s+(.*?)[|\r|\r]+([^\r].*)?$/s", $block, $matches)) {
                    // 检查是否存在文件组
                    if(empty($_FILES[$matches[1]])||!is_array($_FILES[$matches[1]]['name'])){
                        $_FILES[$matches[1]] = [
                            'name' => [],
                            'type' => [],
                            'blob' => [],
                            'size' => []
                        ];
                    }
                    $_FILES[$matches[1]]['name'][] = $matches[2];
                    $_FILES[$matches[1]]['type'][] = $matches[3];
                    $_FILES[$matches[1]]['tmp_name'][] = nuLL;
                    $_FILES[$matches[1]]['blob'][] = $matches[4];
                    $_FILES[$matches[1]]['size'][] = strlen($matches[4]) - 5;
                    $_FILES[$matches[1]]['error'][] = 0;
                    continue;
                }
                if (preg_match("/name=\"([^\"]*)\".*filename=\"([^\"].*?)\".*Content-Type:\s+(.*?)[|\r|\r]+([^\r].*)?$/s", $block, $matches)) {
                    // 检查是否存在文件
                    $_FILES[$matches[1]] = [
                        'name'      =>  $matches[2],
                        'type'      =>  $matches[3],
                        'tmp_name'  =>  nuLL,
                        'blob'      =>  $matches[4],
                        'size'      =>  strlen($matches[4]) - 5,
                        'error'     =>  0
                    ];
                    continue;
                }
            }
            // 提取一般量
            $block = str_replace(PHP_EOL, '', $block);
            if(preg_match('/name=\"([^\"]*)\"([^\r].*)?$/s', $block, $matches)){
                $_POST[$matches[1]] = trim($matches[2]);
            }
        }
    }

    /**
     * 过滤请求中含有的SQL注入威胁
     * 
     * @access public
     * @static
     * @param int $type 指定要排查的数据，默认为$_GET、$_POST、$_COOKIE全部过滤
     * @return string|array
    **/
    public static function stopAttacks($type = 0) {
		$result = true;
		switch ($type) {

            // 仅排差$_GET，取单词get的字母数3
			case 3:
			foreach ($_GET as $key => $val) {
            	$_GET[$key] = self::filterSqlWords($val);
        	}
            break;
            
            // 仅排差$_POST，取单词post的字母数4
			case 4:
			foreach ($_POST as $key => $val) {
                $_POST[$key] = self::filterSqlWords($val);
            }
            break;
            
            // 仅排差$_COOKIE，取单词cookie的字母数6
			case 6:
			foreach ($_COOKIE as $key => $val) {
                $_COOKIE[$key] = self::filterSqlWords($val);
            }
			break;
            
            // 排查全部
			default:
			foreach ($_GET as $key => $val) {
            	$_GET[$key] = self::filterSqlWords($val);
        	}
			foreach ($_POST as $key => $val) {
                $_POST[$key] = self::filterSqlWords($val);
            }
			foreach ($_COOKIE as $key => $val) {
                $_COOKIE[$key] = self::filterSqlWords($val);
            }
			break;
		}
    }

    /**
     * 检查文本中是否含有SQL注入
     * 
     * @access public
     * @static
     * @param string|array $string 要检查的文本，可以使用数组传入多个文本
     * @return bool
    **/
	public static function checkSqlWords($string){
        if(is_array($string)){
			$string = implode('<@TNI_SCAN_GAP>', $string);
		}

		if (preg_match('/\\b(and|or)\\s.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is',$string,$matches) == 1){
			//var_dump($string, $matches);
			return false;
        }
		return true;
    }

    /**
     * 过滤文本中含有的SQL注入威胁
     * 
     * @access public
     * @static
     * @param string|array $string 要排查的文本，可以使用数组传入多个文本
     * @return string|array
    **/
	public static function filterSqlWords($string){
        if(is_array($string)){
			$string = implode('<@TNI_SCAN_GAP>', $string);
		}

		$string = preg_replace('/\\b(and|or)\\s.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is','',$string);
		
		$array = explode('<@TNI_SCAN_GAP>', $string);
		if(count($array)>1){
			return $array;
		}
		return trim($string);
    }

    // 定义三个对象，用来记录未合并的cookie、get、post
    public $__default, $__cookie, $__get, $__post;

    /**  
	 * 标准表单数据对象构造函数
	 * 将构造函数私有化以保证其实例的单一性
	 * 
	 * @access public
	 * @return 构造函数无返回值
    **/ 
    public function __construct(array $defaults = []){
        if(!in_array(strtoupper($_SERVER['REQUEST_METHOD']), ['GET', 'POST'])){
            // 重写$_POST和$_FILES
            self::parseInputRaw();
        }
        // 排差外部数据中的威胁
        if(!get_magic_quotes_gpc()){
            $_COOKIE = array_map("addslashes", $_COOKIE);
            $_GET = array_map("addslashes", $_GET);
            $_POST = array_map("addslashes", $_POST);
        }

        // // 创建一个空数组，用来存放对象的各个属性值
        // $input = [];
        // // 将路由默认值写入$input
        // foreach($defaults as $key=>$val){
        //     $this->modelProperties[$key] = $val;
        // }
        // // 将整理后的Cookie键值组写入$input
        // $this->arrangeCookies($input);
        // // 将整理后的Get键值组写入$input
        // $this->arrangeGets($input);
        // // 将整理后的Post键值组写入$input
        // $this->arrangePosts($input);
        // // 将整理后的$input赋值给对象的$input属性
        // $this->modelProperties = $input;

        // 以上代码整理为一句
        $this->modelProperties = $this->arrangePosts($this->arrangeGets($this->arrangeCookies($this->__default = $defaults)));
    }

    /**
     * 整理COOKIE传入的数据
     * 
     * @access private
     * @param array $input 整理前的$input;
     * @param array 即整理后的$input;
    **/
    private function arrangeCookies(array $input){
        $__cookie = [];
        foreach ($_COOKIE as $key => $val) {
            $__cookie[$key] = $input[$key] = $val;
        }
        $this->__cookie = $__cookie;
        return $input;
    }

    /**
     * 整理GET传入的数据
     * 
     * @access private
     * @param array $input 整理前的$input;
     * @param array 即整理后的$input;
    **/
    private function arrangeGets(array $input){
        $__get = [];
        foreach ($_GET as $key => $val) {
            $__get[$key] = $input[$key] = $val;
        }
        $this->__get = $__get;
        return $input;
    }

    /**
     * 整理POST传入的数据
     * 
     * @access private
     * @param array $input 整理前的$input;
     * @param array 即整理后的$input;
    **/
    private function arrangePosts(array $input){
        $__post = [];
        foreach ($_POST as $key => $val) {
            $__post[$key] = $input[$key] = $val;
        }
        $this->__post = $__post;
        return $input;
    }

    /**
     * SQL防注入过滤方法
     * 
     * @access public
     * @param bool $strict 是否严格剔除，如果此项为真，则含有非法词汇的项将被踢出，否则只是把语句中的非法词汇剔除
     * @return object Tangram\MODEL\FormDataModel
    **/
    public function stopAttack($strict = false){
        // 如果开启剔词状态，这直接将含有非法词汇的键值组剔除
        if($strict){
            foreach ($this->modelProperties as $key => $val) {
                // 使用Tangram\CTRLR\RDBQuerier类的静态方法filterSqlWords()检查是否含有SQL非法字符，如果有则删掉该项
                if(self::checkSqlWords($val)==false){
                    unset($this->modelProperties[$key]);
                }
            }
            return $this;
        }
        // 将数据中的非法词汇直接删掉
        foreach ($this->modelProperties as $key => $val) {
            // 使用Tangram\CTRLR\RDBQuerier类的静态方法filterSqlWords()过滤掉SQL非法字符
            $this->modelProperties[$key] = self::filterSqlWords($val);
        }
        return $this;
    }

    /**
     * 获取数据插入值数组
     * 通过将目标数组（$defaults）与此对象数据（$this->modelProperties）进行比较得出合适的插入值
     * 会将$this->modelProperties中存在而$defaults中不存在的键去掉
     * $this->modelProperties中不存在的键值对则继续使用$defaults的默认值
     * 最终返回一个完整的与$defaults有相同索引键（字段）的新数组
     * 
     * @access public
     * @param array $defaults 一组含有单行数据的所需的所有键及其默认值的数组
     * @return array
    **/
    public function getInsertArray(array $defaults){
        return self::correctArrayByTemplate($this->modelProperties, $defaults);
    }

    /**
     * 获取数据变更值数组
     * 通过将目标数组（$olddata）与此对象数据（$this->modelProperties）进行比较得出合适的更新值
     * 会将$this->modelProperties中存在而$olddata中不存在的键值组去掉
     * $this->modelProperties中不存在，但$olddata存在的键值组也去掉
     * $this->modelProperties和$defaults中都存在，但值一样的键值组也去掉
     * 最终返回$this->modelProperties相对$olddata的该变量
     * 
     * @access public
     * @param array $olddata 一组含有单行数据的所需的所有键值的数组
     * @return array
    **/
    public function getUpdateArray(array $olddata){
        $diff = $this->diff($olddata, self::DIFF_SIMPLE);
        return $diff['__M__'];
    }

    public function str(){
        $string = 'ORIGIN: ' . self::arrayToQueryString($this->__get, '');
        $string .= PHP_EOL.'DEFAULTS: ' . self::arrayToQueryString($this->_defaults, '');
        $string .= PHP_EOL.'COOKIES: ' . self::arrayToQueryString($this->__cookie, '');
        $string .= PHP_EOL.'POSTDATA: ' . self::arrayToQueryString($this->__post, '');
        return $string;
    }
    
}
