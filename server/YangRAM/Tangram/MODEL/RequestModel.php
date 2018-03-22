<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

// 引入相关命名空间，以简化书写
use stdClass;
use Tangram\ClassLoader;
use Tangram\MODEL\ObjectModel;
use Tangram\MODEL\InputsModel;

/**
 * @class Tangram\MODEL\Request
 * Standardized Request Model
 * 标注化请求对象模型，仿单例类
 * 实例为一个封装的数据包，由统一资源索引器（全局引用名为$NEWIDEA->RI）抢先实例化
 * 负责解读请求参数，懒读取模式节省消耗，只有当应用调用某数据时，读取器才为其解读
 * 
 * @var object      $URI	                    统一资源标识信息
 * @prop string     $URI->hostname			    主机名
 * @prop string     $URI->hostnametype          主机名类型
 * @prop array      $TRI->hostarr               主机名分段数组，域名为倒序
 * @prop int        $TRI->port                  端口号
 * @prop string     $URI->src                   原URL
 * @var object      $TRI                        为统一资源索引器优化后资源标识信息
 * @prop string     $TRI->hash                  缓存资源对象标识符
 * @prop string     $TRI->locale_channel        语言频道，仅开启语言频道后有效
 * @prop string     $TRI->pathname              优化后的URL的路径部分
 * @prop string     $TRI->qs                    优化后的URL的查询部分
 * @prop array      $TRI->patharr               优化后的URL的路径部分的数组形态（不区分大小写）
 * @prop array      $TRI->opath                 优化后的URL的路径部分及其数组形态（区分大小写）
 * @var object      $ARI                        为活动应用优化后的资源标识信息
 * @prop string     $ARI->
 * @var int         $LEN                        优化后的URL的路径部分数组化后的数组长度
 * @var string      $OS                         服务器系统信息
 * @var string      $IP                         客户端IP信息
 * @var string      $LANG                       用户请求的语言信息
 * @var array       $ADDR                       客、服两端主机信息
 * @var string      $BROWSER                    客户端浏览器（应用软件）信息
 * @var string      $HEADERS                    客户端发送的报头信息
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class RequestModel implements interfaces\model {
    use traits\magic;
    use traits\arraylike;
    
    const
    LANG_REGEXP = '/^[a-z]{2}-[a-z]{2}$/',
    DOMAIN_REGEXP = '/^[a-zA-Z][-a-zA-Z0-9]{0,62}(\.[a-zA-Z][-a-zA-Z0-9]{0,62})*$/',
    IPV4_REGEXP = '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',
    IPV4_REGEXP_STRICT = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
    IPV6_REGEXP = '/^((?:[0-9A-Fa-f]{1,4}(?::[0-9A-Fa-f]{1,4})*)?)::((?:[0-9A-Fa-f]{1,4}(?::[0-9A-Fa-f]{1,4})*)?)$/',
    IPV6_REGEXP_STD = '/^(?:[0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$/',
    IPV6_REGEXP_STRICT = '/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/'; 
    
    private static $instance = NULL;

    /**  
	 * 标注化请求对象获取方法
	 * 第一次调用时创建标注化请求对象
	 * 
	 * @access public
     * @static
	 * @return object(Tangram\MODEL\Request) 标注化请求对象
	**/ 
	public static function instance(){
		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
    }

    public static function filterTags($string){
        $string = preg_replace('/&lt;/is', '<', $string);
        $string = preg_replace('/&lt;/is', '<', $string);
        $string = preg_replace('/(&amp;|&quot;|&apos|&copy;|©|\'|"|&|)/is', '', $string);
        $string = preg_replace('/<\s*\w+[^>]*?>(.*?<\\/\w+>)?/is', '', $string);
		return trim($string);
    }

    private
    $headers = [],
    $modelProperties = [];

    /**  
	 * 标注化请求对象构造函数
	 * 将构造函数私有化以保证其实例的单一性
	 * 
	 * @access private
	 * @return 构造函数无返回值
    **/ 
    private function __construct(){
        // 初始化一个数组对象$modelProperties
        $modelProperties = [
            'URI'           =>  $this->getURI(),                       // 统一资源标识
            'TRI'           =>  new stdClass,                   // 系统资源标识
            'ARI'           =>  new stdClass                    // 应用资源标识
        ];

        /**
         * 来访URL分析
         * 1. 格式化来访URL，并将它赋值给变量$URI
         * 2. 统一来访URL的大小写为小写，并将它赋值给变量$uri
         * 3. 分别截断$URI和$uri字符串，将得到两个数组赋值给$DIR_ARRAY和$dir_array
        **/

        $URI = preg_replace('/(^\/|\/$)/', '', preg_replace('/[\\\\\/]+/', '/', $_SERVER['PHP_SELF']));
        $URI = self::filterTags($URI);
        $uri = ClassLoader::formatRemoteFileName($URI);
        $DIR_ARRAY = explode('/', $URI);
        $dir_array = explode('/', $uri);

        // 获取配置项中的主页文件名列表和语言列表
        $homepages = explode('/', ClassLoader::formatRemoteFileName(_HOMEPAGE_));
        $i18n = $GLOBALS['NEWIDEA']->LANGS;

        // 在开启多语言频道的情况下，如果字符串$dir_array[1]存在于语言列表$i18n中，则把该匹配的语言项插入到$modelProperties中，并移除$DIR_ARRAY和$dir_array的第一元素
        if(_LOCALE_CHANNELS_&&$i18n&&isset($dir_array[1])&&in_array($dir_array[1], $i18n, true)){
            $modelProperties['LANG'] = $dir_array[1];
            array_shift($DIR_ARRAY);
            array_shift($dir_array);
        }
        
        // 生成全新的URI, 并覆盖给$dir_array[0];
        if(count($dir_array)===2&&in_array($dir_array[1], $homepages)){
            // 如果数组$dir_array的长度为2，且第二个元素存在于数组$homepages之中，则重写来访URI
            $DIR_ARRAY = ['/'];
            $dir_array = ['tangram'];
            $_SERVER['REQUEST_URI'] = $modelProperties['TRI']->pathname = '';
        }else{
            $DIR_ARRAY[0] = $dir_array[0] = '';
            $DIR_ARRAY[0] = join('/', $DIR_ARRAY);     // 此项用于备份完整的新URI
            $modelProperties['TRI']->pathname = join('/', $dir_array);
            $dir_array[0] = 'tangram';                                  // 此项用于备份应用id
        }

        // 提取query string，并整理应用资源标识和语言频道
        if($_SERVER['QUERY_STRING']){
            $modelProperties['TRI']->qs = $_SERVER['QUERY_STRING'];
            $array = explode('?', ClassLoader::formatRemoteFileName($_SERVER['REQUEST_URI']));
            $modelProperties['TRI']->locale_channel = preg_replace('/\/$/', '',str_replace($modelProperties['TRI']->pathname, '', $array[0]));
        }else{
            $modelProperties['TRI']->qs = '';
            $modelProperties['TRI']->locale_channel = preg_replace('/\/$/', '', str_replace($modelProperties['TRI']->pathname, '',  ClassLoader::formatRemoteFileName(rawurldecode($_SERVER['REQUEST_URI']))));
        }
        
        // http method and directory array length
        $modelProperties['METHOD'] = strtoupper($_SERVER['REQUEST_METHOD']);
        // 部分服务器和客户端不支持post和get以外的方法，可以使用传参的形式
        if(_HTTP_METHOD_PARAM_&&($modelProperties['METHOD']==='POST')){
            if(isset($_POST[_HTTP_METHOD_PARAM_])){
                $http_method = strtoupper($_POST[_HTTP_METHOD_PARAM_]);
                if(in_array($http_method, ['GET','DELETE','HEAD','OPTIONS','PUT','PATCH','UPDATE'])){
                    $modelProperties['METHOD'] = $http_method;
                }
                unset($_POST[_HTTP_METHOD_PARAM_]);
            }
		}
        // $modelProperties['METHOD'] = strtoupper($_SERVER['REQUEST_METHOD']);
        $modelProperties['LENGTH'] = count($DIR_ARRAY);

        // 将（可能）调整后的$dir_array、$DIR_ARRAY以及计算出的hash值存入$modelProperties['TRI']
        $modelProperties['TRI']->patharr  = $dir_array;
        $modelProperties['TRI']->opath = $DIR_ARRAY;
        $modelProperties['TRI']->hash  = md5(HOST.$modelProperties['TRI']->locale_channel.$modelProperties['TRI']->pathname.'?'.$modelProperties['TRI']->qs);

        // 将对象$modelProperties引用至实例的$modelProperties属性，以便外部访问
        $this->modelProperties = $modelProperties;

        define('HTTP_HOST', (_OVER_SSL_ ? 'https://' : 'http://').HOST);
        define('HTTP_SOCKET', (_OVER_SSL_ ? 'wss://' : 'ws://').HOST);

        // 定义完整版的全局url常量
        define('__URL', HTTP_HOST.__BURL__);                                // 系统根目录远程路径名
        define('__CHN', HTTP_HOST.$modelProperties['TRI']->locale_channel);            // 当前请求地址所在的语言频道的URL
        define('__DIR', HTTP_HOST.explode('?', $modelProperties['URI']->src)[0]);      // 当前请求地址的远程路径名
        define('__URI', HTTP_HOST.$modelProperties['URI']->src);                       // 当前请求地址改写后的完整态
    }

    /**
     * 获取资源标识对象
     * 
     * @access private
     * @return object(stdClass) 对象化的URI信息
    **/
    private function getURI(){
        $info = new stdClass();
        $info->hostname = preg_replace('/\:\d+/', '',HOST);
        if(preg_match(self::DOMAIN_REGEXP, $info->hostname)){
            if($info->hostname==='localhost'){
                $info->hostnametype = 'localhost';
            }else{
                $info->hostnametype = 'domain';
            }
            $array = explode('.', $info->hostname);
            $info->hostarr = array_reverse($array);
            array_shift($array);
            $info->spuerhost = implode('.', $array);
        }elseif(preg_match(self::IPV4_REGEXP_STRICT, $info->hostname)){
            $info->hostnametype = 'IPV4';
            $info->hostarr = explode('.', $info->hostname);
            $info->spuerhost = '';
        }elseif(preg_match(self::IPV6_REGEXP_STRICT, $info->hostname)){
            $info->hostnametype = 'IPV6';
            $info->hostarr = explode(':', $info->hostname);
            $info->spuerhost = '';
        }else{
            # 遇到系统不能识别的主机名类型，理应报个错先
        }
        $info->port = PORT;
        $info->src = '/' . preg_replace('/(^\/|\/$)/', '', preg_replace('/[\\\\\/]+/', '/', $_SERVER['REQUEST_URI']));
        return $info;
    }


    /**  
	 * 更新Tangram\MODEL\Request实例，仅一次有效
	 * 
	 * @access public
     * @param bool $useCustomRouter
	 * @return object(Tangram\MODEL\Request)
    **/ 
    public function update($appid, $depth, $useCustomRouter = false, $route = 0, $dirname = '/dirname/dirname/dirname', array $defaults = []){
        // 检查是否已经更新过，防止二次更新
        if(isset($this->modelProperties['INPUTS'])&&is_a($this->modelProperties['INPUTS'], '\Tangram\MODEL\InputsModel')){
            return $this;
        }

        $this->modelProperties['ARI']->appid = $this->modelProperties['TRI']->patharr[0] = $appid;

        // 如果使用自定义路由
        if($useCustomRouter){
            define('RI_CURR', $this->modelProperties['ARI']->route = $route);
            $this->modelProperties['ARI']->dirname = $dirname;
            $this->modelProperties['ARI']->depth = $depth;
            $this->modelProperties['ARI']->patharr = array_slice($this->modelProperties['TRI']->patharr, 1 + $depth);
            $inputs = $this->modelProperties['INPUTS'] = (new InputsModel($defaults))->stopAttack();
        }else{
            define('RI_CURR', $this->modelProperties['ARI']->route = -1);
            $this->modelProperties['ARI']->patharr = $arr = array_slice($this->modelProperties['TRI']->patharr, 1 + $depth);
            $this->modelProperties['ARI']->dirname = '/'.implode('/', $arr);
            $this->modelProperties['ARI']->depth = $depth;
            $inputs = $this->modelProperties['INPUTS'] = (new InputsModel())->stopAttack();
        }

        
        if(array_key_exists('LANG', $this->modelProperties)){
            define('REQUEST_LANGUAGE', $this->modelProperties['LANGUAGE'] = $GLOBALS['NEWIDEA']->LANGUAGE = $this->modelProperties['LANG']);
        }else{
            define('REQUEST_LANGUAGE', $this->modelProperties['LANGUAGE'] = $GLOBALS['NEWIDEA']->LANGUAGE = $this->modelProperties['LANG'] = $this->getLANG($inputs));
        }
        
        return $this;
    }

    /**
     * 魔术方法，用来读取对象本身不存在或无法直接读取的属性值
     * 借用此方法，可以实现PHP对象本来不支持的只读功能
     * 也可直接作为普通方法调用
     * 
     * @access public
     * @param string $name 需要读取的属性名
     * @return mixed 返回计算后的属性值
    **/
    public function get($name){
        // 因此方法只会映射到实例对象不存在或访问不到的属性
        // 所以对于对象而且，读取其属性存在如下优先关系
        // 直接公共属性 > $this->modelProperties['INPUTS']对象的属性 > HEADERS属性 > get前缀方法相关的属性> $this->modelProperties数组中的元素

        // 如果$this->modelProperties数组中存在以该属性名作为键名的元素，则返回该元素的键值
        if(isset($this->modelProperties[$name])){
            return $this->modelProperties[$name];
        }

        // 如果属性名为'HEADERS'
        if($name=='HEADERS'){
            // 如果之前读取过，则直接返回之前的缓存值
            if(array_key_exists('HEADERS', $this->modelProperties)){
                return $this->modelProperties['HEADERS'];
            }
            // 首次读取，调用$this->getHeaders()进行计算
            return $this->modelProperties['HEADERS'] = join("\r\n", $this->getHeaders());
        }

        // 如果存在一个含有get前缀的方法与该属性名相关，则调用并返回该方法的回值
        if(method_exists($this, 'get'.$name)){
            $methodname = 'get'.$name;
            return $this->$methodname();
        }

        // 最后，还需排查一下标准表单数据对象，看看它是否存在该属性，如果有则借用之
        $inputs = $this->modelProperties['INPUTS'];
        if(isset($inputs->$name)){
            return $inputs->$name;
        }

        // 实在没有的话，只能返回NULL了
        return NULL;
    }

    /**
     * 获得请求语言的方法
     * 
     * @access private
     * @param object(Tangram\MODEL\InputsModel) $inputs
     * @return string
    **/
    private function getLANG($inputs){
        // 检查语言请求
        // 根据关键字所处位置的级别检查
        // 语言频道值 > Tangram\MODEL\InputsModel(POST > GET > COOKIE > DEFAULT) > 浏览器设置 > 系统配置项
        // lang > language

        if(isset($inputs->lang)&&preg_match(self::LANG_REGEXP, $inputs->lang)){
            return $inputs->lang;
        }
        
        if(isset($inputs->language)&&preg_match(self::LANG_REGEXP, $inputs->language)){
            return $inputs->language;
        }

        if(isset($_SESSION['language'])&&preg_match(self::LANG_REGEXP, $_SESSION['language'])){
            return $_SESSION['language'];
        }

        if(isset($_SESSION['lang'])&&preg_match(self::LANG_REGEXP, $_SESSION['lang'])){
            return $_SESSION['lang'];
        }

        return isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : _LANG_;
    }

    /**
     * 获得客户端IP的方法
     * 
     * @access private
     * @return string
    **/
    private function getIP() {
        if(array_key_exists('IP', $this->modelProperties)){
            return $this->modelProperties['IP'];
        }
		if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
			return $_SERVER['HTTP_CDN_SRC_IP'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && (preg_match(self::IPV4_REGEXP, $_SERVER['HTTP_CLIENT_IP'])||preg_match(self::IPV6_REGEXP, $_SERVER['HTTP_CLIENT_IP']))) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
			foreach ($matches[0] AS $xip) {
				if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
					return $xip;
				}
			}
		}
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * 获得客、服两端主机名的方法
     * 
     * @access private
     * @return string
    **/
    private function getADDR() {
        if(array_key_exists('ADDR', $this->modelProperties)){
            return $this->modelProperties['ADDR'];
        }
        if(isset($_SERVER['SERVER_ADDR'])){
			$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
		}elseif(isset($_SERVER['LOCAL_ADDR'])){
			$SERVER_ADDR = $_SERVER['LOCAL_ADDR'];
		}else{
			$SERVER_ADDR = getenv('SERVER_ADDR');
		}
        return [
            'FROM'  =>  $_SERVER['REMOTE_ADDR'],
            'TO'    =>  $SERVER_ADDR
        ];
    }

    /**
     * 获得客户端操作系统的方法
     * 
     * @access private
     * @return string
    **/
    private function getOS(){
        if(array_key_exists('OS', $this->modelProperties)){
            return $this->modelProperties['OS'];
        }
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$OS = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/win/i',$OS)) {
				return 'Windows';
			}elseif (preg_match('/mac/i',$OS)) {
				return 'MAC';
			}elseif (preg_match('/linux/i',$OS)) {
				return 'Linux';
			}elseif (preg_match('/unix/i',$OS)) {
				return 'Unix';
			}elseif (preg_match('/bsd/i',$OS)) {
				return 'BSD';
			}else {
				return 'Other';
			}
		}
		return NULL;
    }
    
    /**
     * 获得客户浏览器信息的方法
     * 
     * @access private
     * @return string
    **/
    private function getBROWSER(){
        if(array_key_exists('BROWSER', $this->modelProperties)){
            return $this->modelProperties['BROWSER'];
        }
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$br = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/MSIE/i',$br)) {
				return 'MSIE';
			}elseif (preg_match('/Firefox/i',$br)) {
				return 'Firefox';
			}elseif (preg_match('/Chrome/i',$br)) {
				return 'Chrome';
			}elseif (preg_match('/Safari/i',$br)) {
				return 'Safari';
			}elseif (preg_match('/Opera/i',$br)) {
				return 'Opera';
            }elseif (preg_match('/YangRAM/i',$br)) {
				return 'YangRAM';
			}else {
				return 'Other';
			}
		}
        return NULL;
    }
    
    /**
     * 获得客户请求报头信息的方法
     * 
     * @access public
     * @return array
    **/
    public function getHeaders(){
        if(array_key_exists('HEADERS', $this->modelProperties)){
            return $this->headers;
        }
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $this->headers[] = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) . ":\s" . $value;
            }
        }
        return $this->headers;
    }

    /**
     * 将对象中存放的主要数据数组化的方法
     * 
     * @access public
     * @return array
    **/
    public function getArrayCopy(){
        return array_merge([
            'LANG'      =>  $this->modelProperties['LANG'],
            'HEADERS'   =>  $this->getHeaders(),
            'OS'        =>  $this->getOS(),
            'IP'        =>  $this->getIP(),
            'BROWSER'   =>  $this->getBROWSER(),
            'ADDR'      =>  $this->getADDR(),
            'INPUTS'      =>  $this->modelProperties['INPUTS']->getArrayCopy()
        ],
        $this->modelProperties['TRI']->opath);
    }

    /**
     * 将对象中存放的主要数据字串化的方法
     * 
     * @access public
     * @return string
    **/
    public function str(){
        $string = 'Request:';
        foreach($this->modelProperties as $index=>$item){
            if(is_scalar($item)){
                $string .= PHP_EOL.$index.': '.$item;
            }
            if(is_array($item)){
                $string .= PHP_EOL.$index.': '.ObjectModel::arrayToQueryString($item);
            }
            if(is_object($item)){
                if(is_a($item, 'Tangram\MODEL\InputsModel')){
                    $string .= PHP_EOL.$item->str();
                }else{
                    $array = get_object_vars($item);
                    $string .= PHP_EOL.$index.': '.ObjectModel::arrayToQueryString($array);
                }
            }
        }
        return $string;
    }

    /**
	 * set()方法用来使私有属性在核心态时可写
	 * 
	 * @access public
	 * @param string $name 属性名称
	 * @param boolean $value 属性值，如果给一个非布尔型的值，函数会自动将其转化为布尔型
	 * @return false
	**/
	public function set($name, $value){
		return false;
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
        if($this->get($name, $this->modelProperties)){
            return true;
        }
        return false;
    }

    /**  
	 * 删键方法
	 * 
	 * @access public
     * @final
     * @param string $name
	 * @return 回调函数无返值
	**/ 
    final public function uns($name){
        return false;
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

    public function __call($name, $args){
        return $this->modelProperties['INPUTS']->__call($name, $args);
    }
}
