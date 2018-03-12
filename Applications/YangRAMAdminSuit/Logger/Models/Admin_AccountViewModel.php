<?php
namespace Admin\Logger\Models;

use PM\_CLOUD\FolderModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class Admin_AccountViewModel extends \PM\_STUDIO\BaseFormViewModel {
	public static $inputs = [
		[
			'field_name'	=>	'UID',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'OPERATORNAME',
			'display_name'	=>	'管理员昵称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'AVATAR',
			'display_name'	=>	'管理员头像',
			'input_type'	=>	'image'
		],
		[
			'field_name'	=>	'LANGUAGE',
			'display_name'	=>	'文章评级',
			'input_type'	=>	'select'
		]
	],
	$selectOptions = [
		'LANGUAGE'	=>	[
			['zh-cn', '中文简体'],
			['zh-tw', '中文繁體'],
			['en-uk', 'British English'],
			['en-us', 'American English']
		]
	];

	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[2])&&is_numeric($this->request->ARI->patharr[2])&&$this->request->ARI->patharr[2]>0){
			$basedir = $this->request->ARI->dirname.'/';
			$baseurl = $basedir.$this->app->ID;
			$guid = $this->request->ARI->patharr[2];
			$item = AdministratorModel::byGUID($guid);

			if($item){
				$this->assign('formname', '编辑个人信息<a href="'.$basedir.'users/password/'.$guid.'">修改登录密码</a><a href="'.$basedir.'logger/pin-code/'.$guid.'">修改管理员认证码</a>');
				$this->assign('form', self::buildForm($item->getArrayCopy(), 'PUT'));
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