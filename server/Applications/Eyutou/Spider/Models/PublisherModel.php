<?php
namespace Eyutou\Spider\Models;

final class PublisherModel extends \AF\Models\BaseR3Model {
    const
	CODE_DESC = [['code', true, self::SORT_REGULAR]],
	CODE_ASC = [['code', false, self::SORT_REGULAR]],
	BELONG_DESC = [['belong_to', true, self::SORT_REGULAR]],
	BELONG_ASC = [['belong_to', false, self::SORT_REGULAR]],
	NAME_DESC = [['name', true, self::SORT_REGULAR]],
	NAME_ASC = [['name', false, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, self::SORT_CONVERT_GBK]],
    NAME_ASC_GBK = [['name', false, self::SORT_CONVERT_GBK]];

    protected static
    $rdbConnectionIndex = 1,
    $rdbConnectionType = self::CONN_ROW,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'publishers',
    $uniqueIndexes = ['code'],
    $AIKEY = NULL,
    $fileStoragePath = true,
    $__parentFieldName = 'belong_to',
    $defaultPorpertyValues = [
        'code'      =>  '42000001',
        'grade'     =>  '1',
        'belong_to' =>  '420000',
        'name'      =>  '',
        'sponsor'   =>  '',
        'homepage'  =>  '',
        'lastid'    =>  '0',
        'reamrk'    =>  ''
    ];

    public static function getPubishersByArea($province_code = NULL, $range = [0, 0]){
        if($province_code){
            $province_code = str_pad(substr($province_code, 0, 6), 6, '0');
            $like = preg_replace('/0+$/', '%', $province_code);
            return self::query("`code` LIKE '$like'", self::CODE_ASC, $range);
        }
        return self::query([], self::CODE_ASC, $range);
    }
}