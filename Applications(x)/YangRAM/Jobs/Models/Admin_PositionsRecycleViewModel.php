<?php
namespace Jobs\Models;

use PM\_CLOUD\FolderModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class Admin_PositionsRecycleViewModel extends \PM\_2\BaseTrashCanViewModel {
	public static
	$__sortby = TableRowMetaModel::MTIME_DESC;

	public function initVars(){
		return [
			'listname'	=>	'回收站新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	protected function readList($stagedir, $range){
		$count = TableRowModel::getCOUNT('positions', NULL, TableRowMetaModel::RECYCLED);
		$items = TableRowModel::getRows('positions', NULL, TableRowMetaModel::RECYCLED, static::$__sortby, $range[0], $range[1]);
		$rows = $this->buildTableRows($stagedir, $items);

        $this->assign('itemlist', static::buildTable($rows));
        $this->assign('pagelist', self::buildPageList($count));
        $this->template = 'table.html';
        return $this;
	}

    protected function buildTableRows($stagedir, $items = []){
		$rows = [];
		foreach($items as $index=>$position){
			$stage_url = $stagedir.$position->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'name'		=>	[$position->POSINAME],
				'mtime'		=>	[$position->SK_MTIME],
				'__ops'		=>	['<a href="'.$stage_url.'/delete/">彻底删除</a> | <a href="'.$stage_url.'/recover/">恢复</a>']
			];
		}
		return $rows;
	}

	protected function execDeletion($listurl){
		if(is_numeric($this->request->ARI->patharr[2])&&$this->request->ARI->patharr[2]>0){
			if($position = TableRowModel::byGUID($this->request->ARI->patharr[2])){
				if(isset($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]==='delete'){
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