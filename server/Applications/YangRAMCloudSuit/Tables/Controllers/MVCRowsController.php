<?php
namespace Cloud\Tables\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;
use Passport;

use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableRowModel;

class MVCRowsController extends \Controller {
    use \Cloud\Tables\Controllers\traits\authorities;
    
    public function getall($tablename = NULL, $type = NULL){
        if($tablename){
            $this->checkAuthority('R', [
                'tables' => $tablename
            ]) or Status::cast('No permissions to read resources.', 1411.2);
        }else{
            Passport::inGroup('Administrators', false) or Status::cast('No permissions to read resources.', 1411.2);
        }
        
        $rows = TableRowModel::getALL($tablename, $type);
        foreach($rows as $i=>$row){
            $rows[$i] = $row->getArrayCopy();
        }
        self::doneResponese($rows);
    }

    public function getrows($tablename = NULL, $folder = NULL, $sortcode = 'id', $length = 0, $start = 0){
        if(!is_numeric($folder)){
            $folder = NULL;
        }
        if($tablename){
            $this->checkAuthority('R', [
                'tables' =>  $tablename
            ]) or Status::cast('No permissions to read resources.', 1411.2);
        }elseif($folder){
            $this->checkAuthority('R', [
                'folders' =>  $folder
            ]) or Status::cast('No permissions to read resources.', 1411.2);
        }else{
            self::doneResponese([]);
        }
        
        $orderby = RowsController::__standardOrderByOptions(['sortby' => $sortcode]);
        $rows = TableRowModel::getRows($tablename, $folder, TableRowMetaModel::PUBLISHED, $orderby, $start, $length, \Model::LIST_AS_ARRS);
        self::doneResponese($rows);
    }

    public function getrow($id){
        $this->checkAuthority('R', [
            'rows' =>  $id
        ]) or Status::cast('No permissions to read resources.', 1411.2);
        if($row = TableRowModel::byGUID($id)){
            self::doneResponese($row->getArrayCopy());
        }
        self::doneResponese([]);
    }
}