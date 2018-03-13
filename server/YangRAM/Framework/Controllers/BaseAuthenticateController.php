<?php
namespace AF\Controllers;

/**
 * Common Member Authentication Abstract
 * 通用会员验证器抽象
**/
abstract class BaseAuthenticateController extends Controller {
    const       TYPE    =   'AUTH';
    protected   $type   =   'AUTH';

    abstract public function register();

    abstract public function checkPin($PIN);

    abstract public function myCgetALL();

    abstract public function myTitle();

    abstract protected function init();

    abstract public function activate();

    abstract public function myPoints();
}
