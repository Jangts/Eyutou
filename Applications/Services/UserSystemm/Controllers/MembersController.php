<?php
namespace Services\Users\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

use Services\Users\Models\MemberModel;

class MembersController extends \AF\Controllers\BaseResourcesController {
    public function put($id, array $options = []){
        if(empty($id)||($obj = MemberModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if(empty($_POST['password'])){
            $obj->put($_POST);
            if($obj->save()){
                self::doneResponese($obj->getArrayCopy(), 1205, 'Update Successed', false);
            }
        }elseif(strlen($_POST['password'])>=8){
            if(isset($_POST['oldpw'])){
                if($obj->getUserAccountCopy()->checkPassWord($_POST['oldpw'], false)){
                    $obj->put($_POST);
                    if($obj->save()){
                        self::doneResponese($obj->getArrayCopy(), 1205, 'Update Successed', false);
                    }
                }
            }
        }
        self::doneResponese([], 1405, 'Update Faild', false);
    }
}