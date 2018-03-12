<?php
namespace AF\Models;

use Passport;

/**
 * @class AF\Models\BaseAuthorityModel;
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
abstract class BaseAuthorityModel extends BaseMapModel {

    public static function getAdminGroups(array $options = []){
        return ['Administrators'];
    }

    public static function getCreatorGroups(array $options = []){
        return static::getMenderGroups($options);
    }

    public static function getReaderGroups(array $options = []){
        return ['EveryOne'];
    }

    public static function getMenderGroups(array $options = []){
        return ['Users'];
    }

    public static function getKillerGroups(array $options = []){
        return static::getAdminGroups($options);
    }

    final public static function can($type, array $options = []){
        switch ($type) {
            case 'C':
            $usergroups = static::getCreatorGroups($options);
            break;

            case 'R':
            $usergroups = static::getReaderGroups($options);
            break;

            case 'U':
            $usergroups = static::getMenderGroups($options);
            break;

            case 'D':
            $usergroups = static::getKillerGroups($options);
            break;

            case 'A':
            $usergroups = static::getAdminGroups($options);
            break;
            
            default:
            $usergroups = ['System Operators'];
        }
        foreach($usergroups as $usergroup){
            if(empty($options['__check_in_strict_mode'])){
                $strictMode =  false;
            }else{
                $strictMode = true;
            }
            if(Passport::inGroup($usergroup, $strictMode)){
                return true;
            }
        }
        return false;
    }
}