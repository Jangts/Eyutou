<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

// 引入相关命名空间，以简化书写
use Tangram\ClassLoader;
use Status;
use Request;
use App;

/**
 * @class AF\Routers\RESTfulRouter
 * 路由器基类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class RESTfulRouter extends BaseRouter {
	protected
	$alias = [],				// 别名映射，不建议使用，除非必需使用别名
	$ignoreAppDir = false,		// 忽略虚拟应用目录，即分组数组采用ARI，默认关闭，即采用TRI
	$pathPrevail = false,		// 路径为准，即当路径与$_get存在相同键时，以路径中的值为准，默认为否
	$options;

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
		if($this->ignoreAppDir){
			$patharr = $request->ARI->patharr;
		}else{
			$patharr = $request->TRI->patharr;
			array_shift($patharr);
		}
		$classname = self::correctClassName($this->getClassName($request, $patharr));
		$filename = $app->Path.'Controllers/'.str_replace('\\', '/', $classname);
		ClassLoader::execute($filename);
		$fullclassname = $app->xProps['Namespace'].'\\Controllers\\'.$classname;

		//检查控制器类型的基类，确保控制器是AF\Controllers\BaseResourcesController的子类
		if(is_subclass_of($fullclassname, 'AF\Controllers\BaseResourcesController')){
			$fullclassname = '\\'.$fullclassname;
			$arguments = $this->getParameters($request);
			$methodname = $this->getMethodName($request, $fullclassname, $arguments[1]);
			return [$filename, $fullclassname, $methodname, $arguments];
		}
		new Status(1422, '', 'Controller for RESTful API must be a subclass of AF\Controllers\BaseResourcesController', true);
	}
	
	/**
	 * 分析路径中的资源词，并推导或匹配控制器名
	 * 如以下路径：
	 * GET /appdir/zoos：列出所有动物园
	 * POST /zoos：新建一个动物园
	 * GET /zoos/ID：获取某个指定动物园的信息
	 * PUT /zoos/ID：更新某个指定动物园的信息（提供该动物园的全部信息）
	 * PATCH /zoos/ID：更新某个指定动物园的信息（提供该动物园的部分信息）
	 * DELETE /zoos/ID：删除某个动物园
	 * GET /zh-cn/zoos/ID/animals：列出某个指定动物园的所有动物
	 * DELETE /zoos/ID/animals/ID：删除某个指定动物园的指定动物
	 * 将分别导出以下结果：
	 * zoos, ZoosController			[]						// 结果为设置$ignoreAppDir为true的结果
	 * zoos, ZoosController			[]
	 * zoos, ZoosController			[zoos=>ID]
	 * zoos, ZoosController			[zoos=>ID]
	 * zoos, ZoosController			[zoos=>ID]
	 * zoos, ZoosController			[zoos=>ID]
	 * animals, AnimalsController	[zoos=>ID]				// 如果系统支持zh-cn语言频道，则zh-cn段会被自动忽略掉
	 * animals, AnimalsController	[zoos=>ID, animals=>ID]
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @param array							$patharr
	 * @return string
	**/
    final protected function getClassName(Request $requestt, array $patharr){
		/**
		 * 路径中除了语言频道外不应该有非REST的信息
		 * 此函数会把$request->TRI->patharr（亦可指定为$request->ARI->patharr）按奇偶分成键值组
		 * 其中最后一组键值组(值可能为空)即为资源本体
		 * 此函数将根据资源本体匹配控制器名称
		 * 其他键值组将与$_get合并为最终参数，默认以$_get为准
		**/
		
		$count = count($patharr);
		if($count>0){
			$i = 0;
			$options = [];
			for($i; $i < $count; $i += 2){
				if(isset($patharr[$i+1])){
					$options[] = [$patharr[$i], $patharr[$i+1]];
				}else{
					$options[] = [$patharr[$i], NULL];
				}
			}
			$this->options = $options;
			$classalias = $patharr[$i-2];
			if(isset($this->alias[$classalias])){
				return $this->alias[$classalias];
			}else{
				return $classalias.'Controller';
			}
		}else{
			# 返回api列表

		}
	}

	/**
	 * 获取控制器实例方法
	 * 将根据以下请求方法：
	 * GET（SELECT）：从服务器取出资源（一项或多项）。
	 * POST（CREATE）：在服务器新建一个资源。
	 * PUT（UPDATE）：在服务器更新资源（客户端提供改变后的完整资源）。
	 * PATCH（UPDATE）：在服务器更新资源（客户端提供改变的属性）。
	 * DELETE（DELETE）：从服务器删除资源。
	 * HEAD：获取资源的元数据。
	 * OPTIONS：获取信息，关于资源的哪些属性是客户端可以改变的。
	 * 匹配实例方法
	 * get(), select()
	 * post(), create(), put()
	 * put(), update()
	 * patch(), update()
	 * delete(), remove()
	 * getHeadInfo(), getMetaInfo()
	 * getOptions()
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @param string , $fullclassname
	 * @return string
	**/
	final protected function getMethodName(Request $request, $fullclassname, array $options){
		// 为每种请求方法配置了一个标准方法和几个兼容方法，以照顾不同开发者的习惯
		// 一个控制器里同时存在标准方法和兼容方法的，兼容方法将不生效
		// 为规范起见，开发者应当尽量使用标注方法，因为兼容方法随时可能再下一次系统更新时被取消

	
		switch($request->METHOD){
			case 'POST':
			if(method_exists($fullclassname, 'post')){
				return 'post';
			}
			// 新的BaseResourcesController定义了create方法，不需要再检查create
			return 'create';
			// if(method_exists($fullclassname, 'create')){
				
			// }
			// return 'returnMOFError';

			
			return 'returnMOFError';

			case 'PATCH':
			if(method_exists($fullclassname, 'patch')){
				return 'patch';
			}
			case 'PUT':
			case 'UPDATE':
			if(method_exists($fullclassname, 'put')){
				return 'put';
			}
			// 新的BaseResourcesController定义了update方法，不需要再检查update和post
			return 'update';
			// if(method_exists($fullclassname, 'update')){
				
			// }
			// if(method_exists($fullclassname, 'post')){
			// 	return 'post';
			// }
			// return 'returnMOFError';

			case 'DELETE':
			if(method_exists($fullclassname, 'remove')){
				return 'remove';
			}
			// 新的BaseResourcesController定义了delete方法，不需要再检查delete
			return 'delete';
			// if(method_exists($fullclassname, 'delete')){
				
			// }
			// return 'returnMOFError';

			case 'HEAD':
			if(method_exists($fullclassname, 'getHeadInfo')){
				return 'getHeadInfo';
			}
			return 'returnMOFError';

			case 'OPTIONS':
			if(method_exists($fullclassname, 'getOptions')){
				return 'getOptions';
			}
			return 'returnMOFError';

			// case 'GET':
			default:
			if(method_exists($fullclassname, 'get')){
				return 'get';
			}
			if(method_exists($fullclassname, 'read')){
				return 'select';
			}
			if(method_exists($fullclassname, 'select')){
				return 'select';
			}
			return 'returnMOFError';
		}
	}
	
	/**
	 * 整理控制器实例方法参数
	 * 
	 * @access protected
	 * @final
	 * @param object(Tangram\MODEL\Request) $request
	 * @return string
	**/
	final protected function getParameters(Request $request){
		$options = [];

		// 遍历$this->options，生成id参数和原始$options参数
		foreach($this->options as $index=>$option){
			$args[$option[0]] = $option[1];
		}
		$pk = $this->options[$index][0];
		if($this->pathPrevail){
			// 路径优先
			$options = array_merge($request->FORM->__get, $args);
		}else{
			// 附参优先
			$options = array_merge($args, $request->FORM->__get);
		}
		$id = $args[$pk];
		
		return [$id, $options];
	}
}