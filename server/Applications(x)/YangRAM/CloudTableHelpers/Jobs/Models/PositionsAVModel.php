<?php
namespace CTH\Jobs\Models;

use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\AbstractTableRowsLISTAVModel;

class PositionsAVModel extends AbstractTableRowsLISTAVModel {
	public static
	$tablename = 'positions',
	$itemurl = '/positions/position/';

	public static function loadStaticProperties(){
		if(
			is_file($filename = __DIR__.'/avmvar_providers/'.static::$tablename.'_list.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}else{
			parent::loadStaticProperties();
		}
		static::$__sorts['posiname'] = [['POSINAME', false, TableRowModel::SORT_CONVERT_GBK]];
		static::$__sorts['posiname_reverse'] = [['POSINAME', true, TableRowModel::SORT_CONVERT_GBK]];
	}

	protected function buildTableRows($basedir, $list = [], $qs = ''){
        $rows = [];
        foreach($list as $index=>$row){
			$itemurl = $basedir.$row->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'title'		=>	[$row->POSINAME, $itemurl.$qs, false],
				'crttime'	=>	[$row->SK_CTIME],
				'modtime'	=>	[$row->SK_MTIME],
				'pubtime'	=>	[$row->PUBTIME],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$itemurl.$qs .'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/cloudtables/rows/'.$row->ID.'" href="javascript:;">移除</a>']
			];
		}
        return $rows;
    }
}