<?php
namespace Pages\Main\Models;

class Admin_LinkGroupsViewModel extends \PM\_STUDIO\BaseListViewModel {
	public static
	$groups = [
		[
			'name'	=>	'菜单一（默认）',
			'desc'	=>	'默认菜单，通常用作主导航'
		],
		[
			'name'	=>	'菜单二',
			'desc'	=>	'一般用作底部分类导航'
		],
		[
			'name'	=>	'菜单三',
			'desc'	=>	'一般用作侧边导航'
		],
		[
			'name'	=>	'菜单四',
			'desc'	=>	'备用主导航'
		],
		[
			'name'	=>	'菜单五',
			'desc'	=>	'一般用作底部外链'
		],
		[
			'name'	=>	'菜单六',
			'desc'	=>	'备用侧边导航'
		],
		[
			'name'	=>	'菜单七',
			'desc'	=>	'一般用作滑动导航'
		]
	];

	public function initVars(){
		return [
			'listname'	=>	'菜单列表',
			'itemlist'	=>	'<ul class="list-view"><li><p class="list-title"><a href="javascript:;">TITLE</a></p><p class="list-desc">Description</p></li></ul>'
		];
	}

	public function analysis($admininfo){
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->ID;
		$basedir = $stagedir.'/links/';
		$rows = [];
		foreach(self::$groups as $index=>$archive){
			$rows[] = [
				'title'		=>	$archive['name'],
				'url'		=>	$basedir.'?group='.$index,
				'desc'		=>	$archive['desc'],
			];
		}

		$this->assign('classtabs', 	self::buildTabs($basedir));
		$this->assign('itemlist', 	self::buildList($rows));
        
		
		$this->template = 'list.html';
		return $this;
	}
}