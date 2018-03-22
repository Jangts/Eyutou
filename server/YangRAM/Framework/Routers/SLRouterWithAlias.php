<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

/**
 * @class AF\Routers\SLRouterWithAlias
 * 路由器基类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class SLRouterWithAlias extends BaseRouter {
    protected
    $classalias = NULL,
	$methodoptions = NULL,
	$controllers = [];
    
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
		$classname = self::correctClassName($this->getClassName($request));
		$filename = $app->Path.'Controllers/'.str_replace('\\', '/', $classname);
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
		$methodname = $this->getMethodName($request);
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
    final protected function getClassName(Request $request){
		if($request->INPUTS->c){
			$classalias = strtolower($request->INPUTS->c);
		}elseif(isset($request->ARI->patharr[0])){
			$classalias = $request->ARI->patharr[0];
		}else{
			new Status(1415.1, 'Classname Unspecified', true);
		}
		if(isset($this->controllers[$classalias])){
			$this->classalias = $classalias;
			if(isset($this->controllers[$classalias]['classname'])){
				return$this->controllers[$classalias]['classname'];
			}else{
				return $classalias.'Controller';
			}
		}
		new Status(1415.2, 'Class Specified Error', 'Undeclared Classalias [' . $classalias . ']', true);
	}

	/**
	 * 获取控制器实例方法
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @return string
	**/
	final protected function getMethodName(Request $request){
		if(isset($this->controllers[$this->classalias]['methods'])){
			$methodalias = strtolower(str_replace('-', '_', $request->INPUTS->m));
			if(!$methodalias){
				if(isset($request->ARI->patharr[1])){
					$methodalias = str_replace('-', '_', $request->ARI->patharr[1]);
				}else{
					$methodalias = 'main';
				}
			}
			$methods = $this->controllers[$this->classalias]['methods'];
        	if(isset($methods[$methodalias])){
				$this->methodoptions = $methods[$methodalias];
				if(empty($methods[$methodalias]['methodname'])){
					return $methodalias;
				}
				return $methods[$methodalias]['methodname'];
			}
		}
		new Status(1415.3, 'Methodname Specified Error', 'Undeclared Methodname [' . $methodalias . '] For Class ' . $this->controllers[$this->classalias]['classname'], true);
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
	final protected function getParameters(Request $request, $classname, $methodname){
		if(is_string($request->INPUTS->args)){
			$args = explode('/', $request->INPUTS->args);
		}elseif(is_string($request->INPUTS->a)){
			$args = explode('/', $request->INPUTS->a);
		}else{
			$args = array_slice($request->ARI->patharr, 2);
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