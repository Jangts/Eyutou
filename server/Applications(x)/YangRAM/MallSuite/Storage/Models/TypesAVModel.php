<?php
namespace Goods\Models;

use PM\_1008\ProductionTypeModel;
use PM\_1008\BrandModel;

class TypesAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	public static
	$model = 'PM\_1008\ProductionTypeModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'类型名称',
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
			'field_name'	=>	'__ops',
			'display_name'	=>	'操作',
			'column_type'	=>	'',
			'classname'		=>	''	
		]
	],
	$inputs = [
		[
			'field_name'	=>	'id',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'brand_id',
			'display_name'	=>	'所属品牌',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'typename',
			'display_name'	=>	'类型名称',
			'input_type'	=>	'text'
		]
	],
	$creater = [
		'name'	=>	'创建产品类目'
	];

	public function initialize(){
		return [
			'listname'	=>	'产品类型列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑产品类型信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], array $range = [0, 0, 1], $sort = ''){
        $rows = [];
        foreach($items as $index=>$item){
            if($brand = $item->getBrand()){
				$itemurl = $basedir.'/'.$item->id;
				$rows[] = [
					'__index'		=>	[$index + 1],
					'name'			=>	[$item->typename, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
					'brand'			=>	[$brand->brand_name],
					'__ops'			=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">删除</a>']
				];
            }else{
				$item->destroy();
            }
            
        }
        return $rows;
	}
	
	protected function setSelections($item){
        $brands = BrandModel::getALL();
		foreach($brands as $brand){
			$brandOptions[] = [$brand['id'], $brand['brand_name']];
		}
		static::$selectOptions['brand_id'] = $brandOptions;
	}
}