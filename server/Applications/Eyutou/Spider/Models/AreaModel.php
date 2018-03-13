<?php
namespace Eyutou\Spider\Models;

final class AreaModel extends \AF\Models\BaseDeepModel {
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
        'iconograph'    =>  '0',
        'description'   =>  ''
    ];
}