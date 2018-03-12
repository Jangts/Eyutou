<?php
namespace Admin\Logger\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

use Admin\Logger\Models\AdministratorModel;

class AdministratorsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;
    
    public function put($id, array $options = []){
        if(empty($id)||($obj = AdministratorModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if(empty($_POST['PIN'])){
            $obj->put($_POST);
            if($obj->save()){
                self::doneResponese($obj->getArrayCopy(), 1203, 'Update Successed', false);
            }
        }
        elseif(strlen($_POST['PIN'])===6){
            if(isset($_POST['OLDPIN'])&&$obj->checkPinCode($_POST['OLDPIN'])){
                $obj->put($_POST);
                if($obj->save()){
                    self::doneResponese($obj->getArrayCopy(), 1203, 'Update Successed', false);
                }
            }
        }
        self::doneResponese([], 1403, 'Update Faild', false);
    }
}