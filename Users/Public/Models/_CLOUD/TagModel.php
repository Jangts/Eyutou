<?php
namespace PM\_CLOUD;

use Status;

/**
 *Special Use Content Category Model
 *归档文件夹模型
**/
final class TagModel extends \AF\Models\BaseR3Model {
	protected static
    $fileStoragePath = '',
    $tablenamePrefixRewritable = true,
    $tablenamePrefix = DB_YUN,
    $tablenameAlias = 'tagmaps',
    $uniqueIndexes = ['id'],
    $AIKEY = 'id',
    $defaultPorpertyValues = [
        'id'				=>	0,
        'tag'				=>	'',
        'type'				=>	'',
        'tablename'         =>  '',
        'item'     		=>	0
    ];

    public static function byItem($item, $tablename){
        return self::query(['tablename' => $tablename, 'item' => $item]);
    }

    public static function byTablename($tablename){
        if(is_string($tablename)){
            return self::query("`tablename` = '$tablename'");
        }else{
            return self::query("`tablename` = NULL");
        }
        return [];
    }

    public static function byType($item, $type){
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

    protected function __put(array $input, $isSaved = false){
        parent::__put($input, $isSaved);
        $this->readonly = true;
    }
}