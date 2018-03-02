<?php
namespace Goods\Models;

use PM\_8\ProductionTypeModel;
use PM\_8\CategoryModel;

class Admin_TypesViewModel extends \PM\_2\BaseCRUDViewModel {
	public static
	$model = 'PM\_8\ProductionTypeModel',
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
			'field_name'	=>	'category',
			'display_name'	=>	'所属类目',
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
			'field_name'	=>	'typename',
			'display_name'	=>	'类型名称',
			'input_type'	=>	'text'
		]
	],
	$creater = [
		'name'	=>	'创建产品类目'
	];

	public function initVars(){
		return [
			'listname'	=>	'产品类型列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑产品类型信息'
		];
	}

	protected function buildTableRows($basedir, $items = []){
        $rows = [];
        foreach($items as $index=>$item){
            if($category = $item->getCategory()){
				$itemurl = $basedir.'/'.$item->id;
				$rows[] = [
					'__index'		=>	[$index + 1],
					'name'			=>	[$item->typename, $itemurl, false],
					'category'		=>	[$category->category_name],
					'__ops'			=>	['<a href="'.$itemurl.'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">删除</a>']
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