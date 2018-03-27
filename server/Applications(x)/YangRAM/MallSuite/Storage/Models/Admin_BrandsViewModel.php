<?php
namespace Goods\Models;

use PM\_1008\BrandModel;
use PM\_1008\CategoryModel;

class Admin_BrandsViewModel extends \PM\_STUDIO\BaseCRUDViewModel {
	public static
	$model = 'PM\_1008\BrandModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'品牌名称',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'category',
			'display_name'	=>	'所属类目',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'desc',
			'display_name'	=>	'类目描述',
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
			'field_name'	=>	'category_id',
			'display_name'	=>	'所属类目',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'brand_name',
			'display_name'	=>	'品牌名称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'brand_logo',
			'display_name'	=>	'品牌标识',
			'input_type'	=>	'banner'
		],
		[
			'field_name'	=>	'brand_desc',
			'display_name'	=>	'品牌描述',
			'input_type'	=>	'textarea'
		]
	],
	$creater = [
		'name'	=>	'创建品牌'
	];

	public function initVars(){
		return [
			'listname'	=>	'品牌列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑品牌信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], array $range = [0, 0, 1], $sort = ''){
        $rows = [];
        foreach($items as $index=>$item){
			if($category = $item->getCategory()){
				$itemurl = $basedir.'/'.$item->id;
				$rows[] = [
					'__index'		=>	[$index + 1],
					'name'			=>	[$item->brand_name, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
					'category'		=>	[$category->category_name],
					'desc'		=>	[$item->brand_desc],
					'__ops'			=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">删除</a>']
				];
            }else{
				$item->destroy();
			}
        }
        return $rows;
	}
	
	protected function setSelections($item){
        $categories = CategoryModel::getALL();
		foreach($categories as $category){
			$categoryOptions[] = [$category['id'], $category['category_name']];
		}
		static::$selectOptions['category_id'] = $categoryOptions;
	}
}