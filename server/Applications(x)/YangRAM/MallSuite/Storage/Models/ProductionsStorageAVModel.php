<?php
namespace Goods\Models;

use PM\_1008\ProductionModel;
use PM\_1008\ProductionTypeModel;

class ProductionsStorageAVModel extends ProductionsAVModel {
	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		
		self::loadAllTypeTags();
		list($require, $type_id, $brand_id) = $this->conditions(ProductionModel::INSTORAGE);
		
		$count = ProductionModel::getCOUNT($require);
		$productions  = ProductionModel::getRows($type_id, $brand_id, ProductionModel::INSTORAGE, $orderby, $range);

		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id;
		$basedir = $stagedir.'/p/production/';

		if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = '';
		}
		$rows = $this->buildTableRows($basedir, $productions, $range, $sort);
		
		self::$creater = NULL;

		$this->assign('__avmtabs', 	self::buildTabs($stagedir.'/s/productions-storage/'));
		$this->assign('__avmtags', 	self::buildTags($stagedir.'/s/productions-storage/'));

        $this->assign('listname', 	'仓库产品列表');
        $this->assign('itemlist', 	self::buildTable($rows, $range[2]));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}