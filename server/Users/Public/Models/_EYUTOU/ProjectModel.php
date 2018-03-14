<?php
namespace PM\_EYUTOU;

final class ProjectModel extends \AF\Models\BaseR3Model {
    protected static
    $rdbConnectionIndex = 1,
    $rdbConnectionType = self::CONN_ROW,
    $tablenamePrefixRewritable = true,
    $tablenamePrefix = _DBPRE_.'eyutou_',
    $tablenameAlias = 'projects',
    $uniqueIndexes = ['id'],
    $AIKEY = 'id',
    $fileStoragePath = true,
    $defaultPorpertyValues = [
        'id'            =>  NULL,
        'publisher'     =>  '',
        'pubcode'       =>  '',
        'oid'           =>  '',
        'src'           =>  '',
        'title'         =>  '',
        'description'   =>  '',
        'time_publish'  =>  '',
        'time_create'   =>  '',
        'time_start'    =>  '',
        'time_end'      =>  '',
        'time_deadline' =>  '',
        'remark'        =>  ''
    ],
    $constraints = [
        'publisher'     =>  1,
        'oid'           =>  'a',
        'src'           =>  'a',
        'title'         =>  'a',
        'time_publish'  =>  't',
        'time_create'   =>  't',
        'time_start'    =>  't',
        'time_end'      =>  't',
        'time_deadline' =>  't',
    ];
}