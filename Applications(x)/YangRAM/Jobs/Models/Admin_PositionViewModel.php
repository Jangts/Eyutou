<?php
namespace Jobs\Models;

use PM\_CLOUD\FolderModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;

class Admin_PositionViewModel extends \PM\_2\BaseFormViewModel {
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
			'field_name'	=>	'FOLDER',
			'display_name'	=>	'新闻分类',
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
			'field_name'	=>	'PUBTIME',
			'display_name'	=>	'本站发布时间',
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
			'field_name'	=>	'TITLE',
			'display_name'	=>	'招聘公告标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'POSINAME',
			'display_name'	=>	'职位名称',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'PLACE_WORKING',
			'display_name'	=>	'工作地点/场所',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'SALARY_RANGE',
			'display_name'	=>	'薪资范围',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'NUMBER',
			'display_name'	=>	'招聘人数',
			'input_type'	=>	'counter'
		],
		[
			'field_name'	=>	'IS_FULLTIME',
			'display_name'	=>	'是否全职',
			'input_type'	=>	'radio'
		],		
		[
			'field_name'	=>	'PROBATION',
			'display_name'	=>	'试用期',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'POSI_DESC',
			'display_name'	=>	'职位描述',
			'input_type'	=>	'editor'
		],
		[
			'field_name'	=>	'POSI_REQUIRE',
			'display_name'	=>	'职务要求',
			'input_type'	=>	'editor'
		],
		[
			'field_name'	=>	'AGE_RANGE',
			'display_name'	=>	'年龄要求',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'SEX',
			'display_name'	=>	'性别要求',
			'input_type'	=>	'radio'
		],
		[
			'field_name'	=>	'EDUCATION',
			'display_name'	=>	'学历要求',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'CERTIFICATE',
			'display_name'	=>	'资质要求',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'CONTACT',
			'display_name'	=>	'联系人',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'LEVEL',
			'display_name'	=>	'排序级数',
			'input_type'	=>	'counter'
		]
	],
	$selectOptions = [
		'IS_FULLTIME'	=>	[
			['0', '否'],
			['1', '是']
		],
		'SEX'	=>	[
			['0', '不限'],
			['1', '仅男性'],
			['2', '仅女性']
		]
	];

	

	public function analysis($admininfo){
		if(isset($this->request->ARI->patharr[2])&&is_numeric($this->request->ARI->patharr[2])&&$this->request->ARI->patharr[2]>0){
			$guid = $this->request->ARI->patharr[2];
			$news = TableRowModel::byGUID($guid);
			if(!$news){
				$this->assign('href', $this->request->ARI->dirname.'/'.$this->app->ID.'/Positions/');
				
				$this->template = 'news404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'移除',
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid,
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Positions/'
			];
		}else{
			$guid = 0;
			$news = TableRowModel::create(['tablename' => 'positions']);
			$method = 'POST';
			$button2 = NULL;
		}

		$categories = FolderModel::getFoldersByTableName('positions');
		$categoryOptions = [];
		foreach($categories as $category){
			$categoryOptions[] = [$category['id'], $category['name']];
		}
		static::$selectOptions['FOLDER'] = $categoryOptions;

		$this->assign('formname', '编辑招聘信息');
		$this->assign('form', self::buildForm($news->getArrayCopy(), $method));
		$this->assign('buttons', [
			[
				'name' 	=>	'重置',
				'order'	=>	'reset',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	''
			],
			[
				'name' 	=>	'返回',
				'order'	=>	'anchor',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Positions/'
			],
			$button2,
			[
				'name' 	=>	'保存到待审',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=0',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Positions/'
			],
			[
				'name' 	=>	'发布',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=1',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Positions/'
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}