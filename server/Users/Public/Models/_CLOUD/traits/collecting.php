<?php
namespace PM\_CLOUD\traits;

use PDO;
use Model;
use PM\_CLOUD\TableRowMetaModel;

trait trmm_collecting {
    /**
	 * 查询
	 */
	private static function decodeReturnRows($result, $returnFormat = Model::LIST_AS_OBJS){
		if($returnFormat===Model::LIST_AS_ARRS){
			return array_map(function($modelProperties){
				return array_map('htmlspecialchars_decode', $modelProperties);
			}, $result->getArrayCopy());
		}
		$objs = [];
		$pdos = $result->getIterator();
		while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
			$modelProperties = array_map('htmlspecialchars_decode', $modelProperties);
			self::$staticMemorizeStorage['items'][$modelProperties['ID']] = $modelProperties;
			$objs[] = new self($modelProperties);
		}
        return $objs;
	}
	
	/**
	 * 查询
	 */
	public static function query($require = "1 = 1", array $orderby = self::ID_ASC, $range = 0, $returnFormat = Model::LIST_AS_OBJS, $selecte = '*'){
        self::init();
		$result = self::executeQuerySelect(self::$staticQuerier, $require, $orderby, $range);
        if($result){
			return self::decodeReturnRows($result, $returnFormat);
        }
        return [];
    }
    
    /**
	 * 获取全部
	 */
	public static function getALL($tablename = NULL, $type = NULL){
		self::init();
		$querier = self::$staticQuerier->requires();
		if(is_string($tablename)){
			$querier->where('TABLENAME', $tablename);
		}
		if(is_string($type)&&($type!=='file')){
			$querier->where('TYPE', $type);
		}
		$result = $querier->take(0)->orderby(false)->select();
        if($result){
            return self::decodeReturnRows($result);
		}
        return [];
	}
	
	/**
	 * 按条件获取列表
	 */
	public static function getRows($tablename = NULL, $group_id = NULL, $state = self::UNRECYCLED, array $orderby = self::ID_DESC, $start = 0, $num = 18, $returnFormat = Model::LIST_AS_OBJS){
		if($state === self::PUBLISHED&&count($orderby)===1&&(empty($orderby[0][2])||$orderby[0][2]===self::SORT_REGULAR)){
			// 已发布行可以读取缓存
			if($group_id&&is_numeric($group_id)){
				if(is_string($tablename)){
					$group_idInfo = GROUP::byGUID($group_id);
					if($group_idInfo->tablename===$tablename){
						return self::getPublishedRows($group_id, $start, $num, $orderby[0], $returnFormat);
					}
				}else{
					return self::getPublishedRows($group_id, $start, $num, $orderby[0], $returnFormat);
				}
			}else{
				if(is_string($tablename)){
					return self::getPublishedRows($tablename, $start, $num, $orderby[0], $returnFormat);
				}
			}
		}
		$result = self::getQuery($tablename, $group_id, $state, $orderby, $start, $num)->select();
    	if($result){
			return self::decodeReturnRows($result, $returnFormat);
		}
        return [];
    }

    /**
	 * 获取已发布的
	 */
	public static function getPublishedRows($class, $start = 0, $num = 0, array $orderby = ['PUBTIME', false], $returnFormat = Model::LIST_AS_ARRS){
		self::init();
		if(is_string($class)||$class!='0'){
			self::$listFileStorage->setNameSpace($class.'/');
		}
		if(in_array($orderby[0], self::SORTKEYS)){
			$array = self::$listFileStorage->take($orderby[0]);
		}else{
			$array = false;
		}
		if(!$array){
			if(is_numeric($class)&&$class!='0'){
				$array = self::query("`GROUPID` = $class AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", [[$orderby[0], true]], 0, Model::LIST_AS_ARRS);
				self::$listFileStorage->store($orderby[0], $array);
			}elseif(is_string($class)){
				$array = self::query("`TABLENAME` = '$class' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", [[$orderby[0], true]], 0, Model::LIST_AS_ARRS);
				self::$listFileStorage->store($orderby[0], $array);
			}else{
				return [];
			}
			$array = array_map(function($modelProperties){
				return array_map('htmlspecialchars_decode', $modelProperties);
			}, $array);
		}
		if($orderby[1]===false){
			$array = array_reverse($array);
		}
		if(is_numeric($num)){
			$num = intval($num);
		}else{
			$num = 0;
		}
		if(is_numeric($start)){
			$start = intval($start);
		}else{
			$start = 0;
		}
		if($start||$num){
			$array = array_slice($array, $start, $num);
		}
		if($returnFormat===Model::LIST_AS_ARRS){
			return $array;
		}
		$objs = [];
		foreach($array as $modelProperties){
			self::$staticMemorizeStorage['items'][$modelProperties['ID']] = $modelProperties;
			$objs[] = new self($modelProperties);
		}
        return $objs;
    }
    
    /**
	 * 获取未分类的列表
	 */
	public static function getUnclassifiedRows($tablename, $state = self::UNRECYCLED, array $orderby = self::ID_ASC, $start = 0, $num = 18, $returnFormat = Model::LIST_AS_OBJS){
		if(preg_match('/^\w+$/', $tablename)){
			switch($state){
				case self::RECYCLED:
				$str = ' AND SK_IS_RECYCLED = 1';
				break;

				case self::UNRECYCLED:
				$str = ' AND SK_IS_RECYCLED = 0';
				break;
				case self::PUBLISHED:
				$str = ' AND SK_IS_RECYCLED = 0 AND SK_STATE = 1';
				break;
				case self::UNPUBLISHED:
				$str = ' AND SK_IS_RECYCLED = 0 AND SK_STATE = 0';
				break;
				case self::POSTED:
				$str = " AND SK_IS_RECYCLED = 0 AND SK_STATE = '-1'";
				break;
				default:
				$str = '';
			}
			$result = self::query("`TABLENAME` = '$tablename' AND `GROUPID` = 0".$str, $orderby, [$start, $num], $returnFormat);
		}
		return [];
	}
    
    /**
	 * 模糊查询行
	 */
	public static function getRowsOf($class, array $orderby = self::ID_ASC, $range = 0, $returnFormat = Model::LIST_AS_OBJS){
		if($class){
			if(is_numeric($class)&&$class!='0'){
				return self::query("`GROUPID` = $class", $orderby, $range, $returnFormat);
			}elseif(is_string($class)&&preg_match('/^\w+$/', $$class)){
				return self::query("`TABLENAME` = '$class'", $orderby, $range, $returnFormat);
			}
		}
		return [];
    }
    
	/**
	 * 根据获取指定类型的所有行
	 */
	public static function getRowsByType($type, array $orderby = self::ID_ASC, $range = 0, $returnFormat = Model::LIST_AS_OBJS){
		if(preg_match('/^\w+$/', $type)&&(type!=='file')){
			return self::query("`TYPE` = '$type'", $orderby, $range, $returnFormat);
		}
		return [];
	}

	/**
	 * 根据获取指定表的所有行
	 */
	public static function getRowsByTableName($type, array $orderby = self::ID_ASC, $range = 0, $returnFormat = Model::LIST_AS_OBJS){
		if(preg_match('/^\w+$/', $type)){
			return self::query("`TABLENAME` = '$type'", $orderby, $range, $returnFormat);
		}
		return [];
	}

	/**
	 * 获取指定文件夹的所有行
	 */
	public static function getRowsByGROUPID($group_id, array $orderby = self::ID_ASC, $range = 0, $returnFormat = Model::LIST_AS_OBJS){
		if($group_id&&is_numeric($group_id)){
			return self::query("`GROUPID` = $group_id", $orderby, $range, $returnFormat);
		}
		return [];
	}

	/**
	 * 获取某标签下的行
	 */
	public static function getRowsByTagName($tag, $class = NULL, array $orderby = self::ID_ASC, $range = 0, $returnFormat = Model::LIST_AS_OBJS){
		if(is_string($tag)){
			if(is_numeric($class)&&$class!='0'){
				$group_id = TRGroupModel::byGUID($class);
				$tablename = $group_id->tablename;
			}elseif(is_string($class)){
				$tablename = $class;
			}
			$result = TagModel::byTablename($tablename);
			if($result){
				$array1 = $result->getArrayCopy();
				$array2 = [];
				foreach($array1 as $row){
					$array2[] = $row['item'];
				}
				return self::query("`ID` in (".join(',', $array2).") AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", $orderby, $range, $returnFormat);
			}
		}
		return [];
	}

	/**
	 * 获取某时刻前的行
	 */
	public static function getRowsBeforeTime($time, $searchby = self::SEARCH_ALL, $class = NULL){
		$time = date('Y-m-d H:i:s', strtotime($time));
		switch($searchby){
			case self::SEARCH_TYPE:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TYPE` = '$class'", self::PUBTIME_DESC);

			case self::SEARCH_TABLE:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$class'", self::PUBTIME_DESC);

			case self::SEARCH_GROUPID:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `GROUPID` = $class", self::PUBTIME_DESC);

			defailt:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", self::PUBTIME_DESC);
		}
		return [];
	}

	/**
	 * 获取某时刻后的行
	 */
	public static function getRowsAfterTime($time, $searchby = self::SEARCH_ALL, $class = NULL){
		$time = date('Y-m-d H:i:s', strtotime($time));
		switch($searchby){
			case self::SEARCH_TYPE:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 `TYPE` = '$class'", self::PUBTIME_ASC);

			case self::SEARCH_TABLE:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$class'", self::PUBTIME_ASC);

			case self::SEARCH_GROUPID:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `GROUPID` = $class", self::PUBTIME_ASC);

			default:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", self::PUBTIME_ASC);
		}
		return [];
	}
}

trait trm_collecting {
     /**
     * 获取全部
     */
    public static function getALL($tablename = NULL, $type = NULL){
        $metainfos = TableRowMetaModel::getALL($tablename, $type);
        $objs = [];
        foreach($metainfos as $meta){
            if($fullprops = self::getExtendedProperties($meta, $meta->ID, $meta->TYPE)){
                $obj = new static;
                $obj->meta = $meta;
                $objs[] = $obj->__put($fullprops, true);
            }
        }
		return $objs;
    }
    
    /**
     * 按条件和附加条件获取列表
     */
    public static function selectRows($tablename, $group_id = NULL, array $notnulls = [], $select = '*', array $orderby = TableRowMetaModel::ID_DESC, $start = 0, $num = 18, $format = Model::LIST_AS_OBJS){
        if(count($notnulls)&&is_string($tablename)){
            $objs = [];
            $querier = TableRowMetaModel::getQuery($tablename, $group_id, TableRowMetaModel::PUBLISHED, $orderby, $start, $num)->switchTable(RDO::multtable([
                'table' =>  DB_YUN.'schema_'.$tablename,
                'field' =>  'ID',
                'as'    =>  'A'
            ], [
                'table' =>  DB_YUN.'tablerowmeta',
                'field' =>  'ID',
                'as'    =>  'B'
            ]));
            foreach ($notnulls as $field) {
                $querier->where($field, '', '<>');
            }
            $result = $querier->select($select);
            if($result){
			    if($format===Model::LIST_AS_ARRS){
                    return array_map(function($modelProperties){
						return array_map('htmlspecialchars_decode', $modelProperties);
					}, $result->getArrayCopy());
                }
                $pdos = $result->getIterator();
                while($pdos&&$rows = $pdos->fetch(PDO::FETCH_ASSOC)){
                    $modelProperties = self::getExtendedAttributes(array_map('htmlspecialchars_decode', $rows ));
                    $obj = new static;
                    $obj->meta = TableRowMetaModel::byGUID($modelProperties['ID']);
                    $objs[] = $obj->__put($modelProperties, true);
                }
            }
            $querier->switchTable(DB_YUN.'tablerowmeta');
            return $objs;
        }
        return [];
    }

    /**
	 * 按条件获取列表
	 */
    public static function getRows($tablename = NULL, $group_id = NULL, $state = TableRowMetaModel::UNRECYCLED, array $orderby = TableRowMetaModel::ID_DESC, $start = 0, $num = 18, $format = Model::LIST_AS_OBJS, array $notnulls = []){
        if(count($notnulls)&&is_string($tablename)){
            return self::selectRows($tablename, $group_id, $notnulls, '*', $orderby, $start, $num, $format);
        }
        $metainfos = TableRowMetaModel::getRows($tablename, $group_id, $state, $orderby, $start, $num, $format);
        return self::buildRowsByMetaInfos($metainfos, $format);
    }

    /**
	 * 获取某标签下的行
	 */
    public static function getRowsByTagName($tag, $class = NULL, array $orderby = TableRowMetaModel::ID_ASC, $start = 0, $num = 18, $format = Model::LIST_AS_OBJS){
		$metainfos = TableRowMetaModel::getRowsByTagName($tag, $class, $orderby, $start = 0, $num = 18, $format);
        return self::buildRowsByMetaInfos($metainfos, $format);
	}
}