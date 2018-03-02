<?php
namespace Goods\Models;

use PM\_8\ProductionModel;
use PM\_8\BrandModel;
use PM\_8\ProductionTypeModel;

class Admin_ProductionsViewModel extends \PM\_2\BaseTableViewModel {
	public static
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'sorting_name'	=>	'name_gb',
			'display_name'	=>	'产品标题',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'brand',
			'display_name'	=>	'所属品牌',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'type',
			'display_name'	=>	'产品分类',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'time',
			'sorting_name'	=>	'ptime',
			'display_name'	=>	'上架时间',
			'column_type'	=>	'',
			'classname'		=>	'',
			'default'		=>	'未上架'	
		],
		[
			'field_name'	=>	'__count',
			'display_name'	=>	'点击量',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__ops',
			'display_name'	=>	'操作',
			'column_type'	=>	'',
			'classname'		=>	''	
		]
	],
	$creater = [
		'name'	=>	'发布新品'
	],
	$__sorts = [
		'id'				=>	ProductionModel::ID_ASC,
		'id_reverse'		=>	ProductionModel::ID_DESC,
		'ctime'				=>	ProductionModel::CTIME_ASC,
		'ctime_reverse'		=>	ProductionModel::CTIME_DESC,
		'ptime'				=>	ProductionModel::TIME_ASC,
		'ptime_reverse'		=>	ProductionModel::TIME_DESC,
		'rank'				=>	ProductionModel::RANK_ASC,
		'rank_reverse'		=>	ProductionModel::RANK_DESC,
		'name'				=>	ProductionModel::NAME_ASC,
		'name_reverse'		=>	ProductionModel::NAME_DESC,
		'name_gb'			=>	ProductionModel::NAME_ASC_GBK,
		'name_gb_reverse'	=>	ProductionModel::NAME_DESC_GBK
	],
	$__sortby = ProductionModel::ID_DESC,
	$__picks = [
        'pickall'  =>  ProductionModel::ALL,
	    'onsale'  =>  ProductionModel::ONSALE,
	    'instorage'  =>  ProductionModel::INSTORAGE,
    ];

	public function initVars(){
		return [
			'listname'	=>	'产品列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		$count = ProductionModel::getCOUNT(ProductionModel::ONSALE);
		// var_dump($range, $orderby);
		// exit;
		$productions  = ProductionModel::getRows(NULL, NULL, ProductionModel::ALL, $orderby, $range);

		// var_dump($productions);
		// exit;

		// $type = NULL, $getsubtypes = false, $brand = NULL, $getsubbrands = false, $range = 0, $category_id = NULL, $getsubcategories = false)

		$rows = [];
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->ID;
		foreach($productions as $index=>$production){
			if($production->category_id&&$production->brand&&$production->type){
				$stage_url = $stagedir.'/Production/'.$production->id;
				// $brand = BrandModel::byGUID($production->brand_id);
				// $type = ProductionTypeModel::byGUID($production->type_id);
			$rows[] = [
				'__index'		=>	[$index + 1],
				'name'		=>	[$production->name, $stage_url, false],
				'brand'		=>	[$production->brand['brand_name']],
				'type'		=>	[$production->type['typename']],
				'time'		=>	[$production->time_onsale],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$stage_url.'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/8/productions/'.$production->id.'" href="javascript:;">移除</a>']
			];
			}else{
				$production->destroy();
			}
		}
		self::$creater['url'] = $stagedir.'/Production/';


		$this->assign('itemlist', self::buildTable($rows, $range[2]));
		$this->assign('pagelist', self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}