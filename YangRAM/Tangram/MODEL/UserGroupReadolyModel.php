<?php
namespace Tangram\MODEL;

use PDO;
use DBQ;
use Status;
use Storage;

/**
 * @class Tangram\MODEL\UserGroupReadolyModel
 * @readonly
 * User Account
 * 用户账号对象
 * 读取用户账号信息的对象
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
class UserGroupReadolyModel extends ObjectModel {
    private static
    $tablename = DB_REG.'usergroups',
    $staticQuerier = NULL,
    $staticFileStorage = NULL;

    final protected static function init(){
        if(self::$staticQuerier === NULL){
            self::$staticFileStorage = new Storage([
                'htype'         =>  Storage::USE_FILES,
                'path'			=>	USR_PATH.'Public/.usergroups/',
	    		'filetype'		=>	'json.ni',
		    	'expiration'	=>	0
            ], Storage::JSN);
            self::$staticQuerier = new DBQ;
            self::$staticQuerier->using(self::$tablename);
        }
    }

    final public static function getGroupsByAppID($appid){
        self::init();
        $objs = [];
        $result = self::$staticQuerier->requires("`APPID` = '$appid'")->select();
        if($result&&($pdos = $result->getIterator())){
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static;
                if($modelProperties['ALIAS']){
                    self::$staticFileStorage->store($modelProperties['ALIAS'], $modelProperties);
                }
                self::$staticFileStorage->store($modelProperties['GUID'], $modelProperties);
                $obj->__put($modelProperties);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    final public static function getGroupsOfApp($appid){
        return self::getGroupsByAppID($appid);
    }

    final public static function getGroupsByUID($uid){
        self::init();
        $objs = [];
        $result = self::$staticQuerier->requires("`TYPE` = 'USER' AND `SYMBOL` = '$uid'")->select();
        if($result&&($pdos = $result->getIterator())){
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static;
                if($modelProperties['ALIAS']){
                    self::$staticFileStorage->store($modelProperties['ALIAS'], $modelProperties);
                }
                self::$staticFileStorage->store($modelProperties['GUID'], $modelProperties);
                $obj->__put($modelProperties);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    /**
     * get Usergroup by guid
     * 
     * @access public
     * @final
     * @param string        $guid 应用内部标识符
     */
    final public static function byGUID($guid){
        self::init();
        if($modelProperties = self::$staticFileStorage->take($guid)){
            $obj = new static;
            $obj->__put($modelProperties);
            return $obj;
        }
        $result = self::$staticQuerier->requires("`GUID` = '$guid'")->select();
        if($result&&$modelProperties = $result->item()){
            $obj = new static;
            if($modelProperties['ALIAS']){
                self::$staticFileStorage->store($modelProperties['ALIAS'], $modelProperties);
            }
            self::$staticFileStorage->store($guid, $modelProperties);
            $obj->__put($modelProperties);
            return $obj;
        }
        return false;
    }

    /**
     * get Usergroup by alias
     * 
     * @access public
     * @final
     * @param string        $alias 应用内部标识符
     */
    final public static function byALIAS($alias){
        if($alias==='EveryOne'){
            return new static;
        }
        self::init();
        if($modelProperties = self::$staticFileStorage->take($alias)){
            $obj = new static;
            $obj->__put($modelProperties);
            return $obj;
        }
        $result = self::$staticQuerier->requires("`ALIAS` = '$alias'")->select();
        if($result&&$modelProperties = $result->item()){
            $obj = new static;
            self::$staticFileStorage->store($alias, $modelProperties);
            self::$staticFileStorage->store($modelProperties['GUID'], $modelProperties);
            $obj->__put($modelProperties);
            return $obj;
        }
        return false;
    }

    /**
     * get Usergroup by AppID, type and symbol
     * 
     * @access public
     * @final
     * @param string|int    $appid  应用标识
     * @param string        $type   组类型
     * @param string|null   $symbol 应用内部标识符
     */
    final public static function byAITS($appid, $type = 'CARD', $symbol = NULL){
        self::init();
        $result = self::$staticQuerier->requires([
            'APPID'     =>  $appid,
            'TYPE'     =>  $type,
            'SYMBOL'    =>  $symbol
        ])->select();
        if($result&&$modelProperties = $result->item()){
            $obj = new static;
            if($modelProperties['ALIAS']){
                self::$staticFileStorage->store($modelProperties['ALIAS'], $modelProperties);
            }
            self::$staticFileStorage->store($modelProperties['GUID'], $modelProperties);
            $obj->__put($modelProperties);
            return $obj;
        }
        return false;
    }

    protected
    $readonly = true,
    $pk = 'GUID',
    $__guid = '',
    $savedProperties = NULL,
    $modelProperties = [
        'GUID'      =>  '',
        'ALIAS'     =>  'EveryOne',
        'APPID'     =>  'USERS',
        'TYPE'      =>  'CARD',
        'SYMBOL'    =>  NULL,
        'TABLENAME' =>  NULL
    ];

    /**  
	 * 关系数据库行数据对象构造函数
	 * 
	 * @access private
     * @final
	 * @return 构造函数无返回值
	**/ 
    final private function __construct(){}

    /**  
	 * 创建属性数组
	 * 
	 * @access protected
     * @param array|object $input           源数据
     * @param bool|object $savedProperties  已存在的记录
	 * @return object
	**/ 
    protected function __put(array $input){
        $this->__guid = $input['GUID'];
        $this->modelProperties = $this->savedProperties = $input;
        $this->xml = NULL;
        return $this;
    }

    protected function put(array $input){
        $this->xml = NULL;
        if($this->savedProperties){
            $defaultPorpertyValues  = $this->savedProperties;                
        }else{
            $defaultPorpertyValues  = $this->modelProperties;
        }
        $this->modelProperties = self::correctArrayByTemplate($input, $defaultPorpertyValues);
        return $this;
    }
}