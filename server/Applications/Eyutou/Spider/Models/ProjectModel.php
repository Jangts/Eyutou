<?php
namespace Eyutou\Spider\Models;

final class ProjectModel extends \AF\Models\BaseMapModel {
    protected static
    $rdbConnectionIndex = 1,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'projects',
    $uniqueIndexes = [],
    $AIKEY = NULL,
    $fileStoragePath = false,
    $jointIndexes = ['publisher', 'oid'],
    $defaultPorpertyValues = [
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
    ];
}