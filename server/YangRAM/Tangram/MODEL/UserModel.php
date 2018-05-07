<?php
namespace Tangram\MODEL;

use DBQ;
use Status;
use Storage;

/**
 * @class Tangram\MODEL\UserModel
 * @readonly
 * User Account
 * 用户账号对象
 * 读取用户账号信息的对象
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class UserModel extends ObjectModel {
    const
    CREATE_NEW = -1,
    QUERY_ALL = 0,
    QUERY_UID = 1,
    QUERY_USERNAME = 2,
    QUERY_UNICODENAME = 3;

    private static
    $tablename = DB_REG.'users',
    $registerable = true;

    private
    $uid = '0',
    $querier = NULL,
    $files = NULL,
    $ystemProperties = [
        'uid'           =>  '-7',
        'username'      =>  'system',
        'nickname'      =>  'System',
        'unicodename'   =>  'System',
        'avatar'        =>  __aurl__.'uploads/files/d78cf72c9a8f4731217.jpg',
        'regtime'       =>  DATETIME,
        'lasttime'      =>  DATETIME,
        'remark'        =>  'YangRAM System'
    ];

    protected
    $readonly = true, 
    $encodePassWord = NULL,
    $modelProperties = [
        'uid'           =>  '0',
        'username'      =>  'guest',
        'nickname'      =>  'Guest',
        'unicodename'   =>  'Guest',
        'avatar'        =>  __aurl__.'uploads/files/ca28525a8b386236136.jpg',
        'regtime'       =>  '1'.DATETIME,
        'lasttime'      =>  DATETIME,
        'remark'        =>  ''
    ];

    private static function trim($modelProperties){
        unset($modelProperties['password']);
        // unset($modelProperties['authorization_code']);
        unset($modelProperties['status']);
        return $modelProperties;
    }
    
    public static function getALL(): array {
		#
		return false;
    }
    
    public static function getCurrentUser(){
        return UserPassportModel::getUserAccount();
    }

    public function __construct($identification = NULL, $method = self::QUERY_USERNAME){
        if($identification){
            $this->querier = new DBQ;
            $this->querier->using(self::$tablename);
            switch($method){
                case self::CREATE_NEW:
                $this->readonly = false;
                $account = $identification;
                break;
                case self::QUERY_ALL:
                $account = self::queryByIJ($identification);
                break;
                case self::QUERY_UID:
                $account = self::queryById($identification);
                break;
                case self::QUERY_USERNAME:
                $account = self::queryByUN($identification);
                break;
                case self::QUERY_UNICODENAME:
                $account = self::queryByUC($identification);
                break;
                default:
                new Status(1415, 'Parameters Error', true);

            }
            if($account){
                if(isset($account['password'])){
                    $this->encodePassWord = $account['password'];
                }
                $this->writeUserData('baseinfo', $this->modelProperties = self::trim($account));
            }
            $this->uid = $this->modelProperties['uid'];
        }
    }

    public function checkPassWord($password, $rebuildPassport = true){
        if($this->uid){
            if($this->encodePassWord){
                if(md5(hash('sha256', $password)) === $this->encodePassWord){
                    if($rebuildPassport){
                        $_SESSION['username'] = $this->modelProperties['username'];
                        UserPassportModel::init(true);
                    }
                    return true;
                }
            }else{
                if($row=$this->querier->requires()->where('username', $this->modelProperties['username'])->where('password', md5(hash('sha256', $password)))->select('password')->item()){
                    $this->encodePassWord = $row['password'];
                    if($rebuildPassport){
                        $_SESSION['username'] = $this->modelProperties['username'];
                        UserPassportModel::init(true);
                    }
                    return true;
                }
            }
        }
        if($rebuildPassport){
            unset($_SESSION['username']);
            UserPassportModel::init(true);
        }
        return false;
    }

    public function isA($gid){
        if($gid==='EveryOne'||$this->uid==='-7'){
            return [
                'uid'           =>  $this->uid,
                'username'      =>  $this->modelProperties['username'],
                'nickname'      =>  $this->modelProperties['nickname'],
                'avatar'        =>  $this->modelProperties['avatar']
            ];
        }
        $this->initFileStorage($this->modelProperties['username']);
        if($info = $this->files->read('.usergroups/'.$gid)){
            return $info;
        }
        $ug = UserGroupReadolyModel::byGUID($gid);
        if(!$ug){
            $ug = UserGroupReadolyModel::byALIAS($gid);
        }
        if(!$ug){
            return false;
        }
        switch($ug->TYPE){
            case 'USER':
            if($this->uid === $ug->SYMBOL){
                $info = $this->getBaseInfo();
                break;
            }
            return false;

            case 'CARD':
            if($ug->SYMBOL){
                $table = _DBPRE_.$ug->TABLENAME;
                // 如果数据表为公共表
                if($table === DB_PUB.'usergroupmap'){
                    $info = DBQ::one($table, "`uid` = '".$this->uid."' AND `usergroup` = '".$ug->SYMBOL."' AND `appid` = '".$ug->APPID."'");
                }else{
                    $info = DBQ::one($table, "`uid` = '".$this->uid."' AND `usergroup` = '".$ug->SYMBOL."'");
                }
            }else{
                $info = DBQ::one(_DBPRE_.$ug->TABLENAME, "`uid` = '".$this->uid."'");
            }
            if($info){
                break;
            }

            default:
            return false;
        }
        return $this->setGreenCard($ug->TYPE, $ug->APPIDL, [
            'uid'           =>  $info['uid'],
            'nickname'      =>  $info['nickname'],
            'avatar'        =>  $info['avatar'],
            'username'      =>  $info['username']
        ], $ug->SYMBO, $ug->ALIAS);
    }

    public function getBaseInfo(){
        return [
            'uid'           =>  $this->uid,
            'nickname'      =>  $this->modelProperties['nickname'],
            'avatar'        =>  $this->modelProperties['avatar'],
            'username'      =>  $this->modelProperties['username']
        ];
    }

    /**
     * Intelligently Judge
     */
    private function queryByIJ($identification){
        if(is_numeric($identification)){
            return self::queryByID($identification);
        }
        if(is_string($identification)){
            if(preg_match('/^[0-9a-zA-Z]+$/', $identification)){
                if($account = self::queryByUN($identification)){
                    return $account;
                }
            }
            if(preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/', $identification)){
                if($account = self::queryByEM($identification)){
                    return $account;
                }
            }
            if(preg_match('/^\+\d+$/', $identification)){
                if($account = self::queryByMP($identification)){
                    return $account;
                }
            }
            return self::queryByUC($identification);
        }
        return false;
    }

    private function queryById($uid){
        $this->querier->requires()->where('uid', $uid);
        return $this->querier->select()->item();
    }

    private function queryByUN($username){
        $username = strtolower($username);
        $this->initFileStorage($username);
        if($username==='system'){
            return $this->ystemProperties;
        }
        if($modelProperties = $this->readUserData('baseinfo')){
            return $modelProperties;
        }
        $this->querier->requires()->where('username', $username);
        return $this->querier->select()->item();
    }

    private function queryByEM($email){
        $this->querier->requires()->where('email', $email);
        return $this->querier->select()->item();
    }

    private function queryByMP($mobile){
        $this->querier->requires()->where('mobile', $mobile);
        return $this->querier->select()->item();
    }

    private function queryByUC($unicodename){
        $this->querier->requires()->where('unicodename', $unicodename);
        return $this->querier->select()->item();
    }

    private function initFileStorage($username){
        if($this->files===NULL){
            $this->files = new Storage([
                'htype'         =>  Storage::USE_FILES,
                'path'			=>	USR_PATH.'@'.$username.'/',
	    		'filetype'		=>	'json.ni',
		    	'expiration'	=>	0
            ], Storage::JSN, true);
        }
        return $this;
    }

    public function readUserData($index = 'baseinfo'){
        $this->initFileStorage($this->modelProperties['username']);
		return $this->files->read('_USERS/'.hash('md4', $index));
	}

	public function readAppCache($appid, $index){
        $this->initFileStorage($this->modelProperties['username']);
		return $this->files->read('_'.$appid.'/'.hash('md4', $index));
	}

	public function readDocument($index, $time = 0){
        $this->initFileStorage($this->modelProperties['username']);
		return $this->files->read('Documents/'.hash('md4', $index));
    }
    
    public function writeUserData($index = 'baseinfo', $value){
        $this->initFileStorage($this->modelProperties['username']);
        $this->files->write('_USERS/'.hash('md4', $index), $value);
        return $value;
	}

	public function writeAppCache($appid, $index, $value){
        $this->initFileStorage($this->modelProperties['username']);
        $this->files->write('_'.$appid.'/'.hash('md4', $index), $value);
        return $value;
	}

	public function writeDocument($index, $value){
        $this->initFileStorage($this->modelProperties['username']);
        $this->files->write('Documents/'.hash('md4', $index), $value);
        return $value;
    }

    public function setGreenCard($type = 'CARD', $appid = 'USERS', array $value = [], $salt = '', $alias = NULL){
        $this->initFileStorage($this->modelProperties['username']);
        $value['username'] = $this->modelProperties['username'];
		if($alias){
            $this->files->write('.usergroups/'.$alias, $value);
        }
        $this->files->write('.usergroups/'.'#'.$appid.'_'.$type.$salt, $value);
        return $value;
    }
}