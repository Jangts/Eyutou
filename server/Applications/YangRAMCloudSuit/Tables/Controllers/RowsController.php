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
    use \Cloud\Tables\Controllers\traits\authorities;

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

        'ldcd'  =>  TableRowMetaModel::LDCD,
        'ldca'  =>  TableRowMetaModel::LDCA,
        'lacd'  =>  TableRowMetaModel::LACD,
        'laca'  =>  TableRowMetaModel::LACA,
        'ldpd'  =>  TableRowMetaModel::LDPD,
        'ldpa'  =>  TableRowMetaModel::LDPA,
        'lapd'  =>  TableRowMetaModel::LAPD,
        'lapa'  =>  TableRowMetaModel::LAPA,
        'ldtd'  =>  TableRowMetaModel::LDTD,
        'ldta'  =>  TableRowMetaModel::LDTA,
        'latd'  =>  TableRowMetaModel::LATD,
        'lata'  =>  TableRowMetaModel::LATA,

        'ldtd_pinyin'  =>  TableRowMetaModel::LDTD_GBK,
        'ldta_pinyin'  =>  TableRowMetaModel::LDTA_GBK,
        'latd_pinyin'  =>  TableRowMetaModel::LATD_GBK,
        'lata_pinyin'  =>  TableRowMetaModel::LATA_GBK
    ],
    $__sortby = TableRowMetaModel::PUBTIME_DESC;

    public function get($id, array $options = []){
        $this->checkAuthority('R', $options) or Status::cast('No permissions to read resources.', 1411.2);
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
        $this->checkAuthority('C', $options) or Status::cast('No permissions to create resource.', 1411.1);
        if($this->request->FORM->state==='1'&&$this->checkReviewAuthority($options)){
            $_POST['SK_STATE']=1;
        }else{
            $_POST['SK_STATE']=0;
        }
        if($row=TableRowModel::post($_POST)){
            self::doneResponese($row->getArrayCopy(), 1201, 'Create Successed', false);
        }
        self::doneResponese([], 1401, 'Create Faild', false);
    }

    public function put($id, array $options = []){
        $this->checkAuthority('U', $options) or Status::cast('No permissions to update resource.', 1411.3);
        if(empty($id)||($row = TableRowModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        $row->put($_POST);
        if($this->request->FORM->state==='1'&&$this->checkReviewAuthority($options)){
            $row->set('SK_STATE', 1);
        }else{
            $row->set('SK_STATE', 0);
        }
        if($row->save()){
            self::doneResponese($row->getArrayCopy(), 1203, 'Update Successed', false);
        }
        self::doneResponese([], 1403, 'Update Faild', false);
    }

    public function delete($id, array $options = []){
        $this->checkAuthority('D', $options) or Status::cast('No permissions to update resource.', 1411.4);
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