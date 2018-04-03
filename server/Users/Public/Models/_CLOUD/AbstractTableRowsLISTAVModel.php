<?php
namespace PM\_CLOUD;

use Status;
use Request;

abstract class AbstractTableRowsLISTAVModel extends \PM\_STUDIO\BaseTableAVModel {
	public static
	$tablename = NULL,
	$listname = 'My List',
	$listurl = '',
	$itemurl = '',
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

	public static function loadGroupTabs(){
		if($groups = TRGroupModel::getGroupsByTableName(static::$tablename)){
			$tabs = [];
			foreach ($groups as $group) {
				$tabs['group'.$group->id] = [
					'name'	=>	$group->name,
					'title'	=>	empty($group->description) ? $group->name : $group->description,
					'where'	=>	['GROUPID'=>$group->id]
				];
			}
			static::$__avmtabs = $tabs;
		}
	}

	public static function loadStaticProperties(){
		if(
			($table = TableMetaModel::byGUID(static::$tablename))
			&&is_file($filename = __DIR__.'/default_avmvar_providers/'.$table->type.'_list.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}
	}

	public function initialize(){
		if(empty(static::$tablename)){
			// new Status(1414, '', 'must have static property "tablename".');
			static::$tablename = $this->request->ARI->patharr[1];
		}
		static::loadGroupTabs();
		static::loadStaticProperties();
		if(empty(static::$listurl)){
			static::$listurl = '/'.$this->request->ARI->patharr[1].'/'.$this->request->ARI->patharr[2].'/';
		}
		return [
			'listname'	=>	static::$listname,
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
	}

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		if(empty($_GET['tabid'])||empty(static::$__avmtabs[$_GET['tabid']])){
            $group = NULL;
        }else{
			$group = static::$__avmtabs[$_GET['tabid']]['where']['GROUPID'];
        }
		$count = TableRowMetaModel::getCOUNT(static::$tablename, $group, TableRowMetaModel::UNRECYCLED);
		$list  = TableRowModel::getRows(static::$tablename, $group, TableRowMetaModel::UNRECYCLED, $orderby, $range[0], $range[1]);

		
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.static::$itemurl;
		
		$qs = static::buildQueryString($range[2]);
		$rows = $this->buildTableRows($basedir, $list, $qs);
		self::$creater['url'] = $basedir.$qs;

		$this->assign('__avmtabs', 	self::buildTabs($stagedir.static::$listurl));
		$this->assign('__avmtags', '');
		$this->assign('itemlist', 	self::buildTable($rows, $range[2]));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}

	protected function buildTableRows($basedir, $list = [], $qs = ''){
        $rows = [];
        foreach($list as $index=>$row){
			$itemurl = $basedir.$row->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'title'		=>	[$row->TITLE, $itemurl.$qs, false],
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