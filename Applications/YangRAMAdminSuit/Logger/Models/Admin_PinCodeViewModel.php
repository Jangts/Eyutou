<?php
namespace Admin\Logger\Models;

class Admin_PinCodeViewModel extends \PM\_STUDIO\BaseFormViewModel {
	public static $inputs = [
		[
			'field_name'	=>	'UID',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'OLDPIN',
			'display_name'	=>	'旧认证码',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'PIN',
			'display_name'	=>	'新认证码',
			'input_type'	=>	'text'
		]
	];

	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[2])&&is_numeric($this->request->ARI->patharr[2])&&$this->request->ARI->patharr[2]>0){
			$basedir = $this->request->ARI->dirname.'/';
			$baseurl = $basedir.$this->app->ID;
			$guid = $this->request->ARI->patharr[2];
			$item = AdministratorModel::byGUID($guid);

			if($item){
				$this->assign('formname', '修改管理员认证码<a href="'.$basedir.'logger/account/'.$guid.'">编辑个人信息</a><a href="'.$basedir.'users/password/'.$guid.'">修改登录密码</a>');
				$this->assign('form', self::buildForm([
					'UID'		=>	$item->UID,
					'OLDPIN'	=>	'',
					'PIN'		=>	''
				], 'PUT'));
				$this->assign('buttons', [
					[
						'name' 	=>	'重置表单',
						'order'	=>	'reset',
						'form'	=>	'myform',
						'action'=>	'',
						'href'	=>	''
					],
					[
						'name' 	=>	'确认修改',
						'order'	=>	'submit',
						'form'	=>	'myform',
						'action'=>	__aurl__.'logger/administrators/'.$guid,
						'href'	=>	''
					]
				]);
				
				$this->template = 'form.html';
				return $this;
			}
		}
		exit('用户不存在!');
	}
}