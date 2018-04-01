<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class TagModel extends \AF\Models\BaseMapModel {
	protected static
    $tablenamePrefixRewritable = true,
    $tablenamePrefix = DB_YUN,
    $tablenameAlias = 'tagmaps',
    $defaultPorpertyValues = [
        'id'				=>	0,
        'tag'				=>	'',
        'type'				=>	'',
        'tablename'         =>  '',
        'item'     		=>	0
    ];

    public static function getTagsByItem($item, $tablename){
        return self::query(['tablename' => $tablename, 'item' => $item]);
    }

    public static function getTagsByTablename($tablename){
        if(is_string($tablename)){
            return self::query("`tablename` = '$tablename'");
        }
        return [];
    }

    public static function getTagsByType($item, $type){
        return self::query(['type' => $type, 'item' => $item]);
	}

    public static function resetTags($tags, $item, $type, $tablename){
        $querier = self::initQuerier();
        // 清空之前的标签
        $querier->requires("type = '$type' AND item = '$item' AND tag NOT IN ('".join("','", $tags)."')")->delete();

        // 重新插入新标签，在不打断的情况下，PDO会使用模板来处理连续插入
        foreach($tags as $tag){
            $querier->insert([
                'tag'	    =>	$tag,
                'type'	    =>	$type,
                'tablename'	=>	$tablename,
                'item'	    =>	$item
            ], true);
        }  
    }
}