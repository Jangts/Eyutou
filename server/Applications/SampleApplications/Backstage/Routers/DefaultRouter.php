<?php
namespace Lailihong\Backstage\Routers;

use Request;
use App;
use Tangram\CACHE\cac_agents\Filesys;

class DefaultRouter extends \AF\Routers\BaseRouter {
    protected function analysis(App $app, Request $request){
		$classname = 'AdminBusController';
		$filename = $app->Path.'Controllers/'.$classname;
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
		define('__admindir__', $request->ARI->dirname.'/');
		return [$filename, $fullclassname, 'render', [$request->ARI->patharr]];
	}
}