<?php
namespace AF\Models\traits;

trait counting {
    /**
	 * 统计记录行数
	 * 
	 * @access public
	 * @static
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
	 * @return int
	**/
    public static function getCOUNT($require = "1 = 1") {
		$querier = static::initQuerier();
		$querier->requires($require)->orderby(false)->take(0);
		return $querier->count();
    }
    
    final public static function recordByGUID($guid){
        return true;
    }
    
    final public static function getRecordsCount($guid = NULL){
        return 0;
    }

    final public static function getLastRecord($guid = NULL){
        return DATETIME;
    }
}