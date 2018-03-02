<?php
namespace Press\Models;

use PM\_CLOUD\FolderModel;
use PM\_CLOUD\TableRowMetaModel;

class Admin_newsListViewModel extends \PM\_2\BaseTableViewModel {
	public static
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'title',
			'sorting_name'	=>	'title_gb',
			'display_name'	=>	'新闻标题',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		// 应该建立基于GUID的访问，但是模板的问题呢?
		// 可设置显示形式，如自主显示，或作为别人的插件
		// 仅自主现实时显示URL
		// [
		// 	'field_name'	=>	'url',
		// 	'display_name'	=>	'默认访问地址',
		// 	'column_type'	=>	'',
		// 	'classname'		=>	'bc al-left'	
		// ],
		[
			'field_name'	=>	'crttime',
			'sorting_name'	=>	'ctime',
			'display_name'	=>	'创建时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'modtime',
			'sorting_name'	=>	'mtime',
			'display_name'	=>	'修改时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'pubtime',
			'sorting_name'	=>	'ptime',
			'display_name'	=>	'发布时间',
			'column_type'	=>	'',
			'classname'		=>	''	
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
		'name'	=>	'创建新资讯'
	],
	$__sorts = [
		'id'				=>	TableRowMetaModel::ID_ASC,
		'id_reverse'		=>	TableRowMetaModel::ID_DESC,
		'ctime'				=>	TableRowMetaModel::CTIME_ASC,
		'ctime_reverse'		=>	TableRowMetaModel::CTIME_DESC,
		'mtime'				=>	TableRowMetaModel::MTIME_ASC,
		'mtime_reverse'		=>	TableRowMetaModel::MTIME_DESC,
		'ptime'				=>	TableRowMetaModel::PUBTIME_ASC,
		'ptime_reverse'		=>	TableRowMetaModel::PUBTIME_DESC,
		'level'				=>	TableRowMetaModel::LEVEL_ASC,
		'level_reverse'		=>	TableRowMetaModel::LEVEL_DESC,
		'title'				=>	TableRowMetaModel::TITLE_ASC,
		'title_reverse'		=>	TableRowMetaModel::TITLE_DESC,
		'title_gb'			=>	TableRowMetaModel::TITLE_ASC_GBK,
		'title_gb_reverse'	=>	TableRowMetaModel::TITLE_DESC_GBK
	],
	$__sortby = TableRowMetaModel::PUBTIME_DESC;

	public function initVars(){
		return [
			'listname'	=>	'新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		$count = TableRowMetaModel::getCOUNT('news', NULL, TableRowMetaModel::UNRECYCLED);
		$list  = TableRowMetaModel::getRows('news', NULL, TableRowMetaModel::UNRECYCLED, $orderby, $range[0], $range[1]);

		$rows = [];
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->ID;
		foreach($list as $index=>$news){
			$stage_url = $stagedir.'/news/'.$news->ID;
			$rows[] = [
				'__index'		=>	[$index + 1],
				'title'		=>	[$news->TITLE, $stage_url, false],
				'crttime'	=>	[$news->SK_CTIME],
				'modtime'	=>	[$news->SK_MTIME],
				'pubtime'	=>	[$news->PUBTIME],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$stage_url.'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/cloudtables/rows/'.$news->ID.'" href="javascript:;">移除</a>']
			];
		}
		self::$creater['url'] = $stagedir.'/news/';


		$this->assign('itemlist', self::buildTable($rows, $range[2]));
		$this->assign('pagelist', self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}