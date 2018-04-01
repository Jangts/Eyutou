<?php
namespace CTH\Press\Models;

use PM\_CLOUD\TRGroupModel;

class Admin_CategoriesRecycleViewModel extends \PM\_STUDIO\BaseTrashCanViewModel {
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
			'field_name'	=>	'mtime',
			'display_name'	=>	'回收时间',
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
	$__sortby = TRGroupModel::MTIME_DESC;

	protected static function __viewWhere(){
		return [
			'type'				=>	'news',
			'SK_IS_RECYCLED'	=>	1
		];
    }

	public function initialize(){
		return [
			'listname'	=>	'回收站新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
	}
	
	protected function buildTableRows($stagedir, $items = [], array $range = [0, 0, 1], $sort = ''){
        $rows = [];
		foreach($items as $index=>$item){
			$itemurl = $stagedir.$item->id;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'name'		=>	[$item->name],
				'mtime'	    =>	[$item->SK_MTIME],
				'__ops'		=>	['<a href="'.$itemurl.'/delete/?page='. $range[2] .'">彻底删除</a> | <a href="'.$itemurl.'/recover/?page='. $range[2] .'">恢复</a>']
			];
		}
        return $rows;
    }
}