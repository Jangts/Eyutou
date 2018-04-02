<?php
namespace Goods\Models;

use PM\_1008\CategoryModel;

class AttributesAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	public static
	$model = 'PM\_1008\CategoryAttrModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'属性名称',
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
			'field_name'	=>	'attribute_name',
			'display_name'	=>	'属性键名',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'attribute_alias',
			'display_name'	=>	'属性别名',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'attribute_type',
			'display_name'	=>	'约束类型',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'max_value_length',
			'display_name'	=>	'值最大长度',
			'input_type'	=>	'counter'
		],
		[
			'field_name'	=>	'max_float_length',
			'display_name'	=>	'最大浮点长度',
			'input_type'	=>	'counter'
		],
		[
			'field_name'	=>	'values_range',
			'display_name'	=>	'可选值',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'default_value',
			'display_name'	=>	'首选值',
			'input_type'	=>	'textarea'
		]
	],
	$creater = [
		'name'	=>	'创建属性'
	],
	$selectOptions = [
		'attribute_type'	=>	[
			['string',					'文本'],
			['stringwithnull',			'可空文本'],
			['ip'    ,					'IP地址'],
			['url'   ,					'URL地址'],
			['char'  ,					'短文本(255)'],
			['varchar',					'短文本(65532)'],
			['date'  ,					'日期'],
			['time'  ,					'时间'],
			['email' ,					'Email地址'],
			['imgtext',					'base64文本图片'],
			['longtext',				'长文本(长度设置无效)'],
			['datetime',				'日期时间'],
			['numeric',					'数或数字'],
			['numericwithnull',			'可空数或数字'],
			['percentage',				'百分比'],
			['is'    ,					'布尔值(0/1)'],
			['dayofweek' ,				'星期序号'],
			['json'  ,					'JSON对象'],
			['bin'   ,					'二级制数字'],
			['hex'   ,					'16进制数字'],
			['color' ,					'16进制RGB颜色'],
			['tags'  ,					'标签组'],
			['files' ,					'文件组'],
			['radio' ,					'单选'],
			['checkbox',				'复选']
		]
	];

	public function initialize(){
		return [
			'listname'	=>	'属性列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑属性信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], array $range = [0, 0, 1], $sort = ''){
        $rows = [];
        foreach($items as $index=>$item){
			if($category = $item->getCategory()){
				$itemurl = $basedir.'/'.$item->id;
				$rows[] = [
					'__index'		=>	[$index + 1],
					'name'			=>	[$item->attribute_alias, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
					'category'		=>	[$category->category_name],
					'desc'			=>	[$item->brand_desc],
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