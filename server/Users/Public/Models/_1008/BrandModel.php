<?php
namespace PM\_1008;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseR3Model;

class BrandModel extends BaseR3Model {
    const
	ID_DESC = [['id', true, self::SORT_REGULAR]],
    ID_ASC = [['id', false, self::SORT_REGULAR]],
    SORT_DESC = [['SK_SORT_NUM', true, self::SORT_REGULAR]],
	SORT_ASC = [['SK_SORT_NUM', false, self::SORT_REGULAR]],
	CATEGORY_ID_DESC = [['category_id', true, self::SORT_REGULAR]],
	CATEGORY_ID_ASC = [['category_id', false, self::SORT_REGULAR]],
	NAME_DESC = [['brand_name', true, self::SORT_REGULAR]],
	NAME_ASC = [['brand_name', false, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['brand_name', true, self::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['brand_name', false, self::SORT_CONVERT_GBK]];

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
        'brand_desc'  =>  '',
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

    public static function getBrandsByCategory($category_id, array $orderby = [['1', false, self::SORT_REGULAR]], $range = 0, $returnFormat = self::LIST_AS_OBJS, $containChildCategories = false){
        if($category = CategoryModel::byGUID($category_id)){
            // 查询类目的所有子类目
            // 需要包含自己，所传进去的容器中，包含自己
            if($containChildCategories){
                $categories_ids = $category->getOffspringIds(true);
                $types = self::query("`category_id` IN ('".implode("','", $categories_ids)."')", $order, $range, $returnFormat);
            }else{
                $types = self::query("`category_id` = '$category_id'", $order, $range, $returnFormat);
            }
        }else{
            $types = [];
        }
    }

    public static function byName($brandname, $category_id){

    }

    public function getCategory(){
        return CategoryModel::byGUID($this->modelProperties['category_id']);
    }

    protected function __afterDelete(){
        if($this->guid){
            ProductionTypeModell::delete("`brand_id` = '$this->guid'");
            ProductionModell::delete("`brand_id` = '$this->guid'");
        }
        return $this;
    }
}