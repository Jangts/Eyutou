<?php
namespace Goods\Models;

use PM\_1008\ProductionModel;
use PM\_1008\CategoryModel;
use PM\_1008\BrandModel;
use PM\_1008\ProductionTypeModel;

class ProductionAVModel extends \PM\_STUDIO\BaseFormAVModel {
	use traits\amvmethods;

	public static function loadStaticProperties(){
		if(
			is_file($filename = __DIR__.'/avmvar_providers/productions_form.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}
	}

	public function initialize(){
		static::loadBrandTabs();
		static::loadStaticProperties();
		return [
			'formname'	=>	static::$formname
		];
    }

	public function analysis($admininfo){
		$basedir = $this->request->ARI->dirname.'/'.$this->app->id.'/p/productions/';
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
				$this->assign('href', $basedir);
				
				$this->template = 'production404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'删除产品',
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	__aurl__.'1008/productions/'.$guid,
				'href'	=>	$basedir
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
			$production = static::__putDataToNewModel($production);
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

		$this->assign('form', self::buildForm($production->getArrayCopy(), $method));
		if(isset($_GET['page'])){
            $qs = static::buildQueryString($_GET['page']);
        }else{
            $qs = static::buildQueryString();
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
				'href'	=>	$basedir.$qs
			],
			$button2,
			[
				'name' 	=>	'保存到仓库',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'1008/productions/'.$guid.'?state=0',
				'href'	=>	$basedir.$qs
			],
			[
				'name' 	=>	'发布上架',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'1008/productions/'.$guid.'?state=1',
				'href'	=>	$basedir.$qs
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}