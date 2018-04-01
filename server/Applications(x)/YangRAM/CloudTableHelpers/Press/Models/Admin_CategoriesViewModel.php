<?php
namespace CTH\Press\Models;

use Request;
use PM\_CLOUD\TRGroupModel;

class Admin_CategoriesViewModel extends \PM\_STUDIO\BaseCRUDViewModel {
	public static
	$model = 'PM\_CLOUD\TRGroupModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'分类名称',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'parent',
			'display_name'	=>	'父级目录',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'desc',
			'display_name'	=>	'分类描述',
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
			'field_name'	=>	'tablename',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'分类名称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'parent',
			'display_name'	=>	'分类父级',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'description',
			'display_name'	=>	'分类描述',
			'input_type'	=>	'textarea'
		]
	],
	$creater = [
		'name'	=>	'创建分类'
	];

	protected static function __viewWhere(){
		$options = Request::instance()->INPUTS->__get;
		return [
			'tablename'			=>	'news',
			'SK_IS_RECYCLED'	=>	0
		];
	}
	
	protected static function __createTemplate($modelname){
        return TRGroupModel::create([0, '', 'news']);
    }

	public function initialize(){
		return [
			'listname'	=>	'新闻分类列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑新闻分类信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], array $range = [0, 0, 1], $sort = ''){
        $rows = [];
        foreach($items as $index=>$item){
            if($parentobj = $item->getParentObject()){
                $parent = [$parentobj->name, $basedir.$parentobj->id, false];
            }else{
                $parent = ['-'];
            }
            $itemurl = $basedir.'/'.$item->id;
            $rows[] = [
                '__index'		=>	[$index + 1],
                'name'		=>	[$item->name, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
                'parent'	=>	$parent,
                'desc'		=>	[$item->description],
                '__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">移除</a>']
            ];
        }
        return $rows;
	}
	
	protected function setSelections($item){
        $items = $item->getUsableParents();

		$parentOptions = [['0', '无父级页面']];
		foreach($items as $parent){
			if($parent['id']){
				$parentOptions[] = [$parent['id'], str_repeat('——', $parent->__level) . ' | ' . $parent['name']];
			}
		}
		static::$selectOptions['parent'] = $parentOptions;
    }
}