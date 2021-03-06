<?php
namespace Pages\Main\Models;

class LinksAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	public static
	$model = 'Pages\Main\Models\LinkModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'链接名',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'type',
			'display_name'	=>	'类型',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'alt',
			'display_name'	=>	'描述',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'sort',
			'display_name'	=>	'排序',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'state',
			'display_name'	=>	'状态',
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
	$__sorts = [
		'id'			=>	[['id', false, LinkModel::SORT_REGULAR]],
		'id_reverse'	=>	[['id', true, LinkModel::SORT_REGULAR]],
		'sort'			=>	[['sort', false, LinkModel::SORT_REGULAR]],
		'sort_reverse'	=>	[['sort', true, LinkModel::SORT_REGULAR]]
	],
	$__sortby = [['id', false, LinkModel::SORT_REGULAR]],
	$creater = [
		'name'	=>	'创建新链接'
	],
	$types = [
		'page'  =>  '页面地址',
		'achv'  =>  '归档主页',
		'link'  =>  '插件链接',
		'http'  =>  '外部链接'
	],
	$sk = 'state',
	$inputs = [
		[
			'field_name'	=>	'id',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'menu',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'链接名称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'type',
			'display_name'	=>	'链接类型',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'parent_id',
			'display_name'	=>	'父级链接',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'value',
			'display_name'	=>	'链接内容',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'alt',
			'display_name'	=>	'链接描述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'target',
			'display_name'	=>	'链接窗口',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'sort',
			'display_name'	=>	'链接排序',
			'input_type'	=>	'number'
		]
	],
	$selectOptions = [
		'target' => [
			["_top", "顶部窗口"],
			["_self", "本窗口"],
			["_blank", "新窗口"],
			["_parent", "父级窗口"],
			["_search", "搜索窗口"]
		]
	];

	protected static function __viewWhere(){
        return [
			'menu' => $_GET['group']
		];
	}

	protected static function __createURL($basedir, $qs = ''){
        return $basedir.'/0/?group='.$_GET['group'];
    }
	
	protected static function __createTemplate($modelname){
		$link = LinkModel::create();
		if(isset($_GET['group'])&&($_GET['group']>0)&&($_GET['group']<7)){
			$link->menu = $_GET['group'];
		}else{
			$link->menu = 0;
		}
        return $link;
    }

	public function initialize(){
		OptionsModel::__correctTablePrefix($this->app);
		LinkModel::__correctTablePrefix($this->app);
		PageModel::__correctTablePrefix($this->app);
		ArchiveModel::__correctTablePrefix($this->app);
		if(isset($_GET['group'])&&($_GET['group']>0)&&($_GET['group']<7)){
			$_GET['group'] = $_GET['group'];
		}else{
			$_GET['group'] = 0;
		}
		return [
			'listname'	=>	LinkGroupsAVModel::$groups[$_GET['group']]['name'],
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>',
			'formname'	=>	'编辑链接信息<span>'.LinkGroupsAVModel::$groups[$_GET['group']]['name'].'</span>'
		];
	}
	

	protected function buildTableRows($basedir, $items = [], $qs = ''){
		$range = self::__viewLimit();
        $orderby = self::__viewOrderBy();

		$options = OptionsModel::autoloadItems();

		$rows = [];
		$frontdir = $options['default_page_url'];
		foreach($items as $index=>$link){
			$itemurl = $basedir.'/'.$link->id;
			$rows[] = [
				'__index'	=>	[$index],
				'name'		=>	[$link->name, $itemurl.$qs, false],
				'type'		=>	[self::$types[$link->type]],
				'sort'		=>	[$link->sort],
				'alt'		=>	[$link->alt],
				'state'		=>	[$link->state?'正常':'隐藏'],
				'__ops'		=>	['<a href="'. $link->getLinkURL($frontdir).'" target="_blank">访问</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">移除</a>']
			];
		}
		return $rows;
	}

	protected function setSelections($item){
		$typeOptions = [];
		foreach(self::$types as $type=>$alias){
			$typeOptions[] = [$type, $alias];
		}
		static::$selectOptions['type'] = $typeOptions;

		$parentOptions = [['0', '无父级页面']];
		$usableParents = $item->getUsableParents(['menu'=>$item->menu]);
		foreach($usableParents as $parent){
			if($parent['id']){
				$parentOptions[] = [$parent->id, $parent->name];
			}
		}
		static::$selectOptions['parent_id'] = $parentOptions;
	}
}