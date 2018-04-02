<?php
namespace PM\_1008;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseR3Model;

class ProductionTypeModel extends BaseR3Model {
    const
	ID_DESC = [['id', true, self::SORT_REGULAR]],
    ID_ASC = [['id', false, self::SORT_REGULAR]],
    SORT_DESC = [['SK_SORT_NUM', true, self::SORT_REGULAR]],
	SORT_ASC = [['SK_SORT_NUM', false, self::SORT_REGULAR]],
	BRAND_ID_DESC = [['brand_id', true, self::SORT_REGULAR]],
	BRAND_ID_ASC = [['brand_id', false, self::SORT_REGULAR]],
	NAME_DESC = [['typename', true, self::SORT_REGULAR]],
	NAME_ASC = [['typename', false, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['typename', true, self::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['typename', false, self::SORT_CONVERT_GBK]];

    protected static
    $staticQuerier,
    $staticTablrnamePrefix,
    $tablenameAlias = 'types',
    $fileStoragePath = RUNPATH_CA.'GOODS/types/',
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'  =>  0,
        'brand_id'  =>  0,
        'typename'  =>  '',
        'SK_SORT_NUM'    =>  0
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(1008);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public static function getTypesByBrand($brand_id, array $orderby = [['1', false, self::SORT_REGULAR]], $range = 0, $returnFormat = self::LIST_AS_OBJS){
        if($brand = BrandModel::byGUID($brand_id)){
            return ProductionTypeModel::query("`brand_id` = '$brand_id'", $orderby, $range, $returnFormat);
        }
        return [];
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