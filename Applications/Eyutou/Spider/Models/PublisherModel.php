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
    $defaultPorpertyValues = [
        'code'      =>  '42000001',
        'grade'     =>  '1',
        'name'      =>  '',
        'sponsor'   =>  '',
        'homepage'  =>  '',
        'lastid'    =>  '0',
        'reamrk'    =>  ''
    ];
}