<?php
namespace AF\Controllers\VISA;

use Request;
use AF\Models\Certificates\Passport;
use App;

/**
 * Common Application Member Visa
 * 通用应用会员签证器
 * 用于验证会员的控制器抽象，提供了应用会员验证的基本对象和方法
**/
abstract class BaseVISAController extends BaseAuthenticateController {
    const       TYPE    =   'VISA';
    protected   $type   =   'VISA';

    protected   $sp =   'Guest' ,
                            /*  $sp in ['Guest', 'Acquaintance', 'Familiar', 'Member', 'Runholder'];
                             * $sp 的值为：客人，认识的人，熟知的人，代理人，會員。
                            **/
                $uid                ,
                $user               ;

    final public function __construct(App $app, Request $request){
        $this->request = $request;
        $this->app = $app;
        $this->passport = Passport::instance();
        $this->aid = $app->APPID;
        $this->uid = $this->passport->uid;
        $this->init();
    }

    final public function myStatus(){
        return $this->status;
    }

    public function __get($property){
        return $this->member->$property;
    }

    abstract protected function init();

    abstract public function myPoints();

    abstract public function myGroup();
}