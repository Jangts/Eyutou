<?php
namespace Pages\Main\Models;

class PagesAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	public static
	$model = 'Pages\Main\Models\PageModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'title',
			'display_name'	=>	'页面标题',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'url',
			'display_name'	=>	'默认访问地址',
			'column_type'	=>	'',
			'classname'		=>	'tangram al-left'	
		],
		[
			'field_name'	=>	'crttime',
			'sorting_name'	=>	'ctime',
			'display_name'	=>	'创建时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'modtime',
			'sorting_name'	=>	'mtime',
			'display_name'	=>	'修改时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'pubtime',
			'sorting_name'	=>	'ptime',
			'display_name'	=>	'发布时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__count',
			'display_name'	=>	'点击量',
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
	$creater = [
		'name'	=>	'创建新页面'
	],
	$__sorts = [
		'id'			=>	PageModel::ID_ASC,
		'id_reverse'	=>	PageModel::ID_DESC,
		'ctime'			=>	PageModel::CTIME_ASC,
		'ctime_reverse'	=>	PageModel::CTIME_DESC,
		'mtime'			=>	PageModel::MTIME_ASC,
		'mtime_reverse'	=>	PageModel::MTIME_DESC,
		'ptime'			=>	PageModel::PUBTIME_ASC,
		'ptime_reverse'	=>	PageModel::PUBTIME_DESC
	],
	$__sortby = PageModel::ID_DESC,
	$inputs = [
		[
			'field_name'	=>	'id',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'title',
			'display_name'	=>	'页面标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'archive',
			'display_name'	=>	'页面归档',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'parent',
			'display_name'	=>	'父级页面',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'alias',
			'display_name'	=>	'路由别名',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'description',
			'display_name'	=>	'页面描述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'banner',
			'display_name'	=>	'海报',
			'input_type'	=>	'banner100'
		],
		[
			'field_name'	=>	'banner_link',
			'display_name'	=>	'海报链接',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'thumb_inlist',
			'display_name'	=>	'列表缩略图',
			'input_type'	=>	'image'
		],
		[
			'field_name'	=>	'pubtime',
			'display_name'	=>	'校正发布时间',
			'input_type'	=>	'datetime'
		],
		[
			'field_name'	=>	'content',
			'display_name'	=>	'页面内容',
			'input_type'	=>	'ueditor'
		],
		[
			'field_name'	=>	'template',
			'display_name'	=>	'页面模板',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'more',
			'display_name'	=>	'附加内容',
			'input_type'	=>	'longtext'
		],
		[
			"field_name"	=> 	"sort_level",
        	"display_name"	=>	"排序（级数）",
        	"input_type"	=>	"counter"
		]
	];

	// public static function __viewWhere(){
	// 	if(empty($_GET['tabid'])){
    //         return [];
    //     }else{
    //         return static::$__avmtabs[$_GET['tabid']]['where'];
    //     }
	// }

	public static function loadGroupTabs(){
		$tabs = [];
		$archives = ArchiveModel::query();
		foreach ($archives as $archive) {
			$tabs['group'.$archive->id] = [
				'name'	=>	$archive->archive_name,
				'title'	=>	empty($archive->archive_desc) ? $group->archive_name : $archive->archive_desc,
				'where'	=>	['archive'=>$archive->id]
			];
		}
		static::$__avmtabs = $tabs;
	}

	public function initialize(){
		ArchiveModel::__correctTablePrefix($this->app);
		self::loadGroupTabs();
		return [
			'listname'	=>	'页面列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>',
			'formname'	=>	'编辑页面信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], $qs = ''){
		OptionsModel::__correctTablePrefix($this->app);
		$options = OptionsModel::autoloadItems();
		
		$rows = [];
		$frontdir = $options['default_page_url'];

		foreach($items as $index=>$item){
			$itemurl_front = $frontdir.$item->getRelativeURL();
			$itemurl = $basedir.'/'.$item->id;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'title'		=>	[$item->title, $itemurl.$qs, false],
				'url'		=>	[$itemurl_front, $itemurl_front, true],
				'crttime'	=>	[$item->crttime],
				'modtime'	=>	[$item->modtime],
				'pubtime'	=>	[$item->pubtime],
				'__count'	=>	[$item->__count],
				'__ops'		=>	['<a href="'.$itemurl.$qs .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">移除</a>']
			];
		}
		self::$creater['url'] = $basedir.'/0/'.$qs;
		return $rows;
	}

	protected function setSelections($item){
		ArchiveModel::__correctTablePrefix($this->app);
		PageModel::__correctTablePrefix($this->app);
		$archiveOptions = ArchiveModel::query();
		foreach($archiveOptions as $index=>$archive){
			$archiveOptions[$index] = [$archive['id'], $archive['archive_name']];
		}
		static::$selectOptions['archive'] = $archiveOptions;
		if($item->id){
			$pages = PageModel::getPagePaths($item->id, '`state` = 1 AND `archive` = '.$item->archive);
		}else{
			$pages = PageModel::getPagePaths(NULL, '`state` = 1 AND `archive` = 1');
		}
		

		$parentOptions = [['0', '无父级页面']];
		foreach($pages as $parent){
			if($parent['id']){
				$parentOptions[] = [$parent['id'], str_repeat('——', $parent['level']) . ' | ' . $parent['title']];
			}
		}
		static::$selectOptions['parent'] = $parentOptions;
	}
}