<?php
namespace Pages\Designer\Models;

use Pages\Designer\Models\TemplateModel;

class TemplatesAVModel extends \PM\_STUDIO\BaseTableAVModel {
	public static
	$__avmtabs = [
		'niml'	=>	[
			'name'	=>	'页面与片段',
			'title'	=>	'页面与片段',
			'where'	=>	['type'=>'niml']
		],
		'js'	=>	[
			'name'	=>	'脚本',
			'title'	=>	'脚本',
			'where'	=>	['type'=>'js']
		],
		'css'	=>	[
			'name'	=>	'样式',
			'title'	=>	'样式',
			'where'	=>	['type'=>'css']
		]
	],
	$__avmtabswithoutall = true,
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	'__index'	
		],
		[
			'field_name'	=>	'filename',
			'display_name'	=>	'模板文件',
			'max_length'	=>	'300',
			'column_type'	=>	'',
			'classname'		=>	'al-left'	
		],
		[
			'field_name'	=>	'__ops',
			'display_name'	=>	'操作',
			'column_type'	=>	'',
			'classname'		=>	'__ops'	
		]
	];

	public function initialize(){
		return [
			'listname'	=>	'模板列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	''
		];
	}

	public function analysis($admininfo){
		if(isset($_GET['tabid'])&&isset(static::$__avmtabs[$_GET['tabid']])){
			$requires = static::$__avmtabs[$_GET['tabid']]['where'];
		}else{
			$_GET['tabid'] = 'niml';
			$requires = ['type'=>'niml'];
		}
		
		$rows = [];
		$templates = TemplateModel::query($requires);
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/tpl/template/';

		foreach($templates as $index=>$template){
			$itemurl = $basedir.$template->guid.'?tabid='.$_GET['tabid'];
			$rows[] = [
				'__index'		=>	[$index + 1],
				'filename'		=>	['/'.$template->basename, $itemurl, false],
				'__ops'			=>	['<a href="'.$itemurl.'">编辑</a>']
			];
		}

		

		$this->assign('__avmtabs', 	self::buildTabs($stagedir.'/tpl/templates/'));
		$this->assign('__avmtags', '');
		$this->assign('itemlist', self::buildTable($rows));
		
		$this->template = 'table.html';
		return $this;
	}
}