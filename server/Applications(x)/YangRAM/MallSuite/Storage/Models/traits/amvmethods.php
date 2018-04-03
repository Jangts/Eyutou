<?php
namespace Goods\Models\traits;

use PM\_1008\BrandModel;
use PM\_1008\ProductionTypeModel;

trait amvmethods {
    public static function loadBrandTabs(){
		$brands = BrandModel::getALL();
		$tabs = [];
		foreach ($brands as $brand) {
			$tabs['brand'.$brand->id] = [
				'name'	=>	$brand->brand_name,
				'title'	=>	empty($brand->brand_desc) ? $brand->brand_name : $brand->brand_desc,
				'where'	=>	['brand_id'=>$brand->id]
			];
		}
		static::$__avmtabs = $tabs;
	}

	public static function loadAllTypeTags(){
		$types = ProductionTypeModel::getALL();
		$tags = [];
		foreach ($types as $type) {
			$brand = $type->getBrand();
			$tags['type'.$type->id] = [
				'name'	=>	$brand->brand_name.' / '.$type->typename,
				'title'	=>	$brand->brand_name.', '.$type->typename,
				'where'	=>	['type_id'=>$type->id]
			];
		}
		static::$__avmtags = $tags;
	}
}