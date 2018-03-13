<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

// 引入相关命名空间，以简化书写
use Tangram\ClassLoader;
use Status;
use Request;
use App;

/**
 * @class AF\Routers\BaseRouter
 * 路由器基类
 * 子应用的自定义路由直接继承此类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class BaseRouter {
    public static function correctClassName($classname){
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $classname)));
    }

    protected
    $classalias = [],
	$methodoptions = [],
    $models = [];
    
    /**
     * 路由器构造方法
     * 
     * @access public
     * @final
     * @param object(Tangram\MODEL\Application) $app
     * @param object(Tangram\MODEL\Request)     $request
    **/
    final public function __construct(App $app, Request $request){
        list($filename, $fullclassname, $methodname, $arguments) = $this->analysis($app, $request);
		ClassLoader::execute($filename);
        if(class_exists($fullclassname)){
			$class = new $fullclassname($app, $request);
            if(method_exists($class, $methodname)){
                call_user_func_array([$class, $methodname], $arguments);
            }else{
                new Status(1442.3, 'Contronller Method Not Found', "Method $fullclassname::$methodname Not Found! Files of application [$app->Name] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
            }
        }else{
            new Status(1442.2, 'Contronller Not Found', "Class $fullclassname Not Found! Files of application [$app->Name] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
        }
    }
    
    /**
     * 路由分析方法
     * 
     * @access protected
     * @param object(Tangram\MODEL\Application) $app
     * @param object(Tangram\MODEL\Request)     $request
     * @return array
    **/
    protected function analysis(App $app, Request $request){
        new Status(1422, '', "Method AF\Routers\BaseRouter->analysis() must be redeclare.", true);
    }
}