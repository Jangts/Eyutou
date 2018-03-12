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
    protected static $instance = NULL, $defhost = NULL, $res = NULL;

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
	 * 默认域名（如果有的话）获取方法
	 * 第一次调用时创建标注化请求对象
	 * 
	 * @access public
     * @static
	 * @return object(Tangram\MODEL\Request) 标注化请求对象
	**/
    public static function defhost(){
        // 判断是否第一次被调用
        if(self::$defhost===NULL){
            if(_DEFAULT_DOMAIN_){
                if(stripos(HOST, 'www.')===0){
                    // 返回主域名的'www'子域名
                    self::$defhost = 'www.'.strtolower(_DEFAULT_DOMAIN_);
                }else{
                    // 直接返回主域名
                    self::$defhost = strtolower(_DEFAULT_DOMAIN_);
                }
            }else{
			    // 没有指定主域名，则返回当前域名
                self::$defhost = HOST;
            }
        }

        // var_dump(HOST, _DEFAULT_DOMAIN_, self::$defhost);

        // 返回默认域名
		return self::$defhost;
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
        $defhost = self::defhost();

        // 检查是否有缓存，如果有则直接响应给客户端，否则返回false
        // ……
        // 初始化Resource
        // 实例化一个Resource
        // 尝试输出资源（如果有的话）
        self::$res = Resource::initialize($request)->render();

        // 检查是否为标准接口
        $temporary = $this->matchStandardAPI($pathname, $patharr, $request, $defhost);

        // 如果非标准接口，则进行默认路由表匹配
        // 如果返回路由表编号，则进行该路由表的匹配
        if($temporary['map']===0){

            // 检查是否启用且适用于默认RESTful API
            if($defhost===HOST&&_USE_REST_API_){
                $temporary = $this->matchDefaultREST($pathname, $patharr, $request, $defhost);
            }
            RouteCollection::initialize();
            while ($temporary['map']>=0){
                $temporary = $this->matchRouteMap($temporary['map'], $pathname, $patharr, $request, $defhost);
            }
        }
        
        setcookie('language', REQUEST_LANGUAGE, time()+315360000, '/', HOST, _OVER_SSL_, true);
        if(isset($temporary['app'])){
            define('AC_CURR', $temporary['app']);
            define('RT_CURR', abs($temporary['map']));
        }else{
            new StatusProcessor(404, true);//StatusProcessor::notFound();
        }
    }

    private function checked($patharr, $request, $mapid, $index){
        if((isset($patharr[$index])&&$patharr[$index]!=='')){
            $appid = $patharr[$index];
        }else{
            if(defined('_CLI_MODE_')){
                $appid = 0;
                while(empty($appid)){
                    fwrite(STDOUT,"Please specify a subapp (press the APPID):\r\n");
                    $appid=trim(fgets(STDIN));
                }
            }else{
                $request->update('tangram', -1);
                return [
                    'map'       =>  -400,
                    'app'       =>  'tangram'
                ];
            }
        }
        $request->update($appid, $index);
        return [
            'map'       =>  $mapid,
            'app'       =>  $appid
        ];
    }

    /**  
	 * 与标准接口格式进行匹配
	 * 
	 * @access private
     * @param string $pathname
     * @param array $patharr
     * @param object(Tangram\MODEL\Request) $request
     * @param string $defhost
	 * @return array 一个数组格式的路由表
    **/ 
    private function matchStandardAPI($pathname, $patharr, $request, $defhost){
        // 用来比较的目录名需要前缀当前访问域名
        // 而用来被比较的目录名则前缀主域名（如果有的话）
        $pathname = $pathname . '/';
        
        if(stripos($pathname, '/:test/')===0){
            if(defined('_CLI_MODE_')){
                SESSION::init('_test_session_id_');
                echo "Unit Test In Cli Mode\r\n";

                if(empty($_SESSION['username'])){
                    fwrite(STDOUT,"Please input username:\r\n");
                    if($username=trim(fgets(STDIN))){
                        $_SESSION['username'] = $username;
                    }else{
                        $_SESSION['username'] = 'system';
                    }
                }

                if(empty($_SESSION['language'])){
                    fwrite(STDOUT,"Please input request language:\r\n");
                    if($language=trim(fgets(STDIN))){
                        $_COOKIE['language'] = $_SESSION['language'] = $language;
                    }else{
                        $_COOKIE['language'] = $_SESSION['language'] = REQUEST_LANGUAGE;
                    }
                }else{
                    $_COOKIE['language'] = $_SESSION['language'];
                }
                
                return $this->checked($patharr, $request, -2, 2);
            }else{
                new StatusProcessor(1402, '', 'Test api only runs in CLI mode.', true);
            }
        }

        // 标准开放路由仅支持主域名访问
        $pathname = HOST. $pathname;

        // 子域名形式
        if(_STD_API_DOMAIN_){
            define('_STD_API_', _STD_API_DOMAIN_.'.'.$defhost.'/');
            $index = 1;
        }
        // 虚拟目录形式
        // 使用了子域名形式的标准api之后，目录形式的会失效
        elseif(_STD_API_DIR_){
            define('_STD_API_', $defhost.'/'._STD_API_DIR_.'/');
            $index = count(explode('/', preg_replace('/(^\/|\/$)/', '', preg_replace('/[\\\\\/]+/', '/', _STD_API_DIR_)))) + 1;
        }else{
            new Status(1402, '', 'Must Have a Standard API Configuration', true);
        }

        // GUID形式
        if($pathname===_STD_API_&&isset($_GET['app'])&&$_GET['app']!==''){
            SESSION::init();
            $request->update($_GET['app'], 1);
            return [
                'map'       =>  -1,
                'app'       =>  $_GET['app']
            ];
        }

        // IPC接口
        if(stripos($pathname, _STD_API_.':ipc/')===0){
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
                    return $this->checked($patharr, $request, -3, $index);
                }
                // 未明确交互应用的ID
                $sp = new StatusProcessor(1402, '', 'IPC needs specify an application.');
                return $sp->respond(StatusProcessor::JLOG);
            }
            // 否则抛出相应异常
            $sp = new StatusProcessor(1402, '', 'IPC must be a private session.');
            return $sp->respond(StatusProcessor::JLOG);
        }

        // 标准API
        if(stripos($pathname, _STD_API_)===0){
            SESSION::init();
            return $this->checked($patharr, $request, -1, $index);
        }
        
        return ['map' => 0];
    }

    /**  
	 * 与路由总表进行匹配
	 * 
    **/ 
    private function matchDefaultREST($pathname, $patharr, $request, $defhost){
        if(stripos($pathname, __aurl__)===0){
            SESSION::init();
            if((count($patharr)>3&&$patharr[2]!=='')){
                $appid = $patharr[2];
                $request->update($appid, 2, true, -1, __aurl__.$appid);
                return [
                    'map'       =>  -7,
                    'app'       =>  $appid
                ];
            }
            $request->update('tangram', -1);
            return [
                'map'       =>  -400,
                'app'       =>  'tangram'
            ];
        }

        return ['map' => 0];
    }

    /**  
	 * 与路由总表进行匹配
	 * 
	 * @access private
     * @param int $map
     * @param string $pathname
     * @param array $patharr
     * @param object(Tangram\MODEL\Request) $request
     * @param string $defhost
	 * @return array 一个数组格式的路由表
    **/ 
    private function matchRouteMap($map, $pathname, $patharr, $request, $defhost){
        $map = new RouteCollection($map, $defhost, $request->URI->spuerhost);
        $item = $map->match($pathname);
        if($item['STATE']===2){
            return [
                'map'       =>  $item['HANDLER']
            ];
        }elseif($item['STATE']===1){
            SESSION::init();
            $request->update($item['HANDLER'], $item['DEPTH'], true, $item['ROUTE'], $item['DIRNAME'], $item['DEFAULTS']);
            return [
                'map'       =>  -5,
                'app'       =>  $item['HANDLER']
            ];
        }elseif($item['STATE']===3){
            SESSION::init();
            $request->update(_DEFAULT_APP_, 0, true, 0, '/', []);
            return [
                'map'       =>  -6,
                'app'       =>  _DEFAULT_APP_
            ];
        }else{
            $request->update('tangram', 0);
            if($pathname === ''){
                return [
                    'map'       =>  -200,
                    'app'       =>  'tangram'
                ];
            }
            return [
                'map'       =>  -404,
                'app'       =>  'tangram'
            ];
        }
    }

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
        if(RT_CURR===2){
            include(FPATH.'Controllers/traits/.testmethods.php');
        }
        include(FPATH.'Controllers/BaseController.php');
        include(FPATH.'Models/BaseModel.php');
        class_alias('AF\Controllers\BaseController', 'Controller');
        class_alias('AF\Models\BaseModel', 'Model');

        switch (RT_CURR) {
            case 1:
            case 3:
            include(FPATH.'Routers/StandardRouter.php');
            if(RT_CURR===3){
                include(FPATH.'Controllers/IPCController.php');
            }
            return $app->handleStdAPI();

            case 2:
            return $app->testController();

            case 404:
            
            return $app->handleError();

            case 5:
            case 6:
            return $app->handleRouterById();

            case 7:
            include(FPATH.'Controllers/BaseResourcesController.php');
            return $app->handleResource();
        }   
    }

    /**
     * 检查异步任务
     * 
     * @access private
     * @param object(Tangram\MODEL\Application) 应用TASKS
     * @return null
    **/ 
    public function checktasks(App $app){
        return $app->workInOtherProcess([
            'preset'    =>  'master',
            'args'      =>  [],
            'sData'     =>  [],
            'timeout'   =>  _WORKER_BUILD_TIMEOUT_
        ]);
    }
}