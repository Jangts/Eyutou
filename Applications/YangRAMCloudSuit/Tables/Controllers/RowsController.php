<?php
namespace Cloud\Tables\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableRowModel;

class RowsController extends \AF\Controllers\BaseResourcesController {
    public static
    $__sorts = [
        'id_reverse'  =>  TableRowMetaModel::ID_DESC,
	    'id'  =>  TableRowMetaModel::ID_ASC,
	    'ctime_reverse'  =>  TableRowMetaModel::CTIME_DESC,
	    'ctime'  =>  TableRowMetaModel::CTIME_ASC,
	    'pubtime_reverse'  =>  TableRowMetaModel::PUBTIME_DESC,
	    'pubtime'  =>  TableRowMetaModel::PUBTIME_ASC,
	    'mtime_reverse'  =>  TableRowMetaModel::MTIME_DESC,
	    'mtime'  =>  TableRowMetaModel::MTIME_ASC,
	    'level_reverse'  =>  TableRowMetaModel::LEVEL_DESC,
	    'level'  =>  TableRowMetaModel::LEVEL_ASC,
	    'title_reverse'  =>  TableRowMetaModel::TITLE_DESC,
	    'title'  =>  TableRowMetaModel::TITLE_ASC,
	    'title_pinyin_reverse'  =>  TableRowMetaModel::TITLE_DESC_GBK,
        'title_pinyin'  =>  TableRowMetaModel::TITLE_ASC_GBK,
    ],
    $__sortby = TableRowMetaModel::PUBTIME_DESC;

    public function get($id, array $options = []){
        if(empty($id)){
            $rows = TableRowModel::getALL();
            foreach($rows as $i=>$row){
                $rows[$i] = $row->getArrayCopy();
            }
            self::doneResponese($rows, 200, 'Request Successed', false);
        }
        $row = TableRowModel::byGUID($id);
        self::doneResponese($row->getArrayCopy(), 200, 'Request Successed', false);
    }

    public function post($id = NULL, array $options = []){
        if(!empty($id)){
            return $this->put($id, $options);
        }
        if($this->request->FORM->state==='0'){
            $_POST['SK_STATE']=0;
        }else{
            $_POST['SK_STATE']=1;
        }
        if($row=TableRowModel::post($_POST)){
            self::doneResponese($row->getArrayCopy(), 1201, 'Create Successed', false);
        }
        self::doneResponese([], 1401, 'Create Faild', false);
    }

    public function put($id, array $options = []){
        if(empty($id)||($row = TableRowModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        $row->put($_POST);
        if($this->request->FORM->state==='0'){
            $row->set('SK_STATE', 0);
        }else{
            $row->set('SK_STATE', 1);
        }
        if($row->save()){
            self::doneResponese($row->getArrayCopy(), 1205, 'Update Successed', false);
        }
        self::doneResponese([], 1405, 'Update Faild', false);
    }

    public function delete($id, array $options = []){
        if(empty($id)||($row = TableRowModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if($row->recycle()){
            self::doneResponese([], 1204, 'Remove Successed', false);
        }
        self::doneResponese([], 1404, 'Remove Faild', false);
    }
}