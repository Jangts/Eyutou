<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class MyController extends \Controller {
    public function mymethod($arg1, $arg2){
        var_dump($arg1, $arg2);
        exit('{"code":"200","status":"OK","msg":"Welcome to use Custom API of YangRAM!"}');
    }
}