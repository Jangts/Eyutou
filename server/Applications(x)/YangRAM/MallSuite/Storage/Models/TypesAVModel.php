<?php
namespace Goods\Models;

use PM\_1008\ProductionTypeModel;
use PM\_1008\BrandModel;

class TypesAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	use traits\amvmethods;

	public static
	$model = 'PM\_1008\ProductionTypeModel';

	public static function loadStaticProperties(){
		if(
			is_file($filename = __DIR__.'/avmvar_providers/types.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}
	}

	public static function __putDataToNewModel($item){
		if(isset($_GET['tabid'])&&isset(static::$__avmtabs[$_GET['tabid']])){
			$item->put(static::$__avmtabs[$_GET['tabid']]['where']);
		}
		return $item;
	}

	public function initialize(){
		static::loadBrandTabs();
		static::loadStaticProperties();
		return [
			'listname'	=>	static::$listname,
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>',
			'formname'	=>	static::$formname
		];
	}

	protected function buildTableRows($basedir, $items = [], $qs = ''){
        $rows = [];
        foreach($items as $index=>$item){
            if($brand = $item->getBrand()){
				$itemurl = $basedir.'/'.$item->id;
				$rows[] = [
					'__index'		=>	[$index + 1],
					'name'			=>	[$item->typename, $itemurl.$qs, false],
					'brand'			=>	[$brand->brand_name],
					'__ops'			=>	['<a href="'.$itemurl.$qs .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">删除</a>']
				];
            }else{
				$item->destroy();
            }
            
        }
        return $rows;
	}
	
	protected function setSelections($item){
        $brands = BrandModel::getALL();
		foreach($brands as $brand){
			$brandOptions[] = [$brand['id'], $brand['brand_name']];
		}
		static::$selectOptions['brand_id'] = $brandOptions;
	}
}