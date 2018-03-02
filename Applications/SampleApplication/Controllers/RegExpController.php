<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class RegExpController extends \Controller {
    public function pages($options){
        var_dump($options);
        exit('{"code":"200","status":"OK","msg":"Welcome to use RegExp Mataching API of YangRAM!"}');
    }
}