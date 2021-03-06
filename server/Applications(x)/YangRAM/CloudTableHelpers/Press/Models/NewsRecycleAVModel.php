<?php
namespace CTH\Press\Models;

use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class NewsRecycleAVModel extends \PM\_STUDIO\BaseTrashCanAVModel {
	public static
	// $model = 'PM\_CLOUD\TableRowModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'title',
			'display_name'	=>	'新闻标题',
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
	$__sortby = TableRowMetaModel::MTIME_DESC;

	public function initialize(){
		return [
			'listname'	=>	'回收站新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	protected function readList($stagedir, $range){
		$count = TableRowMetaModel::getCOUNT('news', NULL, TableRowMetaModel::RECYCLED);
		$items = TableRowMetaModel::getRows('news', NULL, TableRowMetaModel::RECYCLED, static::$__sortby, $range[0], $range[1]);
		$qs = static::buildQueryString($range[2]);
		$rows = $this->buildTableRows($stagedir, $items, $qs);

		$this->assign('__avmtabs','');
		$this->assign('__avmtags', '');
        $this->assign('itemlist', static::buildTable($rows));
        $this->assign('pagelist', self::buildPageList($count));
        $this->template = 'table.html';
        return $this;
	}

    protected function buildTableRows($stagedir, $items = [], $qs = ''){
		$rows = [];
		foreach($items as $index=>$news){
			$itemurl = $stagedir.$news->ID;
			$rows[] = [
				'__index'		=>	[$index + 1],
				'title'		=>	[$news->TITLE],
				'mtime'	=>	[$news->SK_MTIME],
				'__ops'		=>	['<a href="'.$itemurl.'/delete/' . $qs .'">彻底删除</a> | <a href="'.$itemurl.'/recover/' . $qs .'">恢复</a>']
			];
		}
		return $rows;
	}

	protected function execDeletion($listurl){
		if(is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			if($news = TableRowModel::byGUID($this->request->ARI->patharr[3])){
				if(isset($this->request->ARI->patharr[4])&&$this->request->ARI->patharr[4]==='delete'){
					$news->destroy();
				}else{
					$news->recycle(0);
				}
			}
		}
		$this->assign('href', $listurl);
		$this->template = 'recycle.html';
		return $this;
	}
}