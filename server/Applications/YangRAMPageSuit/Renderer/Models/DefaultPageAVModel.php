<?php
namespace Pages\Main\Models;

class DefaultPageAVModel extends \PM\_STUDIO\BaseFormAVModel {
	public static $inputs = [
		[
			'field_name'	=>	'default_page_title',
			'display_name'	=>	'首页标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'default_page_url',
			'display_name'	=>	'首页目录',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'default_description',
			'display_name'	=>	'站点描述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'logo',
			'display_name'	=>	'站点标识',
			'input_type'	=>	'banner'
		],
		[
			'field_name'	=>	'default_page_content',
			'display_name'	=>	'首页内容',
			'input_type'	=>	'ueditor'
		],
		[
			'field_name'	=>	'common_bottom',
			'display_name'	=>	'通用底部',
			'input_type'	=>	'editor'
		],
		[
			'field_name'	=>	'use_theme',
			'display_name'	=>	'使用主题',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'default_page_template',
			'display_name'	=>	'首页模板',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'search_result_page_template',
			'display_name'	=>	'搜索结果页模板',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'more',
			'display_name'	=>	'附加内容',
			'input_type'	=>	'longtext'
		]
	];

	public function analysis($admininfo){
		OptionsModel::__correctTablePrefix($this->app);
		$options = OptionsModel::autoloadItems();

		$this->assign('formname', '编辑页面基本信息');
		$this->assign('form', self::buildForm($options));
		$this->assign('buttons', [
			[
				'name' 	=>	'重置',
				'order'	=>	'reset',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	''
			],
			[
				'name' 	=>	'确定',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	 '//'._STD_API_.$this->app->ID.'?c=options&m=update',
				'href'	=>	 ''
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}