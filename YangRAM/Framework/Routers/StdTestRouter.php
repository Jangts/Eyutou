<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

/**
 * @class AF\Routers\StdTestRouter
 * 路由器基类
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class StdTestRouter extends BaseRouter {    
    /**
     * 路由分析方法
     * 
     * @access protected
     * @final
     * @param object(Tangram\MODEL\Application) $app
     * @param object(Tangram\MODEL\Request)     $request
	 * @return array
    **/
    final protected function analysis(App $app, Request $request){
		$classname = $this->getClassName($app->Path.'Controllers/', $request);
		$filename = $app->Path.'Controllers/'.$classname;
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
		$methodname = $this->getMethodName($request);
		$arguments = $this->getParameters($request);
        return [$filename, $fullclassname, $methodname, $arguments];
	}
	
	/**
	 * 获取控制器名
	 * 
	 * @access private
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @return string
	**/
    final private function getClassName($path, Request $request){
		$classname = preg_replace('/\\\+/', '\\', $request->FORM->c).'Controller';
		while(!is_file($path.$classname.'.php')){
			if($classname==='__exitController'){
				exit('Thank You!');
			}
			fwrite(STDOUT,"Please specify controller alias:\r\n");
			$classname = preg_replace('/\\\+/', '\\', trim(fgets(STDIN))).'Controller';
		}
		return $classname;
	}

	/**
	 * 获取控制器实例方法
	 * 
	 * @access private
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @return string
	**/
	final private function getMethodName(Request $request){
		if($request->FORM->m){
			return str_replace('-', '_', $request->FORM->m);
		}
		fwrite(STDOUT,"Please specify a method (press the enter key means call main()):\r\n");
        if($input = trim(fgets(STDIN))){
			return $input;
		}
		return 'main';
	}

	/**
	 * 整理控制器实例方法参数
	 * 
	 * @access private
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @return string
	**/
	final private function getParameters(Request $request){
		if(is_string($request->FORM->args)){
			return preg_split('/\s+/', $request->FORM->args);
		}
		return [];
	}
}