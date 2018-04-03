<?php
namespace Goods\Models;

use PM\_1008\CategoryModel;

class CategoriesAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	public static
	$model = 'PM\_1008\CategoryModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'类目名称',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'parent',
			'display_name'	=>	'父级类目',
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
			'field_name'	=>	'category_name',
			'display_name'	=>	'产品类目名称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'parent_id',
			'display_name'	=>	'产品类目父级',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'category_desc',
			'display_name'	=>	'产品类目描述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'SK_SORT_NUM',
			'display_name'	=>	'排序序数',
			'input_type'	=>	'counter'
		]
	],
	$creater = [
		'name'	=>	'创建产品类目'
	];

	public function initialize(){
		return [
			'listname'	=>	'产品类目列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑产品类目信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], $qs = ''){
        $rows = [];
        foreach($items as $index=>$item){
            if($parentobj = $item->getParentObject()){
                $parent = [$parentobj->category_name, $basedir.$parentobj->id, false];
            }else{
                $parent = ['-'];
            }
            $itemurl = $basedir.'/'.$item->id;
            $rows[] = [
                '__index'		=>	[$index + 1],
                'name'		=>	[$item->category_name, $itemurl.$qs, false],
                'parent'	=>	$parent,
                'desc'		=>	[$item->description],
                '__ops'		=>	['<a href="'.$itemurl.$qs .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">删除</a>']
            ];
        }
        return $rows;
	}
	
	protected function setSelections($item){
        $items = $item->getUsableParents();

		$parentOptions = [['0', '无父级页面']];
		foreach($items as $parent){
			if($parent['id']){
				$parentOptions[] = [$parent['id'], str_repeat('——', $parent->__level) . ' | ' . $parent['category_name']];
			}
		}
		static::$selectOptions['parent_id'] = $parentOptions;
	}
}