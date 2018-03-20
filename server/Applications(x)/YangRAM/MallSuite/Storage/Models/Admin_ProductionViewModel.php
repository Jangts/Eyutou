<?php
namespace Goods\Models;

use PM\_1008\ProductionModel;
use PM\_1008\CategoryModel;
use PM\_1008\BrandModel;
use PM\_1008\ProductionTypeModel;

class Admin_ProductionViewModel extends \PM\_STUDIO\BaseFormViewModel {
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
			'field_name'	=>	'standard',
			'display_name'	=>	'产品规格',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'description',
			'display_name'	=>	'产品SEO描述',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'link',
			'display_name'	=>	'产品链接',
			'input_type'	=>	'text'
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
		$categoryOptions = [
			['2',	'饼干糕点']
		];
		$brandOptions = [
			['0',	'不设置品牌']
		];
		$typeOptions = [
			['0',	'不设置类型']
		];
		if(isset($this->request->ARI->patharr[3])&&is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			$guid = $this->request->ARI->patharr[3];
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
				'action'=>	__aurl__.'1008/productions/'.$guid,
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'
			];
			if($production->category_id&&$category=CategoryModel::byGUID($production->category_id)){
				$categories_ids = $category->getOffspringIds(true);
			}else{
				$categories_ids = ['2'];
			}
			$types = ProductionTypeModel::query("`brand_id` = '$production->brand_id'");
			foreach($types as $type){
				$typeOptions[] = [$type['id'], $type['typename']];
			}
		}else{
			$guid = 0;
			if(count($productions = ProductionModel::query('1 = 1', ProductionModel::ID_DESC, 1))){
				$category_id = $productions[0]->category_id;
				$production = ProductionModel::create(['category_id' => $category_id]);
			}else{
				$production = ProductionModel::create();
			}
			$method = 'POST';
			$button2 = NULL;
			$categories_ids = ['2'];
		}

		// $categories = CategoryModel::getALL();
		// foreach($categories as $category){
		// 	$categoryOptions[] = [$category['id'], $category['category_name']];
		// }
		static::$selectOptions['category_id'] = $categoryOptions;

		$brands = BrandModel::query("`category_id` IN ('".implode("','", $categories_ids)."')");		
		foreach($brands as $brand){
			$brandOptions[] = [$brand['id'], $brand['brand_name']];
		}
		static::$selectOptions['brand_id'] = $brandOptions;
				
		static::$selectOptions['type_id'] = $typeOptions;

		$this->assign('formname', '编辑产品信息');
		$this->assign('form', self::buildForm($production->getArrayCopy(), $method));
		if(isset($_GET['sort'])){
            $selects = '?sort='. $_GET['sort'];
        }else{
            $selects = '?sort=';
        }
        if(isset($_GET['page'])){
            $selects .= '&page='. $_GET['page'];
        }else{
            $selects .= '&page=';
        }
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
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'.$selects
			],
			$button2,
			[
				'name' 	=>	'保存到仓库',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'1008/productions/'.$guid.'?state=0',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'.$selects
			],
			[
				'name' 	=>	'发布上架',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'1008/productions/'.$guid.'?state=1',
				'href'	=>	$this->request->ARI->dirname.'/'.$this->app->ID.'/Productions/'.$selects
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}