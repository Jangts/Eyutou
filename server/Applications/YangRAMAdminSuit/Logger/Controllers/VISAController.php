<?php
namespace Admin\Logger\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

use Passport;
use Tangram\MODEL\UserModel;
use Admin\Logger\Models\AdministratorModel;

class VISAController extends \Controller {
    public function main(){
        // 直接读取用户信息
        // 匹配密码后生成护照
        // 检查是否拥有使用本应用的权限
        // 如果拥有使用权，则签证于护照中
        // 否则，返回签证失败的信息
        if(isset($this->request->INPUTS->__post['username'])&&isset($this->request->INPUTS->__post['password'])&&isset($this->request->INPUTS->__post['pincode'])){
            $user = new UserModel($this->request->INPUTS->__post['username'], UserModel::QUERY_USERNAME);
            if($user->uid){
                if($user->checkPassWord($this->request->INPUTS->__post['password'], true)){
                    $member = AdministratorModel::byGUID(Passport::holder());
                    if($member->checkPinCode($this->request->INPUTS->__post['pincode'])){
                        Passport::checkIn($member->getBaseInfo(), false, '', 'Administrators');
                        if($member->OGROUP==='1'){
                            Passport::checkIn($member->getBaseInfo(), false, 'OPTR', 'System Operators');
                        }
                        exit('{"code":"202","status":"Accepted","msg":"Visaed!","data":{"username":"'.Passport::holdername().'"}}');
                    }
                    exit('{"code":"1411","status":"Visa Failed","msg":"Pin Code Not Match!"}');
                }
                exit('{"code":"1411","status":"Visa Failed","msg":"Password Not Match!"}');
            }
            exit('{"code":"1411","status":"Visa Failed","msg":"Unregister User!"}');
        }
        exit('{"code":"1411","status":"Visa Failed","msg":"Parameters Error!"}');        
    }

    public function login(){
        return $this->main();
    }

    public function logout(){
        if($admininfo = Passport::inGroup('Administrators', false)){
            if($member = AdministratorModel::byGUID(Passport::holder())){
                Passport::checkOut();
            }
        }
        exit('{"code":"203","status":"Non-Authoritative Information","msg":"Logged Out!","data":{}}');
    }
}