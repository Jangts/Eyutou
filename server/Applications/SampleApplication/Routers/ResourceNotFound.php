<?php
namespace App\Routers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class ResourceNotFound extends \AF\Routers\BaseRouter {
    /**
     * 路由器构造方法
     * 
     * @access protected
     * @param object(Tangram\MODEL\Application) $app
     * @param object(Tangram\MODEL\Request)     $request
    **/
    protected function analysis(App $app, Request $request){
        new Status(404, '', "", true);
    }
}