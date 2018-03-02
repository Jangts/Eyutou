<?php
namespace Press\Models;

use PM\_CLOUD\FolderModel;

class Admin_CategoriesRecycleViewModel extends \PM\_2\BaseTrashCanViewModel {
	public static
	$model = 'PM\_CLOUD\FolderModel',
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
	$__sortby = FolderModel::MTIME_DESC;

	protected static function __viewWhere(){
        return "`type` = 'news' AND `SK_IS_RECYCLED` = 1";
    }

	public function initVars(){
		return [
			'listname'	=>	'回收站新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
	}
	
	protected function buildTableRows($stagedir, $items = []){
        $rows = [];
		foreach($items as $index=>$item){
			$stage_url = $stagedir.$item->id;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'name'		=>	[$item->name],
				'mtime'	    =>	[$item->SK_MTIME],
				'__ops'		=>	['<a href="'.$stage_url.'/delete/">彻底删除</a> | <a href="'.$stage_url.'/recover/">恢复</a>']
			];
		}
        return $rows;
    }
}