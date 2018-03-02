<?php
namespace PM\_8;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseR3Model;

class BrandModel extends BaseR3Model {

    protected static
    $staticQuerier,
    $staticTablrnamePrefix,
    $tablenameAlias = 'brands',
    $fileStoragePath = RUNPATH_CA.'GOODS/brands/',
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'  =>  0,
        'category_id'  =>  0,
        'brand_name'  =>  '',
        'brand_logo'  =>  '',
        'brand_desc'  =>  ''
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(8);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public static function byName($brandname, $category_id){

    }

    public function getCategory(){
        return CategoryModel::byGUID($this->modelProperties['category_id']);
    }
}