<?php
namespace Goods\Models;

use PM\_8\ProductionModel;
use PM\_8\CategoryModel;
use PM\_8\BrandModel;
use PM\_8\ProductionTypeModel;

class Admin_ProductionViewModel extends \PM\_2\BaseFormViewModel {
	public static $inputs = [
		[
			'field_name'	=>	'id',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'state',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'time_onsale',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'产品标题',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'code',
			'display_name'	=>	'产品编号',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'category_id',
			'display_name'	=>	'产品类目',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'brand_id',
			'display_name'	=>	'所属品牌',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'type_id',
			'display_name'	=>	'产品类型',
			'input_type'	=>	'select'
		],
		
		[
			'field_name'	=>	'image',
			'display_name'	=>	'产品图片',
			'input_type'	=>	'image'
		],
		[
			'field_name'	=>	'description',
			'display_name'	=>	'产品SEO描述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'detail',
			'display_name'	=>	'产品详情描述',
			'input_type'	=>	'editor'
		],
		[
			'field_name'	=>	'rank',
			'display_name'	=>	'产品评级',
			'input_type'	=>	'select'
		]
	],
	$selectOptions = [
		'brand_id'		=>	[
			['0', '请先选择类目']
		],
		'type_id'		=>	[
			['0', '请先选择类目'],
		],
		'rank'		=>	[
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
		$categoryOptions = [];
		if(isset($this->request->ARI->patharr[2])&&is_numeric($this->request->ARI->patharr[2])&&$this->request->ARI->patharr[2]>0){
			$guid = $this->request->ARI->patharr[2];
			$production = ProductionModel::byGUID($guid);
			if(!$production){
				$this->assign('href', $this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/');
				
				$this->template = 'production404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'删除产品',
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	__aurl__.'8/productions/'.$guid,
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'
			];
		}else{
			$guid = 0;
			if(count($productions = ProductionModel::query('1 = 1', ProductionModel::ID_DESC, 1))){
				$category_id = $productions[0]->category_id;
				$production = ProductionModel::create(['category_id' => $category_id]);
			}else{
				$production = ProductionModel::create();
				$categoryOptions = [
					'0'	=>	'请选择类目'
				];
			}
			$method = 'POST';
			$button2 = [
				'name' 	=>	'返回列表',
				'order'	=>	'anchor',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'
			];
		}

		$categories = CategoryModel::getALL();
		foreach($categories as $category){
			$categoryOptions[] = [$category['id'], $category['category_name']];
		}
		static::$selectOptions['category_id'] = $categoryOptions;
		if($production->category_id){
			$categories_ids = $category->getOffspringIds(true);
			$brands = BrandModel::query("`category_id` IN ('".implode("','", $categories_ids)."')");
			$types = ProductionTypeModel::query("`category_id` IN ('".implode("','", $categories_ids)."')");

			foreach($brands as $brand){
				$brandOptions[] = [$brand['id'], $brand['brand_name']];
			}
			static::$selectOptions['brand_id'] = $brandOptions;

			foreach($types as $type){
				$typeOptions[] = [$type['id'], $type['typename']];
			}
			static::$selectOptions['type_id'] = $typeOptions;
		}

		$this->assign('formname', '编辑产品信息');
		$this->assign('form', self::buildForm($production->getArrayCopy(), $method));
		$this->assign('buttons', [
			[
				'name' 	=>	'重置表单',
				'order'	=>	'reset',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	''
			],
			$button2,
			[
				'name' 	=>	'保存到仓库',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'8/productions/'.$guid.'?state=0',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'
			],
			[
				'name' 	=>	'发布上架',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'8/productions/'.$guid.'?state=1',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}