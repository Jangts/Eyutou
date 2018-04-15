<?php
namespace Pages\Main\Models;

class PluginAVModel extends \PM\_STUDIO\BaseFormAVModel {
	public static $inputs = [
		[
			'field_name'	=>	'appalias',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'options',
			'display_name'	=>	'配置文件',
			'input_type'	=>	'hightext'
		]
	];

	public function initialize(){
		return [
			'formname'	=>	'编辑插件信息'
		];
	}
	
	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[3])){
			$guid = $this->request->ARI->patharr[3];
			$puglin = PluginModel::byGUID($guid);
			$method = 'PUT';
		}else{
			$puglin = PluginModel::byGUID();
			$method = 'POST';
		}

		$this->assign('form', self::buildForm([
			'appalias'	=>	$puglin->appalias,
			'options'	=>	$puglin->getOptionsText()
		], $method));
        
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
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->id.'/plugins/plugins/'
			],
			[
				'name' 	=>	'提交保存',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.$this->app->ID.'/plugins/'.$guid,
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->id.'/plugins/plugins/'
			]
		]);

		$this->template = 'form.html';
		return $this;
	}
}