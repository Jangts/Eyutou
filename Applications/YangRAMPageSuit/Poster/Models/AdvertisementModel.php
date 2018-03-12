<?php
namespace Pages\Ads\Models;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class AdvertisementModel extends \AF\Models\BaseR3Model {
	const
	ID_DESC = [['id', true, self::SORT_REGULAR]],
	ID_ASC = [['id', false, self::SORT_REGULAR]],
	TITLE_DESC = [['title', true, self::SORT_REGULAR]],
	TITLE_ASC = [['title', false, self::SORT_REGULAR]],
	TITLE_DESC_GBK = [['title', true, self::SORT_CONVERT_GBK]],
	TITLE_ASC_GBK = [['title', false, self::SORT_CONVERT_GBK]],
	DISPLAY_DESC = [['display', true, self::SORT_REGULAR]],
	DISPLAY_ASC = [['display', false, self::SORT_REGULAR]],
	HITS_DESC = [['hits', true, self::SORT_REGULAR]],
	HITS_ASC = [['hits', false, self::SORT_REGULAR]];

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
	
	public static function getAdsByType($type, array $orderby = self::ID_ASC){
		if(is_string($type)&&in_array($type, ['html', 'image', 'text', 'video'])){
			if($tablename&&($tablemeta = TableMetaModel::byGUID($tablename))){
				return self::query("`type` = '$type'" , $orderby);
			}
		}
		return [];
	}
	
	public static function create(array $option = ['New Ad', 'image']){
		list($title, $type) = $option;
		if(in_array($type, ['html', 'image', 'text', 'video'])){
			$obj = new static;
			$obj->type = $type;
			if(is_string($title)){
				$obj->title = $title;
			}
			return $obj;
		}
		return false;
	}

	public static function post(array $input){
		if(isset($input['type'])){
			$obj = new static;
        	$obj->__put($input, false);
        	return $obj->__insert();
		}
		return false;
	}
}