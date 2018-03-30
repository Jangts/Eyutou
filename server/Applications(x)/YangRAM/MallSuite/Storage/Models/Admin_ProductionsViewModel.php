<?php
namespace Goods\Models;

use PM\_1008\ProductionModel;
use PM\_1008\BrandModel;
use PM\_1008\ProductionTypeModel;

class Admin_ProductionsViewModel extends \PM\_STUDIO\BaseTableViewModel {
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
		'brand'				=>	ProductionModel::BRAND_ASC,
		'brand_reverse'		=>	ProductionModel::BRAND_DESC,
		'type'				=>	ProductionModel::TYPE_ASC,
		'type_reverse'		=>	ProductionModel::TYPE_DESC,
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

	public function initialize(){
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
		$productions  = ProductionModel::getRows(NULL, NULL, ProductionModel::ALL, $orderby, $range);

		$rows = [];
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/p/production/';

		if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = '';
        }
		foreach($productions as $index=>$production){
			if($production->category_id&&$production->brand&&$production->type){
				$itemurl = $basedir.$production->id;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'name'		=>	[$production->name, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
				'brand'		=>	[$production->brand['brand_name']],
				'type'		=>	[$production->type['typename']],
				'time'		=>	[$production->time_onsale],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/1008/productions/'.$production->id.'" href="javascript:;">移除</a>']
			];
			}else{
				$production->destroy();
			}
		}
		
		self::$creater['url'] = $basedir;

		$this->assign('classtabs', 	self::buildTabs($stagedir.'/p/productions/'));
		$this->assign('itemlist', 	self::buildTable($rows, $range[2]));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}