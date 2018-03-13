<?php
namespace Eyutou\Spider\Models;

final class PublisherModel extends \AF\Models\BaseR3Model {
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
}