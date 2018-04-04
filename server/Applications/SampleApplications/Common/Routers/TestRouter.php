<?php
namespace App\Routers;

use Request;
use App;

class TestRouter extends \AF\Routers\BaseRouter {
    protected function analysis(App $app, Request $request){
		$classname = 'MyController';
		$filename = $app->Path.'Controllers/'.$classname;
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
        return [$filename, $fullclassname, 'mymethod', ['YangRAM', 5.0]];
	}
}