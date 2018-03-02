<?php
namespace Press\Models;

use PM\_CLOUD\FolderModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class Admin_NewsViewModel extends \PM\_2\BaseFormViewModel {
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
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'TITLE',
			'display_name'	=>	'新闻标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'SUBTITLE',
			'display_name'	=>	'新闻副标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'FOLDER',
			'display_name'	=>	'新闻分类',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'PRESS',
			'display_name'	=>	'发行机构',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'AUTHOR',
			'display_name'	=>	'作者',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'ORIGINATE_FROM',
			'display_name'	=>	'转载来源',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'PRESS_DATE',
			'display_name'	=>	'原文发布日期',
			'input_type'	=>	'fulldate'
		],
		[
			'field_name'	=>	'PUBTIME',
			'display_name'	=>	'本站发布时间',
			'input_type'	=>	'datetime'
		],
		[
			'field_name'	=>	'THUMB',
			'display_name'	=>	'列表缩略图',
			'input_type'	=>	'image'
		],
		[
			'field_name'	=>	'ABSTRACT',
			'display_name'	=>	'内容概述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'CONTENT',
			'display_name'	=>	'新闻正文',
			'input_type'	=>	'editor'
		],
		[
			'field_name'	=>	'MARKED',
			'display_name'	=>	'标注精华',
			'input_type'	=>	'radio'
		],
		[
			'field_name'	=>	'RANK',
			'display_name'	=>	'文章评级',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'LEVEL',
			'display_name'	=>	'排序级数',
			'input_type'	=>	'counter'
		]
	],
	$selectOptions = [
		'MARKED'	=>	[
			['0', '否'],
			['1', '是']
		],
		'RANK'		=>	[
			['1', '★'],
			['2', '★★'],
			['4', '★★★'],
			['3', '★★★★'],
			['5', '★★★★★'],
			['6', '★★★★★★'],
			['7', '★★★★★★★']
		]
	];

	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[2])&&is_numeric($this->request->ARI->patharr[2])&&$this->request->ARI->patharr[2]>0){
			$guid = $this->request->ARI->patharr[2];
			$news = TableRowModel::byGUID($guid);
			if(!$news){
				$this->assign('href', $this->request->ARI->dirname.'/'.$this->app->ID.'/news-list/');
				
				$this->template = 'news404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'移除新闻',
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid,
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/news-list/'
			];
		}else{
			$guid = 0;
			$news = TableRowModel::create(['tablename' => 'news']);
			$method = 'POST';
			$button2 = NULL;
		}

		$categories = FolderModel::getFoldersByTableName('news');
		$categoryOptions = [];
		foreach($categories as $category){
			$categoryOptions[] = [$category['id'], $category['name']];
		}
		static::$selectOptions['FOLDER'] = $categoryOptions;

		$this->assign('formname', '编辑新闻信息');
		$this->assign('form', self::buildForm($news->getArrayCopy(), $method));
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
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/news-list/'
			],
			$button2,
			[
				'name' 	=>	'保存到待审',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=0',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/news-list/'
			],
			[
				'name' 	=>	'发布',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=1',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/news-list/'
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}