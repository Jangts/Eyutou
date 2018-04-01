<?php
// 核心控制模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\CTRLR;

// 引入相关命名空间，以简化书写
use Request;
use SESSION;
use App;
use Tangram\MODEL\RouteCollection;
use Tangram\MODEL\ApplicationPermissions;
use Tangram\CACHE\Resource;


/**
 * @class Tangram\CTRLR\ResourceIndexer
 * Uniform Resource Indexer, the Bus Router
 * 统一资源索引器，俗称总路由器
 * 仿单例类，由总控单元（全局引用名为$NEWIDEA）抢先实例化
 * 为进程生成一个Tangram\MODEL\Request（标准化请求）实例，并解析该请求，以得到子应用ID和路由代号
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class ResourceIndexer {
    protected static $instance = NULL, $stdhost = NULL, $res = NULL;

    /**  
	 * 资源索引器实例获取方法
	 * 第一次调用时创建资源索引器实例
	 * 
	 * @access public
     * @static
	 * @return object(Tangram\CTRLR\ResourceIndexer) 统一资源索引器
	**/ 
    public static function instance(){
		if(self::$instance === NULL){
			self::$instance = new static;
		}
		return self::$instance;
    }
    
    /**  
	 * 默认域名获取方法
	 * 第一次调用时创建标注化请求对象
	 * 
	 * @access public
     * @static
	 * @return object(Tangram\MODEL\Request) 标注化请求对象
	**/
    public static function stdhost(){
        // 判断是否第一次被调用
        if(self::$stdhost===NULL){
            if(_PRIMARY_DOMAIN_){
                if(stripos(HOST, 'www.')===0){
                    // 返回主域名的'www'子域名
                    self::$stdhost = 'www.'.strtolower(_PRIMARY_DOMAIN_);
                }else{
                    // 直接返回主域名
                    self::$stdhost = strtolower(_PRIMARY_DOMAIN_);
                }
            }else{
			    // 没有指定主域名，则返回当前域名
                self::$stdhost = HOST;
            }
        }

        // 返回默认域名
		return self::$stdhost;
    }

    /**  
	 * 默认域名（如果有的话）获取方法
	 * 第一次调用时创建标注化请求对象
	 * 
	 * @access public
     * @static
	 * @return object(Tangram\MODEL\Request) 标注化请求对象
	**/
    public static function defai(){
        if(isset($_SERVER['DOMAIN_NAMES'][HOST])&&isset($_SERVER['DOMAIN_NAMES'][HOST]['appid'])){
            return $_SERVER['DOMAIN_NAMES'][HOST]['appid'];
        }
        if(_DEFAULT_APP_){
            return _DEFAULT_APP_;
        }
        return 'tangram';
    }

     /**  
	 * 资源委托缓存方法
	 * 因核心模块先于活动应用运行，故应用可将较为长时效的资源委托给核心模块来缓存，从而获得更快的访问速度
	 * 
     * @access public
     * @static
     * @param object $data 
     * @param int $time 有效时间，单位为秒，默认是0秒，即永远
	 * @return ResourceIndexer 资源索引器
	**/ 
    public static function delegate($data, $time = 0){
		return self::$res->update($data, $time);
	}

    public $ROUTE, $COLUMN;

    /**  
	 * 统一资源索引器构造函数，仅一次有效
     * 将构造函数保护起来以保证其实例的单一性
	 * 
	 * @access private
	 * @return 构造函数无返回值
    **/ 
    private function __construct(){
        // 初始化标注化请求对象，并将完整路径、路径分段数组和主机信信息提出
        $request = Request::instance();
        $pathname = $request->TRI->pathname;
        $patharr = $request->TRI->patharr;
        $stdhost = self::stdhost();

        // 检查是否有缓存，如果有则直接响应给客户端，否则返回false
        // ……
        // 初始化Resource
        // 实例化一个Resource
        // 尝试输出资源（如果有的话）
        self::$res = Resource::config($request)->render();

        // 检查是否为标准接口
        $temporary = $this->matchStandardAPI($pathname, $patharr, $request, $stdhost);

        // 如果非标准接口，则进行默认路由表匹配
        // 如果返回路由表编号，则进行该路由表的匹配
        if($temporary['map']===self::NOT_MATCH){

            // 检查是否启用且适用于默认RESTful API
            if($stdhost===HOST&&_USE_REST_API_){
                $temporary = $this->matchDefaultREST($pathname, $patharr, $request, $stdhost);
            }
            
            RouteCollection::config();
            while ($temporary['map']>=self::NOT_MATCH){
                $temporary = $this->matchRouteMap($temporary['map'], $pathname, $patharr, $request, $stdhost);
            }
        }
        
        setcookie('language', REQUEST_LANGUAGE, time()+315360000, '/', HOST, _OVER_SSL_, true);
        if(isset($temporary['app'])){
            define('APPID', $temporary['app']);
            define('ROUTE', $temporary['map']);
        }else{
            new StatusProcessor(404, true);
        }
    }

    private function checked($patharr, $request, $mapid, $index, $callback){
        if((isset($patharr[$index])&&$patharr[$index]!=='')){
            $appid = $patharr[$index];
            $route = abs($mapid);
        }else{
            list($mapid, $appid, $route, $index) = $callback($index);
        }
        $request->update($appid, $route, $index);
        return [
            'map'       =>  $mapid,
            'app'       =>  $appid
        ];
    }

    private function checkCLITestAPI($patharr, $request){
        if (defined('_CLI_MODE_')) {
            SESSION::init('_test_session_id_');
            echo "Unit Test In Cli Mode\r\n";

            if (empty($_SESSION['username'])) {
                fwrite(STDOUT, "Please input username:\r\n");
                if ($username = trim(fgets(STDIN))) {
                    $_SESSION['username'] = $username;
                } else {
                    $_SESSION['username'] = 'system';
                }
            }

            if (empty($_SESSION['language'])) {
                fwrite(STDOUT, "Please input request language:\r\n");
                if ($language = trim(fgets(STDIN))) {
                    $_COOKIE['language'] = $_SESSION['language'] = $language;
                } else {
                    $_COOKIE['language'] = $_SESSION['language'] = REQUEST_LANGUAGE;
                }
            } else {
                $_COOKIE['language'] = $_SESSION['language'];
            }

            return $this->checked($patharr, $request, self::STD_REST, 2, function($index){
                $appid = 0;
                while(empty($appid)){
                    fwrite(STDOUT,"Please specify a subapp (press the APPID):\r\n");
                    $appid=trim(fgets(STDIN));
                }
                return [self::STD_REST, $appid, abs(self::STD_REST), $index];
            });
        } else {
            new StatusProcessor(1402, '', 'Test api only runs in CLI mode.', true);
        }
    }

    private function checkIPCRequestAPI($patharr, $request, $index){
        // 读取两端主机信息
            $index ++;
            $addr = $request->ADDR;
            // 如果客、服两端的主机地址一致，且请求中含有私有会话标识（private_session_id），则断定为合法交互
            if($addr['FROM']===$addr['TO']&&isset($_COOKIE['private_session_id'])&&preg_match('/^[0-9a-z]{32}$/', $_COOKIE['private_session_id'])){
                // 初始化会话
                SESSION::init($key = $_COOKIE['private_session_id']);
                if((isset($patharr[$index])&&$patharr[$index]!=='')){
                    // 尝试读取本地伪post，如果存在，则改写$_POST的值
                    $filename = RUNPATH_CA.$patharr[$index].'/ipc/private_formdata_json/'.$key.'.ipcmsg';
                    if(is_file($filename)){
                        if($array = json_decode(file_get_contents($filename), true)){
                            $_POST = array_map("addslashes", $array);
                        }
                        // 缓存数据使命达成，则销毁之以免浪费空间
                        unlink($filename);
                        $sp = new StatusProcessor(200, '', 'Read IPC formdata from file ['.$filename.'].');
                        $sp->log();
                    }else{
                        $sp = new StatusProcessor(1402, '', 'IPC must posted formdata.');
                        return $sp->respond(StatusProcessor::JLOG);
                    }
                    $_SESSION['username'] = 'system';
                    return $this->checked($patharr, $request, self::STD_IPC, $index, function($index){
                        return [self::SYS_ERROR, 'tangram', 400, 0];
                    });
                }
                // 未明确交互应用的ID
                $sp = new StatusProcessor(1402, '', 'IPC needs specify an application.');
                return $sp->respond(StatusProcessor::JLOG);
            }
            // 否则抛出相应异常
            $sp = new StatusProcessor(1402, '', 'IPC must be a private session.');
            return $sp->respond(StatusProcessor::JLOG);
    }

    /**  
	 * 与标准接口格式进行匹配
	 * 
	 * @access private
     * @param string $pathname
     * @param array $patharr
     * @param object(Tangram\MODEL\Request) $request
     * @param string $stdhost
	 * @return array 一个数组格式的路由表
    **/ 
    private function matchStandardAPI($pathname, $patharr, $request, $stdhost){
        // 用来比较的目录名需要前缀当前访问域名
        // 而用来被比较的目录名则前缀主域名（如果有的话）
        $pathname = $pathname . '/';
        
        if(stripos($pathname, '/:test/')===0){
            return $this->checkCLITestAPI($patharr, $request);
        }

        // 标准开放路由仅支持主域名访问
        $pathname = HOST. $pathname;

        // 子域名形式
        if(_STD_API_DOMAIN_){
            define('_STD_API_', _STD_API_DOMAIN_.'.'.$stdhost.'/');
            $index = 1;
        }
        // 虚拟目录形式
        // 使用了子域名形式的标准api之后，目录形式的会失效
        elseif(_STD_API_DIR_){
            define('_STD_API_', $stdhost.'/'._STD_API_DIR_.'/');
            $index = count(explode('/', preg_replace('/(^\/|\/$)/', '', preg_replace('/[\\\\\/]+/', '/', _STD_API_DIR_)))) + 1;
        }else{
            new Status(1402, '', 'Must Have a Standard API Configuration', true);
        }

        // IPC接口
        if(stripos($pathname, _STD_API_.':ipc/')===0){
            return $this->checkIPCRequestAPI($patharr, $request, $index);
        }

        // 标准API
        if(stripos($pathname, _STD_API_)===0){
            SESSION::init();
            return $this->checked($patharr, $request, self::STD_MVC, $index, function($index){
                $index--;
                if(isset($_GET['app'])&&$_GET['app']!==''){
                    return [self::STD_MVC, $_GET['app'], self::STD_MVC,  $index];
                }elseif(isset($_SERVER['DOMAIN_NAMES'][HOST])&&isset($_SERVER['DOMAIN_NAMES'][HOST]['appid'])){
                    return [self::STD_MVC, $_SERVER['DOMAIN_NAMES'][HOST]['appid'], abs(self::STD_MVC), $index];
                }else{
                    return [self::SYS_ERROR, 'tangram', 400, 0];
                }
            });
        }
        return ['map' => self::NOT_MATCH];
    }

    /**  
	 * 与路由总表进行匹配
	 * 
    **/ 
    private function matchDefaultREST($pathname, $patharr, $request, $stdhost){
        if(stripos($pathname, strtolower(A_RPN))===0){
            SESSION::init();
            if((count($patharr)>3&&$patharr[2]!=='')){
                $appid = $patharr[2];
                $request->update($appid, abs(self::STD_REST), 2, __aurl__.$appid);
                return [
                    'map'       =>  self::STD_REST,
                    'app'       =>  $appid
                ];
            }
            $request->update('tangram', 400, 0);
            return [
                'map'       =>  self::SYS_ERROR,
                'app'       =>  'tangram'
            ];
        }
        return ['map' => self::NOT_MATCH];
    }

    /**  
	 * 与路由总表进行匹配
	 * 
	 * @access private
     * @param int $map
     * @param string $pathname
     * @param array $patharr
     * @param object(Tangram\MODEL\Request) $request
     * @param string $stdhost
	 * @return array 一个数组格式的路由表
    **/ 
    private function matchRouteMap($map, $pathname, $patharr, $request, $stdhost){
        $map = new RouteCollection($map, $stdhost, $request->URI->spuerhost);
        $item = $map->match($pathname);
        if($item['STATE']===2){
            return [
                'map'       =>  $item['HANDLER']
            ];
        }elseif($item['STATE']===1){
            SESSION::init();
            $request->update($item['HANDLER'], $item['ROUTE'], $item['DEPTH'], $item['DIRNAME'], $item['DEFAULTS']);
            return [
                'map'       =>  self::DIR_MATCH,
                'app'       =>  $item['HANDLER']
            ];
        }else{
            if(($pathname === '')&&isset($_GET["error"])){
                $app    =   'tangram';
                $map    =   self::SYS_ERROR;
                $route  =   $_GET["error"];
            }else{
                $app    =   self::defai();
                $map    =   self::APP_DEFAULT;
                $route  =   0;
            }
            $request->update($app, $route, 0, '/');
            return [
                'map'       =>  $map,
                'app'       =>  $app
            ];
        }
    }

    const
    NOT_MATCH       =   0,
    STD_MVC         =   -1,
    STD_TEST        =   -2,
    STD_IPC         =   -3,
    STD_REST        =   -7,
    DIR_MATCH       =   -5,
    APP_DEFAULT     =   -6,
    SYS_ERROR       =   -4;

    /**
     * 配置子路由
     * 
     * @access public
     * @param object(Tangram\MODEL\Application) 活动应用
     * @param object(Tangram\MODEL\ApplicationPermissions) 进程权限表
     * @return null
    **/ 
    public function route(App $app, ApplicationPermissions $permissions){
        include(FPATH.'Routers/BaseRouter.php');
        if(ROUTE===self::STD_TEST){
            include(FPATH.'Controllers/traits/.testmethods.php');
        }
        include(FPATH.'Controllers/BaseController.php');
        include(FPATH.'Models/BaseModel.php');
        class_alias('AF\Controllers\BaseController', 'Controller');
        class_alias('AF\Models\BaseModel', 'Model');

        switch (ROUTE) {
            case self::STD_IPC:
            include(FPATH.'Controllers/IPCController.php');
            case self::STD_MVC:
            include(FPATH.'Routers/StandardRouter.php');
            return $app->executeStdMVCAction();

            case self::STD_TEST:
            return $app->testCLIController();

            case self::APP_DEFAULT:
            $app->routeDefaultResource();
            case self::DIR_MATCH:
            return $app->routeCustomResource();

            case self::STD_REST:
            include(FPATH.'Controllers/BaseResourcesController.php');
            return $app->crudStandardResource();
        }
        return new StatusProcessor(route, '', $_SERVER['REQUEST_URI'], true);
    }

    /**
     * 检查异步任务
     * 
     * @access private
     * @param object(Tangram\MODEL\Application) 应用TASKS
     * @return null
    **/ 
    public function checktasks(App $app){
        return $app->callOtherProcess([
            'preset'    =>  'master',
            'args'      =>  [],
            'sData'     =>  [],
            'timeout'   =>  _WORKER_BUILD_TIMEOUT_
        ]);
    }
}