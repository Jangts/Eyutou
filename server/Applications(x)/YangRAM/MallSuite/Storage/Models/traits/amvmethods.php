<?php
namespace Goods\Models\traits;

use PM\_1008\BrandModel;

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

}