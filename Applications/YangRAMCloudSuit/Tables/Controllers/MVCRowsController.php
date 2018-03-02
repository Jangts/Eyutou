<?php
namespace Cloud\Tables\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableRowModel;

class MVCRowsController extends \Controller {
    public function getall($type = NULL){
        $trs = TableRowModel::getALL();
        foreach($trs as $i=>$tr){
            $trs[$i] = $tr->getArrayCopy();
        }
        self::doneResponese($trs);
    }

    public function getrows($tablename = NULL, $folder = NULL, $sortcode = 'id', $length = 0, $start = 0){
        if(!is_numeric($folder)){
            $folder = NULL;
        }
        $orderby = RowsController::__standardOrderByOptions(['sortby' => $sortcode]);
        $trs = TableRowModel::getRows($tablename, $folder, TableRowMetaModel::PUBLISHED, $orderby, $start, $length, \Model::LIST_AS_ARRS);
        self::doneResponese($trs);
    }

    public function getrow($id){
        $tr = TableRowModel::byGUID($id);
        var_dump($tr);

        exit('{"code":"200","status":"OK","msg":"Welcome to use Standard (Like) API of YangRAM!"}');
    }
}