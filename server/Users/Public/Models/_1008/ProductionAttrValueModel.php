<?php
namespace PM\_1008;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseR3Model;

class ProductionAttrValueModel extends BaseR3Model {

    protected static
    $staticQuerier,
    $staticTablrnamePrefix,
    $tablenameAlias = 'production_attrs_map',
    $fileStoragePath = RUNPATH_CA.'GOODS/productionattrs/',
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'production_id'  =>  0,
        'attribute_id'  =>  0,
        'attribute_val'  =>  ''
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(1008);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public static function byProductionId($production_id, $returnFormat = self::LIST_AS_OBJS){
        return self::query(['production_id'=>$production_id], [['	attribute_id', false, self::SORT_REGULAR]], 0, $returnFormat);
    }

    public static function getProductionAttrs($production_id){
        $array = self::byProductionId($production_id, self::LIST_AS_ARRS);
        $modelProperties = [];
        foreach($array as $attr){
            $modelProperties[$attr['attribute_id']] = $attr['attribute_val'];
        }
        return $modelProperties;
    }
}