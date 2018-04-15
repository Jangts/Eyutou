<?php
namespace Pages\Designer\Models;

use Pages\Designer\Models\TemplateModel;

class TemplateAVModel extends \PM\_STUDIO\BaseFormAVModel {
	public function initialize(){
		return [];
	}
	
	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[3])){
			$guid = $this->request->ARI->patharr[3];
			$template = TemplateModel::byGUID($guid);
			$method = 'update';
		}else{
			$template = TemplateModel::byGUID();
			$method = 'create';
		}

		$this->assign('form', '<form name="myform"><textarea name="content">'.$template->content.'</textarea></form>');
        
		$this->assign('buttons', [
			[
				'name' 	=>	'返回列表',
				'order'	=>	'anchor',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->id.'/tpl/templates/'
			],
			[
				'name' 	=>	'提交保存',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	'//'._STD_API_.$this->app->ID.'?c=templates&m='.$method.'&args='.$guid,
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->id.'/tpl/templates/'
			]
		]);

		$this->template = 'form.html';
		return $this;
	}
}