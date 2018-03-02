<?php
namespace Tangram\MODEL;

/**
 * @class Tangram\MODEL\UserPassportModel
 * General User UserPassportModel
 * 通用用户护照
 * 仿单例类
 * 用户身份认证处理的全局对象
**/
final class UserPassportModel extends ObjectModel {
    private static
    $uid = 0,
    $username = 'guest',
    $powertype = 0,
    $instance = NULL;

	public static function init($rebuild = false){
		if(self::$instance === NULL||$rebuild){
			self::$instance = new self;
		}
    }

    public static function holder(){
        self::init();
		return self::$uid;
    }

    public static function holdername(){
        self::init();
		return self::$username;
    }

    public static function type(){
        self::init();
		return self::$powertype;
    }
    
    public static function checkIn($value, $private = true, $salt = '', $alias = NULL){
        self::init();
		return self::$instance->visa($value, $private, $salt, $alias);
    }

    public static function checkOut(){
        self::init();
		return self::$instance->abstain();
    }

    public static function hasVisaed($salt = '', $appid = AI_CURR){
        self::init();
		return self::$instance->checkVisa($salt, $appid);
    }

    public static function hasSignedInGroup($gid = 'Administrators'){
        $ug = UserGroupReadolyModel::byGUID($gid);
        if(!$ug){
            $ug = UserGroupReadolyModel::byALIAS($gid);
        }
        if(!$ug){
            return false;
        }
        if($ug->TYPE==='VISA'){
            self::init();
            return self::$instance->checkVisa($ug->SYMBOL, $ug->APPID);
        }
        return false;
    }

    public static function inGroup($gid = 'Administrators', $strictMode = false){
        self::init();
        if($strictMode!=false||self::$powertype===2){
            $ug = UserGroupReadolyModel::byGUID($gid);
            if(!$ug){
                $ug = UserGroupReadolyModel::byALIAS($gid);
            }
            if(!$ug){
                return false;
            }
            switch($ug->TYPE){
                case 'VISA':
                $visa = self::$instance->checkVisa($ug->SYMBOL, $ug->APPID);
                if($strictMode||$visa){
                    return $visa;
                }

                case 'USER':
                case 'CARD':
                return self::$instance->getUserAccountCopy()->isA($gid);    
            }
        }
        return false;
    }
    
    public static function getUserPassportCopy(){
        self::init();
		return self::$instance;
    }

    public static function getUserAccount(){
        self::init();
        return self::$instance->getUserAccountCopy();
    }

    protected
    $readonly = true,
    $account = null,
    $modelProperties = [];

    private function __construct(){
        if(isset($_SESSION['username'])){
            // 检查是否为正式登陆
            $this->account = new UserModel($_SESSION['username'], UserModel::QUERY_USERNAME);
            if($this->account->uid){
                self::$uid = $this->account->uid;
                self::$username = $this->account->username;
                self::$powertype = 2;
                $_SESSION['username'] = self::$username;
                setcookie('username', self::$username, time()+_COOKIE_EXPIRY_, '/', HOST, _OVER_SSL_, true);
            }else{
                session_destroy();
                setcookie('username', NULL, time()-1, '/', HOST, _OVER_SSL_, true);
            }
        }elseif(isset($_COOKIE['username'])){
            // 检查是否为印象登陆
            $this->account = new UserModel(strtolower($_COOKIE['username']), UserModel::QUERY_USERNAME);
            if($this->account->uid){
                self::$uid = $this->account->uid;
                self::$username = $this->account->username;
                self::$powertype = 1;
                $this->setcookie('username', self::$username);
            }else{
                $this->setcookie('username');
            }
        }else{
            $this->account = new UserModel(NULL);
        }
        foreach($this->account as $key=>$val){
            $this->modelProperties[$key] = $val;
        }
    }

    public function getUserAccountCopy(){
        return $this->account;
    }

    public function visa(array $value = [], $private = true, $salt = '', $alias = NULL){
        if($private){
            $index = '_private_visa_for_'.AI_CURR.'_'.$salt;
        }else{
            $index = '_public_visa_for_'.AI_CURR.'_'.$salt;
        }
        $this->account->setGreenCard('VISA', AI_CURR, $value, $salt, $alias);
        $_SESSION[$index] = $value;
    }

    public function checkVisa($salt = '', $appid = AI_CURR){
        if($appid===AI_CURR){
            $index = '_private_visa_for_'.AI_CURR.'_'.$salt;
            if(!empty($_SESSION[$index])){
                return $_SESSION[$index];
            }
        }
        $index = '_public_visa_for_'.$appid.'_'.$salt;
        if(!empty($_SESSION[$index])){
            return $_SESSION[$index];
        }
        return false;
    }

    public function setcookie($key, $value = NULL, $time = _COOKIE_EXPIRY_){
        if(is_string($value)&&$value){
            setcookie($key, $value, time() + $time, '/', HOST, _OVER_SSL_, true);
        }else{
            setcookie($key, NULL, time() - 1, '/', HOST, _OVER_SSL_, true);
        }
    }

    public function abstain(){
        self::$uid = 0;
        self::$username = 'guest';
        self::$powertype = 0;
        session_destroy();
        $this->setcookie('username');
        return true;
    }
}
