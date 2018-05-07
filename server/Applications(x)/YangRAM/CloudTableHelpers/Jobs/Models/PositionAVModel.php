<?php
namespace CTH\Jobs\Models;

use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowModel;
use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\AbstractTableRowCRUDAVModel;

class PositionAVModel extends AbstractTableRowCRUDAVModel {
	public static
	$itemname = '招聘信息',
	$listurl = '/positions/positions/';

	public static function loadStaticProperties(){
		if(
			is_file($filename = __DIR__.'/avmvar_providers/'.static::$tablename.'_form.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}else{
			parent::loadStaticProperties();
		}
	}
}