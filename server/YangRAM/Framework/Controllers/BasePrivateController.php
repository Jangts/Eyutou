<?php
namespace AF\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Tangram\ClassLoader;
use Request;
use App;

/**
 * @class AF\Controllers\BaseController
 * Private Controller Base Class
 * 私有控制器基类
 * 为含有权限检查的控制器提供的基类
 * 
 * @abstract
 * @author 		Jangts
 * @version    	5.0.0
**/
abstract class BasePrivateController extends BaseController {
    protected
    $accessCode = 0;

    /**
     * 检查当前权限，并返回权限代码
     */
    abstract public function checkAuthority($type = NULL, array $options = []);

    final public function getAccessCode(){
        return $this->accessCode;
    }

    public function reject($href = NULL){
        if(is_string($href)){
            new Status(1411, '', 'This is a private API, and you can get the right by visit this url: '.$href.' .', true);
        }
        new Status(1411, '', 'This is a private API, and you have no right to access.', true);
    }
    
}