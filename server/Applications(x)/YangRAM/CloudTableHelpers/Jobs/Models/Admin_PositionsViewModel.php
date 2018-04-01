<?php
namespace CTH\Jobs\Models;

use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableRowModel;

class Admin_PositionsViewModel extends \PM\_STUDIO\BaseTableViewModel {
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
			'sorting_name'	=>	'holderplace',
			'display_name'	=>	'职位名称',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
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
		'name'	=>	'创建新的职位招聘'
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

	public function initialize(){
        return [
			'listname'	=>	'职位列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		$count = TableRowModel::getCOUNT('positions', NULL, TableRowMetaModel::UNRECYCLED);
		$positions  = TableRowModel::getRows('positions', NULL, TableRowMetaModel::UNRECYCLED, $orderby, $range[0], $range[1]);

		$rows = [];
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/positions/position/';
		if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = '';
        }
		foreach($positions as $index=>$position){
			$itemurl = $basedir.$position->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'title'		=>	[$position->POSINAME, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
				'crttime'	=>	[$position->SK_CTIME],
				'modtime'	=>	[$position->SK_MTIME],
				'pubtime'	=>	[$position->PUBTIME],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/cloudtables/rows/'.$position->ID.'" href="javascript:;">移除</a>']
			];
		}
		self::$creater['url'] = $basedir;


		$this->assign('classtabs', 	self::buildTabs($stagedir.'/positions/'));
		$this->assign('itemlist', 	self::buildTable($rows));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}