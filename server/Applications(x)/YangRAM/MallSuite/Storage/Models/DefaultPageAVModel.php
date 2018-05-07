<?php
namespace Mall\Goods\Models;

class OptionsAVModel extends \PM\_STUDIO\BaseFormAVModel {
	public static $inputs = [
		[
			'field_name'	=>	'show_buy_link_on_pagesplugin',
			'display_name'	=>	'在页面中显示购买链接',
			'input_type'	=>	'radio'
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