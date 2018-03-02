<?php
namespace PM\_8;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseR3Model;

class ProductionTypeModel extends BaseR3Model {

    protected static
    $staticQuerier,
    $staticTablrnamePrefix,
    $tablenameAlias = 'types',
    $fileStoragePath = RUNPATH_CA.'GOODS/types/',
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'  =>  0,
        'category_id'  =>  0,
        'typename'  =>  ''
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(8);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public function getCategory(){
        return CategoryModel::byGUID($this->modelProperties['category_id']);
    }
}