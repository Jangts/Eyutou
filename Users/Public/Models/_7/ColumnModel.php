<?php
namespace PM\_7;

/**
 * @class PM\_7\ColumnModel
 * General Column Model
 * 
 * @author     Jangts
 * @version    5.0.0
**/

final class ColumnModel extends \Model {
    public function __construct($guid, $parents = []){
        $this->modelProperties = [
            'guid' => $guid,
            'parents' => is_array($parents) ? $parents : []
        ];
    }

    public function push($guid){
        $parents = $this->modelProperties['parents'];
        $parents[] = $guid;
        $this->modelProperties['parents'] = array_unique($parents);
    }

    public function match($guid, $true = true, $false = false, $selfonly = false){
        if($guid === $this->modelProperties['guid']){
            return $true;
        }
        if($selfonly){
            return $false;
        }
        if(in_array($guid, $this->modelProperties['parents'])){
            return $true;
        }
        return $false;
    }
}