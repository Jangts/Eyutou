<?php
namespace Eyutou\Spider\Models;

final class AreaModel extends \AF\Models\BaseDeepModel {
    const
	CODE_DESC = [['code', true, self::SORT_REGULAR]],
	CODE_ASC = [['code', false, self::SORT_REGULAR]],
	BELONG_DESC = [['belong_to', true, self::SORT_REGULAR]],
	BELONG_ASC = [['belong_to', false, self::SORT_REGULAR]],
	NAME_DESC = [['areaname', true, self::SORT_REGULAR]],
	NAME_ASC = [['areaname', false, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['areaname', true, self::SORT_CONVERT_GBK]],
    NAME_ASC_GBK = [['areaname', false, self::SORT_CONVERT_GBK]];
    
    protected static
    $rdbConnectionIndex = 1,
    $rdbConnectionType = self::CONN_ROW,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'areas',
    $uniqueIndexes = ['code'],
    $AIKEY = NULL,
    $fileStoragePath = true,
    $defaultPorpertyValues = [
        'code'          =>  '420000',
        'grade'         =>  '1',
        'belong_to'     =>  '0',
        'areaname'      =>  '',
        'iconograph'    =>  '',
        'banner'        =>  '',
        'description'   =>  ''
    ];

    public static function getCities($province_code = NULL, $range = [0, 0]){
        if($province_code){
            $province_code = substr($province_code, 0, 2).'0000';
            $requires = ['belong_to' =>  $province_code];
        }else{
            $requires = [];
        }
        $requires['grade'] = 2;
        return self::query($requires, self::CODE_ASC, $range);
    }

    public static function getCounties($province_code = NULL, $range = [0, 0]){
        if($province_code){
            $province_code = str_pad(substr($province_code, 0, 4), 6, '0');
            if($province_code!='000000'){
                $like = preg_replace('/0+$/', '%', $province_code);
                return self::query("`grade` = 3 AND `code` LIKE '$like'", self::CODE_ASC, $range);
            }
        }
        return self::query("`grade` = 3", self::CODE_ASC, $range);
    }
}