<?php
namespace CTH\Jobs\Models;

use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class PositionsRecycleAVModel extends \PM\_STUDIO\BaseTrashCanAVModel {
	public static
	$__sortby = TableRowMetaModel::MTIME_DESC;

	public function initialize(){
		return [
			'listname'	=>	'回收站新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	protected function readList($stagedir, $range){
		$count = TableRowModel::getCOUNT('positions', NULL, TableRowMetaModel::RECYCLED);
		$items = TableRowModel::getRows('positions', NULL, TableRowMetaModel::RECYCLED, static::$__sortby, $range[0], $range[1]);
		$qs = static::buildQueryString($range[2]);
		$rows = $this->buildTableRows($stagedir, $items, $qs);

		$this->assign('__avmtabs','');
		$this->assign('__avmtags', '');
        $this->assign('itemlist', static::buildTable($rows, $range));
        $this->assign('pagelist', self::buildPageList($count));
        $this->template = 'table.html';
        return $this;
	}

    protected function buildTableRows($stagedir, $items = [], $qs = ''){
		$rows = [];
		foreach($items as $index=>$position){
			$itemurl = $stagedir.$position->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'name'		=>	[$position->POSINAME],
				'mtime'		=>	[$position->SK_MTIME],
				'__ops'		=>	['<a href="'.$itemurl.'/delete/' . $qs .'">彻底删除</a> | <a href="'.$itemurl.'/recover/' . $qs .'">恢复</a>']
			];
		}
		return $rows;
	}

	protected function execDeletion($listurl){
		if(is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			if($position = TableRowModel::byGUID($this->request->ARI->patharr[3])){
				if(isset($this->request->ARI->patharr[4])&&$this->request->ARI->patharr[4]==='delete'){
					$position->destroy();
				}else{
					$position->recycle(0);
				}
			}
		}
		$this->assign('href', $listurl);
		$this->template = 'recycle.html';
		return $this;
	}
}