<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class DefaultController extends \Controller {
    public function main(){
        exit('{"code":"200","status":"OK","msg":"Welcome to use Standard (Like) API of YangRAM!"}');
    }
}