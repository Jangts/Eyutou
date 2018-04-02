<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

use DBQ;

/**
 *
**/
class LocationModel extends ObjectModel {

	// System Informations
	protected $modelProperties = [
		'ADDR'		=>	'',
		'BRIEF'		=>	'',
		'LANG'		=>	'',
		'LOC_ID'	=>	'',
		'NAME'		=>	'',
		'OWNER'		=>	'',
		'REMARK'	=>	'',
		'ID'		=>	'',
		'LAT'		=>	'',
		'LNG'		=>	'',
		'TEL1'		=>	'',
		'TEL2'		=>	'',
		'EMAIL'		=>	''
	];

	public function __construct(){
		$la_info = DBQ::one(DB_REG.'languages', "LANG = '".$GLOBALS['NEWIDEA']->LANGUAGE."'");
		if(empty($la_info)){
			$la_info = DBQ::one(DB_REG.'languages', "lang = '"._LANG_."'");
		}
		if(empty($la_info)){
			$la_info = DBQ::one(DB_REG.'languages');
		}
		if(!empty($la_info)){
			$lo_info = DBQ::id(DB_REG.'locations', $la_info["LOC_ID"]);
			if(!empty($lo_info)){
				$info = array_merge($la_info, $lo_info);
				foreach($info as $key	=>$val){
					$this->modelProperties[strtoupper($key)] = $val;
				}
			}
		}
	}
}
