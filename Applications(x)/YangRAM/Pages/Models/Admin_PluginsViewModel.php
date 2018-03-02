<?php
namespace Pages\Models;

class Admin_PluginsViewModel extends \PM\_2\BaseTableViewModel {
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

	public function initVars(){
		return [
			'listname'	=>	'插件列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	''
		];
	}

	public function analysis($admininfo){
		$rows = [];
		$puglins = PluginModel::getALL();
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->ID;
		foreach($puglins as $index=>$puglin){
			$stage_url = $stagedir.'/Plugin/'.$puglin->appalias;
			$rows[] = [
				'__index'			=>	[$index + 1],
				'pluginname'	=>	[$puglin->pluginname, $stage_url, false],
				'app'			=>	[$puglin->getAppName()],
				'desc'			=>	[$puglin->desc],
				'__ops'			=>	['<a href="'.$stage_url.'">编辑</a>']
			];
		}

		$this->assign('itemlist', self::buildTable($rows));
		
		$this->template = 'table.html';
		return $this;
	}
}