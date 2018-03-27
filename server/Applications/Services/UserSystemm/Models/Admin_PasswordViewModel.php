<?php
namespace Services\Users\Models;

class Admin_PasswordViewModel extends \PM\_STUDIO\BaseFormViewModel {
	public static $inputs = [
		[
			'field_name'	=>	'uid',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'oldpw',
			'display_name'	=>	'旧密码',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'password',
			'display_name'	=>	'新密码',
			'input_type'	=>	'text'
		]
	];

	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[3])&&is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			$rootdir = $this->request->ARI->dirname.'/';
			$basedir = $rootdir.$this->app->id;
			$guid = $this->request->ARI->patharr[3];
			$item = MemberModel::byGUID($guid);

			if($item){
				$this->assign('formname', '修改登录密码<a href="'.$rootdir.'logger/ph/account/'.$guid.'">编辑个人信息</a><a href="'.$rootdir.'logger/ph/pin-code/'.$guid.'">修改管理员认证码</a>');
				$this->assign('form', self::buildForm([
					'uid'		=>	$item->uid,
					'oldpw'		=>	'',
					'password'	=>	''
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
						'action'=>	__aurl__.'users/members/'.$guid,
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