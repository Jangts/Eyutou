<?php
namespace PM\_STUDIO;

use Status;
use Request;

abstract class BaseCRUDAVModel extends \PM\_STUDIO\BaseTableAVModel {
    public static
    $listname = 'My List',
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
        static::loadStaticProperties();
		return [
			'listname'	=>	static::$listname,
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		if(empty($_GET['tabalias'])||empty(static::$classtabs[$_GET['tabalias']])){
            $group = NULL;
        }else{
			$group = static::$classtabs[$_GET['tabalias']]['where']['GROUPID'];
        }
		$count = TableRowMetaModel::getCOUNT('news', $group, TableRowMetaModel::UNRECYCLED);
		$list  = TableRowMetaModel::getRows('news', $group, TableRowMetaModel::UNRECYCLED, $orderby, $range[0], $range[1]);

		$rows = [];
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/news/news/';
		if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = '';
        }
		foreach($list as $index=>$news){
			$itemurl = $basedir.$news->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'title'		=>	[$news->TITLE, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
				'crttime'	=>	[$news->SK_CTIME],
				'modtime'	=>	[$news->SK_MTIME],
				'pubtime'	=>	[$news->PUBTIME],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/cloudtables/rows/'.$news->ID.'" href="javascript:;">移除</a>']
			];
		}

		if(empty($_GET['tabalias'])){
            self::$creater['url'] = $basedir;
        }else{
            self::$creater['url'] = $basedir.'?tabalias='.$_GET['tabalias'];
        }
		

		$this->assign('classtabs', 	self::buildTabs($stagedir.'/news/news-list/'));
		$this->assign('itemlist', 	self::buildTable($rows, $range[2]));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}