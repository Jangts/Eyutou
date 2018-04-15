<?php
namespace PM\_NOTIFIER;

use Model;

/**
 * Application Messages Model
 * 应用会员信息模型
**/
abstract class BaseNotifyModel extends \AF\Models\BaseModel {
    protected
    $type = 0,
    $modelProperties = ['uid' => 0];

    final public function __construct($appid, $uid = 0){
        $this->appid = $appid;
        if($modelProperties = $this->query($uid)){
            $this->modelProperties = $modelProperties;
        }
        $this->__init();
    }

    protected function __init(){}

    abstract protected function query($uid) : array;

    protected function __put(array $input, $isSent){

    }

    private function register(){

    }

    public function checkPin(){

    }
}
