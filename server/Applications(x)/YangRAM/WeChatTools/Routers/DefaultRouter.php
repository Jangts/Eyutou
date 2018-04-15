<?php
namespace WX\Routers;

use Status;
use Request;
use App;

class DefaultRouter extends \AF\Routers\BaseRouter {
    protected function analysis(App $app, Request $request) : array {
		$classname = 'Controller';
		$filename = $app->Path.'Controllers/'.$classname;
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
        return [$filename, $fullclassname, 'main', ['YangRAM', 5.0]];
	}
}