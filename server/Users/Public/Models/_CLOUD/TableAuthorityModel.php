<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class TableAuthorityModel extends \AF\Models\BaseAuthorityModel {
    const
    ID_DESC = [['name', true, self::SORT_REGULAR]],
	ID_ASC = [['name', false, self::SORT_REGULAR]],
    TABLE_DESC = [['tablename', true, self::SORT_REGULAR]],
    TABLE_ASC = [['tablename', false, self::SORT_REGULAR]];
    
    protected static
    $fileStoragePath = true,
    $tablenamePrefixRewritable = true,
    $tablenamePrefix = DB_YUN,
    $tablenameAlias = 'authorities',
    $uniqueIndexes = ['id'],
    $AIKEY = 'id',
    $jointIndexes = ['tablename','auth_type','usergroup'],
    $defaultPorpertyValues = [
        'id'				=>	0,
        'tablename'         =>  '',
        'auth_type'			=>	'W',
        'usergroup'     	=>	'Users'
    ];

    public static function getAdminGroups(array $options = []) : array {
        $usergroups = self::getUserGroupsByType('A', $options['tablename']);
        if(!in_array('Administrators', $usergroups)){
            $usergroups[] = 'Administrators';
        }
        return $usergroups;
    }

    public static function getCreatorGroups(array $options = []) : array {
        $usergroups = self::getUserGroupsByType('C', $options['tablename']);
        if(empty($usergroups)){
            return static::getMenderGroups($options);
        }
        $admingroups = static::getAdminGroups($options);
        return array_unique(array_merge($admingroups, $usergroups));
    }

    public static function getReaderGroups(array $options = []) : array {
        $usergroups = self::getUserGroupsByType('R', $options['tablename']);
        if(empty($usergroups)){
            return ['EveryOne'];
        }
        $admingroups = self::getAdminGroups($options);
        return array_unique(array_merge($admingroups, $usergroups));
        
    }

    public static function getMenderGroups(array $options = []) : array {
        $usergroups1 = self::getUserGroupsByType('U', $options['tablename']);
        $usergroups2 = self::getUserGroupsByType('W', $options['tablename']);
        if(empty($usergroups1)&&empty($usergroups2)&&empty($options['__readonly'])){
            return ['Users'];
        }
        $admingroups = self::getAdminGroups($options);
        return array_unique(array_merge($admingroups, $usergroups1, $usergroups2));
    }

    public static function getKillerGroups(array $options = []) : array {
        $usergroups = self::getUserGroupsByType('D', $options['tablename']);
        if(empty($usergroups)){
            $usergroups = self::getUserGroupsByType('W', $options['tablename']);
        }
        $admingroups = self::getAdminGroups($options);
        return array_unique(array_merge($admingroups, $usergroups));
    }

    public static function getUserGroupsByType($auth_type, $tablename){
        $index = $tablename.'/'.$auth_type;
        $staticFileStorage = self::getFileStorage();
        if($usergroups = $staticFileStorage->take($index)){
            return $usergroups;
        }
        $usergroups = [];
        if(is_string($auth_type)&&is_string($tablename)){
            $objs = self::query("`auth_type` = '$auth_type' AND `tablename` = '$tablename'", self::TABLE_ASC, self::LIST_AS_ARRS, 'usergroup');
            foreach ($objs as $obj) {
                $usergroups[] = $obj['usergroup'];
            }
        }
        $staticFileStorage->store($index, $usergroups);
        return $usergroups;
    }

    protected static function __checkPostData(array $input) : array {
        $input = self::correctArrayByTemplate($input, static::$defaultPorpertyValues);
        if(!in_array($input['auth_type'], ['A', 'C', 'R', 'U', 'D', 'W', 'P'])){
            $input['auth_type'] = 'W';
        }
        if(!TableMetaModel::byGUID($input['tablename'])){
            new Status(1415, '', 'invalid tablename', true);
        }
    }

    public static function joinKeys2guid(array $keys, bool $pickfirst = false){
        // 检查是否存在联合索引键组
        if($pickfirst){
            $keys = static::pickKeys($keys);
        }
        if(count($keys)===count(static::$jointIndexes)){
            return join('/', $keys);
        }
        new Status(1415, true);        
    }

    public static function getAuthsByType($auth_type, $tablename){
        if(is_string($auth_type)&&is_string($tablename)){
            $objs = self::query("`auth_type` = '$auth_type' AND `tablename` = '$tablename'", self::TABLE_ASC);
            if($grouping){
                $groups = [];
                foreach ($objs as $obj) {
                    $index = $obj->auth_type;
                    if(!isset($groups[$index])){
                        $groups[$index] = [];
                    }
                    $groups[$index][] = $obj;
                }
                return $groups;
            }
            return $objs;
        }
        return [];
    }

    public static function getAuthsOfTable($tablename, $grouping = false){
        if(is_string($tablename)){
            $objs = self::query("`tablename` = '$tablename'", self::TABLE_ASC);
            if($grouping){
                $groups = [];
                foreach ($objs as $obj) {
                    $index = $obj->auth_type;
                    if(!isset($groups[$index])){
                        $groups[$index] = [];
                    }
                    $groups[$index][] = $obj;
                }
                return $groups;
            }
            return $objs;
        }
        return [];
    }

    protected function __afterDelete() : bool{
        $index = $this->tablename.'/'.$this->auth_type;
        $this->files->store($index);
        return true;
    }
}
// $auths = TableAuthorityModel::getAuthsOfTable($options["tables"]);
// $auth1 = TableAuthorityModel::byGUID([
//     'tablename'         =>  'news',
//     'auth_type'			=>	'A',
//     'usergroup'     	=>	'EveryOne'
// ]);
// $auth2 = TableAuthorityModel::postIfNotExists([
//     'tablename'         =>  'news',
//     'auth_type'			=>	'R',
//     'usergroup'     	=>	'EveryOne'
// ]);
// $auth3 = TableAuthorityModel::post([
//     'tablename'         =>  'cloudnotes',
//     'auth_type'			=>	'A',
//     'usergroup'     	=>	'Administrators'
// ]);
// $groups = [
//     'A' =>  TableAuthorityModel::getUserGroupsByType('A', 'news'),
//     'C' =>  TableAuthorityModel::getUserGroupsByType('C', 'news'),
//     'R' =>  TableAuthorityModel::getUserGroupsByType('R', 'news')
// ];