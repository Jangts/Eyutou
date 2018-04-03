<?php
namespace Goods\Models;

use PM\_1008\ProductionModel;
use PM\_1008\BrandModel;
use PM\_1008\ProductionTypeModel;

class ProductionsAVModel extends \PM\_STUDIO\BaseTableAVModel {
	use traits\amvmethods;

	public static
	$__sorts = [
		'id'				=>	ProductionModel::ID_ASC,
		'id_reverse'		=>	ProductionModel::ID_DESC,
		'brand'				=>	ProductionModel::BRAND_ASC,
		'brand_reverse'		=>	ProductionModel::BRAND_DESC,
		'type'				=>	ProductionModel::TYPE_ASC,
		'type_reverse'		=>	ProductionModel::TYPE_DESC,
		'ctime'				=>	ProductionModel::CTIME_ASC,
		'ctime_reverse'		=>	ProductionModel::CTIME_DESC,
		'ptime'				=>	ProductionModel::TIME_ASC,
		'ptime_reverse'		=>	ProductionModel::TIME_DESC,
		'grade'				=>	ProductionModel::GRADE_ASC,
		'grade_reverse'		=>	ProductionModel::GRADE_DESC,
		'name'				=>	ProductionModel::NAME_ASC,
		'name_reverse'		=>	ProductionModel::NAME_DESC,
		'name_gb'			=>	ProductionModel::NAME_ASC_GBK,
		'name_gb_reverse'	=>	ProductionModel::NAME_DESC_GBK
	],
	$__sortby = ProductionModel::ID_DESC;

	public static function loadTypeTags($brand_id){
		$types = ProductionTypeModel::getTypesByBrand($brand_id);
		if($types = ProductionTypeModel::getTypesByBrand($brand_id)){
			$tags = [];
			foreach ($types as $type) {
				$tags['type'.$type->id] = [
					'name'	=>	$type->typename,
					'title'	=>	empty($type->description) ? $type->typename : $type->description,
					'where'	=>	['type_id'=>$type->id]
				];
			}
			static::$__avmtags = $tags;
		}
	}

	public static function loadStaticProperties(){
		if(
			is_file($filename = __DIR__.'/avmvar_providers/productions_list.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}
	}

	public function initialize(){
		static::loadBrandTabs();
		static::loadAllTypeTags();
		static::loadStaticProperties();
		return [
			'listname'	=>	static::$listname,
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
	}

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		
		self::loadAllTypeTags();
		list($require, $type_id, $brand_id) = $this->conditions(ProductionModel::ONSALE);
		
		$count = ProductionModel::getCOUNT($require);
		$productions  = ProductionModel::getRows($type_id, $brand_id, ProductionModel::ONSALE, $orderby, $range);

		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/p/production/';

		$qs = static::buildQueryString($range[2]);
		$rows = $this->buildTableRows($basedir, $productions, $qs);
		self::$creater['url'] = $basedir.$qs;

		$this->assign('__avmtabs', 	self::buildTabs($stagedir.'/p/productions/'));
		$this->assign('__avmtags', 	self::buildTags($stagedir.'/p/productions/'));

		$this->assign('itemlist', 	self::buildTable($rows, $range[2]));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}

	protected function conditions($require){
		if(empty($_GET['tagid'])||empty(static::$__avmtags[$_GET['tagid']])){
			$type_id = NULL;
			if(empty($_GET['tabid'])||empty(static::$__avmtabs[$_GET['tabid']])){
				$brand_id = NULL;
			}else{
				$brand_id = static::$__avmtabs[$_GET['tabid']]['where']['brand_id'];
				self::loadTypeTags($brand_id);
				$require = array_merge(static::$__avmtabs[$_GET['tabid']]['where'], ProductionModel::ONSALE);
			}
        }else{
			$type_id = static::$__avmtags[$_GET['tagid']]['where']['type_id'];
			if($type = ProductionTypeModel::byGUID($type_id)){
				$_GET['tabid'] = 'brand'.$type->brand_id;
				self::loadTypeTags($brand_id = $type->brand_id);
				$require = array_merge([
					'type_id'	=>	$type_id,
					'brand_id'	=>	$brand_id
				], ProductionModel::ONSALE);
			}else{
				$type_id = NULL;
				$brand_id = NULL;
				unset($_GET['tagid']);
			}
		}
		return [$require, $type_id, $brand_id];
	}

	protected function buildTableRows($basedir, $productions, $qs = ''){
        $rows = [];
		foreach($productions as $index=>$production){
			if($production->category_id&&$production->brand&&$production->type){
				$itemurl = $basedir.$production->id;
				$rows[] = [
					'__index'	=>	[$index + 1],
					'name'		=>	[$production->name, $itemurl.$qs, false],
					'brand'		=>	[$production->brand['brand_name']],
					'type'		=>	[$production->type['typename']],
					'time'		=>	[$production->time_onsale],
					'__count'	=>	[0],
					'__ops'		=>	['<a href="'.$itemurl.$qs .'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/1008/productions/'.$production->id.'" href="javascript:;">移除</a>']
				];
			}else{
				$production->destroy();
			}
		}
		return $rows;
    }
}