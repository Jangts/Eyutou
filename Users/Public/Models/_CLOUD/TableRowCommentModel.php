<?php
namespace PM\_CLOUD;

use PDO;
use Status;
use Storage;
use Model;

/**
 * @class PM\_CLOUD\TableRowMetaModel
 * Special Use Content Light Model
 * 专用内容轻模型
 * 是一个忽略不同预设字段差异的精简的单一标准模型
**/
final class TableRowCommentModel extends \AF\Models\BaseDeepModel {
    protected static
    $__parentFieldName = 'parent_id',
    $fileStoragePath = DPATH.'CLOUDS/tablerows/comments/',
    $fileStoreLifetime = 0,
    $staticMemorizeStorage = [],
    $staticFileStorage,
    $tablenamePrefix = DB_YUN,
	$tablenameAlias = 'comments',
	$defaultPorpertyValues  = [
		'id'				=>	NULL,
		'row_id'			=>	0,
		'parent_id'			=>	0,
		'pubtime'           =>  DATETIME,
		'content'			=>	'',
		'SK_STATE'			=>	1
	];
}