<?php
namespace App\Routers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class RESTfulRouter extends \AF\Routers\RESTfulRouter {
    protected $ignoreAppDir = true;
}