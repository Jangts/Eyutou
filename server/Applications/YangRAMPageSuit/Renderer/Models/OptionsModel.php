<?php
namespace Pages\Main\Models;

// 引入相关命名空间，以简化书写
use AF\Models\BaseR3Model;

class OptionsModel extends BaseR3Model {

    protected static
    $rdbConnectionIndex = CACAC,
    $rdbConnectionType = 0,
    $tablenamePrefix = CACAT,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'options',
    $uniqueIndexes = ['option_name'],
    $AIKEY = NULL,
    $fileStoragePath = true,
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'option_name'  =>  '',
        'option_value'  =>  '',
        'autoload'  =>  1
    ];

    public static function autoloadItems(){
        return self::select('option_value', true);
    }

}