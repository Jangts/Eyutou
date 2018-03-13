<?php
namespace Pages\Main\Routers;

use Request;
use App;
use Tangram\CACHE\cac_agents\Filesys;

class DefaultRouter extends \AF\Routers\BaseRouter {
    protected function analysis(App $app, Request $request){
		$classname = 'PageRenderingController';
		$filename = $app->Path.'Controllers/'.$classname;
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
		// if(RT_CURR===6){
		// 	return [$filename, $fullclassname, 'renderDefaultPage', [$request->ARI->patharr]];
		// }
		if($result = $this->checkPluginDirs($app->Path, $app->xProps['Namespace'], $request->ARI->patharr)){
			return $result;
		}
		if($result = $this->checkRequestGets()){
			return $result;
		}
		if($result = $this->checkRESTLikeAPIs()){
			return $result;
		}
		return [$filename, $fullclassname, 'renderPageByAlias', [$request->ARI->patharr]];
	}

	private function checkPluginDirs($apppath, $namespace,  $patharr){
		if(is_file(__DIR__.'/plugindirs.json')){
			$pluginDirs = json_decode(file_get_contents(__DIR__.'/plugindirs.json'), true);
		}elseif(is_file($apppath.'/Plugins/plugins.json')){
			$pluginDirs = [];
			$plugins = json_decode(file_get_contents($apppath.'/Plugins/plugins.json'), true);
			foreach($plugins as $name=>$plugin){
				if(!empty($plugin['plugindirs'])){
					foreach($plugin['plugindirs'] as $dir=>$options){
						if(empty($options['path'])){
							$options['path']	= 	$name;
						}
						$pluginDirs[$dir] = $options;
					}
				}
			}
			Filesys::writeContent(__DIR__.'/plugindirs.json', json_encode($pluginDirs));
		}else{
			return false;
		}

		$relative_dirname = implode('/', $patharr);
		foreach($pluginDirs as $dir=>$options){
			if(stripos($relative_dirname.'/', $dir)===0){
				$classname = $options['controller'];
				$filename = $apppath.'Plugins/'.$options['path'].'/Controllers/'.$classname;
				$fullclassname = '\\'.$namespace.'\\Plugins\\'.$options['path'].'\\Controllers\\'.$classname;
				return [$filename, $fullclassname, $options['method'], [$patharr]];
			}
		}
		return false;
	}

	private function checkRequestGets(){
		
	}

	private function checkRESTLikeAPIs(){
		
	}
}