<?php
namespace Pages\Main\Plugins\goods\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_PAGES\ColumnModel;

use PM\_1008\ProductionModel;
use PM\_1008\CategoryModel;
use PM\_1008\BrandModel;
use PM\_1008\TypeModel;
use PM\_1008\OptionsModel as ProductionOptionsModel;

use Pages\Main\Models\FrontPageViewModel;
use Pages\Main\Models\OptionsModel;

class ProductionsPageController extends \Controller {
    public function main(){
        $options = OptionsModel::autoloadItems();
        ProductionOptionsModel::__correctTablePrefix(new \App(1008));
        $pro_options = ProductionOptionsModel::autoloadItems();
        // var_dump($pro_options);
        // exit;

        $column = new ColumnModel('link_productions/');
        // $column->push('link_productions/');
        
        // $productions = ProductionModel::getRows(1, 1, ProductionModel::ONSALE, ProductionModel::ID_DESC);
        
        $renderer = new FrontPageViewModel();

        // if(count($productions)){
        //     $renderer->assign("title", $productions[0]->type['typename'] . ' | ' . $productions[0]->category['category_name'] . ' | ' . $productions[0]->brand['brand_name']);
        // }else{
        //     $category = CategoryModel::byGUID(1);
        //     $brand = BrandModel::byGUID(1);
        //     $type = TypeModel::byGUID(1);
        //     $renderer->assign("title", $type->typename . ' | ' . $category->category_name . ' | ' . $brand->brand_name);
        // }
		
        $renderer->assign($options, "option_");
        $renderer->assign($pro_options, "pro_option_");
        $renderer->assign("column", $column);
        // $renderer->assign("list", $productions);
	
        $renderer->using($options['use_theme']);

		$renderer->display($pro_options['tamplate_use_in_pagesplugin']);
    }
}