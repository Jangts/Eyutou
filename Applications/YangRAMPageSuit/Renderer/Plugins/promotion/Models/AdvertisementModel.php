<?php
namespace Pages\Main\Plugins\promotion\Models;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class AdvertisementModel extends \AF\Models\BaseR3Model {
	protected static
	$fileStoragePath = true,
	$fileStoreLifetime = 0,
	$tablenameAlias = 'ads',
	$tablenamePrefixRewritable = true,
	$defaultPorpertyValues  = [
		'id'				=>	0,
		'title'				=>	'New Ad',
		'type'				=>	'image',
		'content'			=>	'',
		'link'				=>	'',
        'display'		    =>	0,
		'hits'				=>	0
	];

	public static function post(array $input){
		return false;
	}
}