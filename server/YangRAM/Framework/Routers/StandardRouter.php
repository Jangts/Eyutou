<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

/**
 * @class AF\Routers\StandardRouter
 * 路由器基类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class StandardRouter extends BaseRouter {
	protected
	$defaultc = 'Default',
	$controllers = [
		'Default' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				]
			]
		]
	],
	$methodoptions = NULL;
    
    /**
     * 路由分析方法
     * 
     * @access protected
     * @final
     * @param object(Tangram\MODEL\Application) $app
     * @param object(Tangram\MODEL\Request)     $request
	 * @return array
    **/
    final protected function analysis(App $app, Request $request) : array {
		$classalias = $this->getClassAlias($request);
		$classname = $classalias.'Controller';
		$filename = $app->Path.'Controllers/'.$classname;
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
		$methodname = $this->getMethodName($request, $classalias);
		$arguments = $this->getParameters($request, $classname, $methodname);
        return [$filename, $fullclassname, $methodname, $arguments];
	}
	
	/**
	 * 获取控制器名
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @return string
	**/
    final protected function getClassAlias(Request $request){
		if($request->INPUTS->c){
			$classalias = preg_replace('/\\\+/', '\\', $request->INPUTS->c);
		}elseif(isset($request->ARI->patharr[0])){
			$classalias = preg_replace('/\\\+/', '\\', preg_replace('/\..*$/', '', $request->ARI->patharr[0]));
		}elseif($this->defaultc&&isset($this->controllers[$this->defaultc])){
			return $this->defaultc;
		}
		$classalias = self::correctClassName($classalias);
		if(isset($this->controllers[$classalias])){
			return $classalias;
		}
		new Status(1415.2, 'Class Specified Error', 'Class [' . $classalias.'Controller' . '] is nonexistent or private', true);
	}

	/**
	 * 获取控制器实例方法
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @param string	 					$classalias
	 * @return string
	**/
	final protected function getMethodName(Request $request, $classalias){
		$methodname = preg_replace('/[\-\_]+/', '_', $request->INPUTS->m);;
		if(!$methodname){
			$methodname = 'main';
		};
		$methodname = strtolower($methodname);
		if(isset($this->controllers[$classalias]['methods'])){
			$methods = $this->controllers[$classalias]['methods'];
        	if(isset($methods[$methodname])){
				$this->methodoptions = $methods[$methodname];
				return $methodname;
			}
		}
		new Status(1415.3, 'Methodname Specified Error', 'Undeclared Methodname [' . $methodname . '] For Class ' . $classalias.'Controller', true);
	}

	/**
	 * 整理控制器实例方法参数
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @param string	 					$classname
	 * @param string						$methodname 
	 * @return string
	**/
	final protected function getParameters(Request $request, $classname, $methodname) : array {
		if(is_string($request->INPUTS->args)){
			$args = explode('/', $request->INPUTS->args);
		}else{
			$args = [];
		}
		$i = count($args);
		if(isset($this->methodoptions['args'])&&is_array($pargs = $this->methodoptions['args'])){
			$count = count($pargs);
			for($i; $i < $count; $i++){
				$args[$i] = $pargs[$i];
			}
		}
		if($i>=$this->methodoptions['minArgsLength']){
			return $args;
		}
		new Status(1415.4, 'Parameters Given Error', 'Insufficient Number Of Parameters For [' . $classname . '::' . $methodname . '], this method needs at least '.$this->methodoptions['minArgsLength'].' arguments, given is '.count($args).'.', true);
	}
}