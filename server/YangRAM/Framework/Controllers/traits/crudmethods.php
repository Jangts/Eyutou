<?php
namespace AF\Controllers\traits;

use Status;
use Response;
use Passport;

trait crudmethods {
    public static
    $model = '',
    // primary key
    $pk = 'id',
    // state key
    $sk = 'SK_STATE';

    public function checkAdminAuthority(array $options = []){
        if(Passport::inGroup('Administrators', false)){
            return true;
        }
        return false;
    }

    public function checkReviewAuthority(array $options = []){
        return $this->checkDeleteAuthority($options);
    }

    /**
     * 检查权限
     * 
     * @access public
     * @param string(1) $type 权限类型，可选值为C/R/U/D/A
     * @param array $options 附加参数
     * @return bool
     */
    public function checkAuthority($type, array $options = []){
        switch($type){
            case 'C':
            return $this->checkCreateAuthority($options) || $this->checkAdminAuthority($options);

            case 'R':
            return $this->checkReadAuthority($options) || $this->checkAdminAuthority($options);

            case 'U':
            return $this->checkUpdateAuthority($options) || $this->checkAdminAuthority($options);

            case 'D':
            return $this->checkDeleteAuthority($options) || $this->checkAdminAuthority($options);

            case 'A':
            return $this->checkAdminAuthority($options);
        }
        return false;
    }

    public function create($id = NULL, array $options = []){
        $this->checkAuthority('C', $options) or Status::cast('No permissions to create resource.', 1411.1);
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        if(static::$sk){
            if($_GET['state']==='1'&&$this->checkReviewAuthority($options)){
                $_POST[static::$sk]=1;
            }else{
                $_POST[static::$sk]=0;
            }
        }
        if($item=$modelname::post($_POST)){
            \Controller::doneResponese($item->getArrayCopy(), 1201, 'Create Successed', false);
        }
        \Controller::doneResponese([], 1401, 'Create Faild', false);
    }

    public function update($id, array $options = []){
        $this->checkAuthority('U', $options) or Status::cast('No permissions to update resource.', 1411.3);
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        if(empty($id)||($item = $modelname::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        $item->put($_POST);
        if(static::$sk){
            if($_GET['state']==='1'&&$this->checkReviewAuthority($options)){
                $item->set(static::$sk, 1);
            }else{
                $item->set(static::$sk, 0);
            }
        }
        if($item->save()){
            \Controller::doneResponese($item->getArrayCopy(), 1203, 'Update Successed', false);
        }
        \Controller::doneResponese([], 1403, 'Update Faild', false);
    }

    public function delete($id, array $options = []){
        $this->checkAuthority('D', $options) or Status::cast('No permissions to update resource.', 1411.4);
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        if(empty($id)||($item = $modelname::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to delete is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if(method_exists($modelname, 'recycle')){
            if($item->recycle()){
                \Controller::doneResponese([], 1204, 'Remove Successed', false);
            }
        }
        elseif(method_exists($modelname, 'remove')){
            $pk = static::$pk;
            if($modelname::remove("`$pk` = '$id'")){
                \Controller::doneResponese([], 1204, 'Remove Successed', false);
            }
        }
        elseif($item->destroy()){
            \Controller::doneResponese([], 1212, 'Delete Successed', false);
        }
        \Controller::doneResponese([], 1412, 'Delete Faild', false);
    }
}