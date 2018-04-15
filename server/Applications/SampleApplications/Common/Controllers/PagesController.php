<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class PagesController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function checkReadAuthority(array $options = []) : bool {
        return true;
    }
    
    public function get($id, array $options = []){
        var_dump($id, $options);
        exit('{"code":"200","status":"OK","msg":"Welcome to use RESTful API of YangRAM!"}');
    }
}