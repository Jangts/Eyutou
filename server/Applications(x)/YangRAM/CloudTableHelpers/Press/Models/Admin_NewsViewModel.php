<?php
namespace CTH\Press\Models;

use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class Admin_NewsViewModel extends \PM\_STUDIO\BaseFormViewModel {
	public static $inputs = [
		[
			'field_name'	=>	'ID',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'TYPE',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'TABLENAME',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'DESCRIPTION',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'SK_CTIME',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'SK_MTIME',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'SK_STATE',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'PRIMER',
			'display_name'	=>	'新闻引题',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'TITLE',
			'display_name'	=>	'新闻标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'SUBTITLE',
			'display_name'	=>	'新闻副标题',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'GROUPID',
			'display_name'	=>	'新闻分类',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'PRESS',
			'display_name'	=>	'来源',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'ORIGINATE_URL',
			'display_name'	=>	'来源URL',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'PRESS_DATE',
			'display_name'	=>	'原文发布日期',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'REPORTER',
			'display_name'	=>	'通讯员',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'PHOTOGRAPHER',
			'display_name'	=>	'摄影',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'THUMB',
			'display_name'	=>	'列表缩略图',
			'input_type'	=>	'image'
		],
		[
			'field_name'	=>	'PUBTIME',
			'display_name'	=>	'本站发布时间',
			'input_type'	=>	'datetime'
		],
		[
			'field_name'	=>	'ABSTRACT',
			'display_name'	=>	'内容概述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'CONTENT',
			'display_name'	=>	'新闻正文',
			'input_type'	=>	'ueditor'
		],
		[
			'field_name'	=>	'MARKED',
			'display_name'	=>	'标注精华',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'RANK',
			'display_name'	=>	'评星（等数）',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'LEVEL',
			'display_name'	=>	'排序（级数）',
			'input_type'	=>	'counter'
		]
	],
	$selectOptions = [
		'MARKED'	=>	[
			['0', '否'],
			['1', '是']
		],
		'RANK'		=>	[
			['1', '★（低级）'],
			['2', '★★（次低级）'],
			['4', '★★★（中低级）'],
			['3', '★★★★（中级）'],
			['5', '★★★★★（中高级，默认）'],
			['6', '★★★★★★（高级）'],
			['7', '★★★★★★★（顶级，非置顶勿选）']
		]
	];

	public function analysis($admininfo){
		$basedir = $this->request->ARI->dirname.'/'.$this->app->id.'/news/news-list/';
		if(isset($this->request->ARI->patharr[3])&&is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			$guid = $this->request->ARI->patharr[3];
			$news = TableRowModel::byGUID($guid);
			
			if(!$news){
				$this->assign('href', $basedir);
				
				$this->template = '404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'移除新闻',
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid,
				'href'	=>	$basedir
			];
		}else{
			$guid = 0;
			$news = TableRowModel::create(['tablename' => 'news']);
			if(isset($_GET['tabalias'])&&is_array(Admin_newsListViewModel::$classtabs[$_GET['tabalias']])){
				foreach(Admin_newsListViewModel::$classtabs[$_GET['tabalias']]['where'] as $prop=>$value){
					$news->__set($prop, $value);
				}
			}
			$method = 'POST';
			$button2 = NULL;
		}

		$groups = TRGroupModel::getGroupsByTableName('news');
		$groupOptions = [];
		foreach($groups as $group){
			$groupOptions[] = [$group['id'], $group['name']];
		}
		static::$selectOptions['GROUPID'] = $groupOptions;

		$this->assign('formname', '编辑新闻信息');
		$this->assign('form', self::buildForm($news->getArrayCopy(), $method));
		if(isset($_GET['sort'])){
            $selects = '?sort='. $_GET['sort'];
        }else{
            $selects = '?sort=';
        }
        if(isset($_GET['page'])){
            $selects .= '&page='. $_GET['page'];
        }else{
            $selects .= '&page=';
		}
		if(isset($_GET['tabalias'])){
            $selects .= '&tabalias='. $_GET['tabalias'];
        }
		$this->assign('buttons', [
			[
				'name' 	=>	'重置表单',
				'order'	=>	'reset',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	''
			],
			[
				'name' 	=>	'返回列表',
				'order'	=>	'anchor',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	$basedir.$selects
			],
			$button2,
			[
				'name' 	=>	'保存到待审',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=0',
				'href'	=>	$basedir.$selects
			],
			[
				'name' 	=>	'发布',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=1',
				'href'	=>	$basedir.$selects
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}