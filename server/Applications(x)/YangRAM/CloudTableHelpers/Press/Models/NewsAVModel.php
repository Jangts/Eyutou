<?php
namespace CTH\Press\Models;

use PM\_CLOUD\AbstractTableRowCRUDAVModel;

class NewsAVModel extends AbstractTableRowCRUDAVModel {
	public static
	$itemname = '新闻',
	$listurl = '/news/news-list/';

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