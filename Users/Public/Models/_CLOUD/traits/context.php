<?php
namespace PM\_CLOUD\traits;

trait trmm_context {
    public function prev($range = self::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case self::SEARCH_TYPE:
				$array = self::query("`ID` < $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TYPE` = '$this->TYPE'", self::ID_DESC, 1);
				case self::SEARCH_TABLE:
				$array = self::query("`ID` < $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME'", self::ID_DESC, 1);
				break;
				case self::SEARCH_FOLDER:
				$array = self::query("`ID` < $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME' AND `FOLDER` = $this->FOLDER", self::ID_DESC, 1);
				break;
				default:
				$array = self::query("`ID` < $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", self::ID_DESC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function next($range = self::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case self::SEARCH_TYPE:
				$array = self::query("`ID` > $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TYPE` = '$this->TYPE'", self::ID_ASC, 1);
				case self::SEARCH_TABLE:
				$array = self::query("`ID` > $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME'", self::ID_ASC, 1);
				break;
				case self::SEARCH_FOLDER:
				$array = self::query("`ID` > $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME' AND `FOLDER` = $this->FOLDER", self::ID_ASC, 1);
				break;
				default:
				$array = self::query("`ID` > $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", self::ID_ASC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function early($range = self::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case self::SEARCH_TYPE:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TYPE` = '$this->TYPE'", self::PUBTIME_DESC, 1);
				case self::SEARCH_TABLE:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME'", self::PUBTIME_DESC, 1);
				break;
				case self::SEARCH_FOLDER:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME' AND `FOLDER` = $this->FOLDER", self::PUBTIME_DESC, 1);
				break;
				default:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", self::PUBTIME_DESC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function later($range = self::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case self::SEARCH_TYPE:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TYPE` = '$this->TYPE'", self::PUBTIME_ASC, 1);
				case self::SEARCH_TABLE:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME'", self::PUBTIME_ASC, 1);
				break;
				case self::SEARCH_FOLDER:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0 AND `TABLENAME` = '$this->TABLENAME' AND `FOLDER` = $this->FOLDER", self::PUBTIME_ASC, 1);
				break;
				default:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `SK_STATE` = 1 AND `SK_IS_RECYCLED` = 0", self::PUBTIME_ASC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}
}

trait trm_context {
    public function contexts(){
        $meta = new TableRowMetaModel($this->data['ID']);

        return [
            'Previous' => $meta->prev(),
            'Previous_InSameSchema' => $meta->prev(TableRowMetaModel::SEARCH_TYPE),
            'Previous_InSameTable' => $meta->prev(TableRowMetaModel::SEARCH_TABLE),
            'Previous_InSameFolder' => $meta->prev(TableRowMetaModel::SEARCH_FOLDER),

            'Next' => $meta->next(),
            'Next_InSameSchema' => $meta->next(TableRowMetaModel::SEARCH_TYPE),
            'Next_InSameTable' => $meta->next(TableRowMetaModel::SEARCH_TABLE),
            'Next_InSameFolder' => $meta->next(TableRowMetaModel::SEARCH_FOLDER),
        
            'Earlier' => $meta->early(),
            'Earlier_InSameSchema' => $meta->early(TableRowMetaModel::SEARCH_TYPE),
            'Earlier_InSameTable' => $meta->early(TableRowMetaModel::SEARCH_TABLE),
            'Earlier_InSameFolder' => $meta->early(TableRowMetaModel::SEARCH_FOLDER),

            'Later' => $meta->later(),
            'Later_InSameSchema' => $meta->later(TableRowMetaModel::SEARCH_TYPE),
            'Later_InSameTable' => $meta->later(TableRowMetaModel::SEARCH_TABLE),
            'Later_InSameFolder' => $meta->later(TableRowMetaModel::SEARCH_FOLDER),
        ];
    }
}