<?php
namespace CTH\Press\Models;

use PM\_CLOUD\AbstractTableRowsLISTAVModel;

class NewsListAVModel extends AbstractTableRowsLISTAVModel {
	public static $itemurl = '/news/news/';

	public static function loadStaticProperties(){
		if(
			is_file($filename = __DIR__.'/avmvar_providers/'.static::$tablename.'_list.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}else{
			parent::loadStaticProperties();
		}
	}
}