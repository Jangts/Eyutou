<?php
namespace PM\_1008;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseDeepModel;

class CategoryModel extends BaseDeepModel {

    protected static
    $staticQuerier,
    $staticTablrnamePrefix,
    $tablenameAlias = 'categories',
    $fileStoragePath = RUNPATH_CA.'GOODS/categories/',
    $fileStoreLifetime = 0,
    $__parentFieldName = 'parent_id',
    $defaultPorpertyValues  = [
        'id'  =>  0,
        'parent_id'  =>  0,
        'category_name'  =>  '',
        'category_desc'  =>  '',
        'SK_RANK'    =>  0
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(1008);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public function getOffspringIds($containSelf = false){
        $categories_ids = [];
        if($containSelf){
            $container = [$this];
        }else{
            $container = [];
        }
        $categories = $this->getOffspring($container, 0, $this->__guid);
        foreach($categories as $category){
            $categories_ids[] = $category->id;
        }
        return $categories_ids;
    }

    protected function __afterDelete(){
        if($this->guid){
            BrandModel::delete("`category_id` = '$this->guid'");
            ProductionModell::delete("`category_id` = '$this->guid'");
        }
        return $this;
    }
}