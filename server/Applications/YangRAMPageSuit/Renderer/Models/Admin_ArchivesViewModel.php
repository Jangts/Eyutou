<?php
namespace Pages\Main\Models;

class Admin_ArchivesViewModel extends \PM\_STUDIO\BaseCRUDViewModel {
	public static
	$model = 'Pages\Main\Models\ArchiveModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'归档名称',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'desc',
			'display_name'	=>	'归档描述',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'homepage',
			'display_name'	=>	'归档主页',
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
			'field_name'	=>	'archive_name',
			'display_name'	=>	'归档名称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'archive_desc',
			'display_name'	=>	'归档描述',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'archive_image',
			'display_name'	=>	'归档图片',
			'input_type'	=>	'image'
		],
		[
			'field_name'	=>	'archive_hp',
			'display_name'	=>	'归档主页',
			'input_type'	=>	'text'
		]
	],
	$__sorts = [
		'name'			=>	[['archive_name', false, ArchiveModel::SORT_CONVERT_GBK]],
		'name_reverse'	=>	[['archive_name', true, ArchiveModel::SORT_CONVERT_GBK]]
	],
	$creater = [
		'name'	=>	'创建分类'
	];

	public function initialize(){
		return [
			'listname'	=>	'归档列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑归档信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], array $range = [0, 0, 1], $sort = ''){
		$rows = [];
        foreach($items as $index=>$item){
			$itemurl = $basedir.'/'.$item->id;
			$homepageurl = $item->getHomepageURL($this->app);
            $rows[] = [
                '__index'		=>	[$index + 1],
                'name'		=>	[$item->archive_name, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
				'desc'		=>	[$item->archive_desc],
				'homepage'	=>	[$homepageurl, $homepageurl, true],
                '__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">移除</a>']
            ];
        }
		return $rows;
	}

	protected function setSelections($item){}
}