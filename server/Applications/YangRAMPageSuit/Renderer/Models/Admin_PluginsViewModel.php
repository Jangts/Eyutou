<?php
namespace Pages\Main\Models;

class Admin_PluginsViewModel extends \PM\_STUDIO\BaseTableViewModel {
	public static
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'pluginname',
			'display_name'	=>	'插件名',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'app',
			'display_name'	=>	'所属应用',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'desc',
			'display_name'	=>	'插件描述',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__ops',
			'display_name'	=>	'操作',
			'column_type'	=>	'',
			'classname'		=>	''	
		]
	];

	public function initialize(){
		return [
			'listname'	=>	'插件列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	''
		];
	}

	public function analysis($admininfo){
		$rows = [];
		$puglins = PluginModel::getALL();
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/plugin/';

		foreach($puglins as $index=>$puglin){
			$itemurl = $basedir.$puglin->appalias;
			$rows[] = [
				'__index'		=>	[$index + 1],
				'pluginname'	=>	[$puglin->pluginname, $itemurl, false],
				'app'			=>	[$puglin->getAppName()],
				'desc'			=>	[$puglin->desc],
				'__ops'			=>	['<a href="'.$itemurl.'">编辑</a>']
			];
		}

		$this->assign('classtabs', 	self::buildTabs($basedir));
		$this->assign('itemlist', self::buildTable($rows));
		
		$this->template = 'table.html';
		return $this;
	}
}