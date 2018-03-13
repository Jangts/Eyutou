<?php
namespace PM\_1008;

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
        'brand_id'  =>  0,
        'typename'  =>  ''
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(1008);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public function getBrand(){
        return BrandModel::byGUID($this->modelProperties['brand_id']);
    }

    protected function __afterDelete(){
        if($this->guid){
            ProductionModell::delete("`type_id` = '$this->guid'");
        }
        return $this;
    }
}