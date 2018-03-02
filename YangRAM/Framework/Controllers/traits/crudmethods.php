<?php
namespace AF\Controllers\traits;

use Status;
use Response;

trait crudmethods{
    public static
    $model = '',
    // primary key
    $pk = 'id',
    // state key
    $sk;

    protected function create($id = NULL, array $options = []){
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        if(static::$sk){
            if($_GET['state']==='0'){
                $_POST['SK_STATE']=0;
            }else{
                $_POST['SK_STATE']=1;
            }
        }
        if($item=$modelname::post($_POST)){
            \Controller::doneResponese($item->getArrayCopy(), 1201, 'Create Successed', false);
        }
        \Controller::doneResponese([], 1401, 'Create Faild', false);
    }

    protected function update($id, array $options = []){
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
            if($_GET['state']==='0'){
                $item->set(static::$sk, 0);
            }else{
                $item->set(static::$sk, 1);
            }
        }
        if($item->save()){
            \Controller::doneResponese($item->getArrayCopy(), 1205, 'Update Successed', false);
        }
        \Controller::doneResponese([], 1405, 'Update Faild', false);
    }

    protected function delete($id, array $options = []){
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