<?php
namespace Goods\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_1008\ProductionModel;

class ProductionsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public static 
    $__sorts = [
		'id'				=>	ProductionModel::ID_ASC,
		'id_reverse'		=>	ProductionModel::ID_DESC,
		'ctime'				=>	ProductionModel::CTIME_ASC,
		'ctime_reverse'		=>	ProductionModel::CTIME_DESC,
		'ptime'				=>	ProductionModel::TIME_ASC,
		'ptime_reverse'		=>	ProductionModel::TIME_DESC,
		'rank'				=>	ProductionModel::RANK_ASC,
		'rank_reverse'		=>	ProductionModel::RANK_DESC,
		'name'				=>	ProductionModel::NAME_ASC,
		'name_reverse'		=>	ProductionModel::NAME_DESC,
		'name_gb'			=>	ProductionModel::NAME_ASC_GBK,
		'name_gb_reverse'	=>	ProductionModel::NAME_DESC_GBK
	],
	$__sortby = ProductionModel::ID_DESC,
    $__picks = [
        'pickall'  =>  ProductionModel::ALL,
	    'onsale'  =>  ProductionModel::ONSALE,
	    'instorage'  =>  ProductionModel::INSTORAGE,
    ];

    public function checkReadAuthority(array $options = []){
        return true;
    }

    public function checkCreateAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }

    public function checkUpdateAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }

    public function checkDeleteAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }

    public function get($id = NULL, $options = []){
        if($id){
            if($pro = ProductionModel::byGUID($id)){
                return self::doneResponese($pro->getArrayCopy());
            }else{
                $sp = new Status(404);
                $sp->respond(Status::JSON);
            }
        }else{
            if(isset($options['types'])){
                $type = $options['types'];
            }else{
                $type = NULL;
            }
            if(isset($options['brands'])){
                $brand = $options['brands'];
            }else{
                $brand = NULL;
            }
            $orderby = self::__standardOrderByOptions($options);
            $range = self::__standardRangeByOptions($options);
            if(isset($options['pick'])&&isset(self::$__picks[$options['pick']])){
                $state = self::$__picks[$options['pick']];
            }else{
                $state = ProductionModel::ALL;
            }
            if(isset($options['categories'])){
                $category = $options['categories'];
            }else{
                $category = NULL;
            }
            $pros = ProductionModel::getRows($type, $brand, $state, $orderby, $range, $category, true, ProductionModel::LIST_AS_ARRS);
            return self::doneResponese($pros);
        }
    }

    public function post($id = NULL, array $options = []){
        if(!empty($id)){
            return $this->put($id, $options);
        }
        if($_GET['state']==='0'){
            $_POST['state']='0';
            $_POST['time_onsale']=NULL;
        }else{
            $_POST['state']='1';
            if($_POST['time_onsale']==NULL){
                $_POST['time_onsale']=DATETIME;
            }
        }
        if($pro=ProductionModel::post($_POST)){
            self::doneResponese($pro->getArrayCopy(), 1201, 'Create Successed', false);
        }
        self::doneResponese([], 1401, 'Create Faild', false);
    }

    public function put($id, array $options = []){
        if(empty($id)||($pro = ProductionModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if($_GET['state']==='0'){
            $_POST['state']='0';
            $_POST['time_onsale']=NULL;
        }else{
            $_POST['state']='1';
            if($_POST['time_onsale']==NULL){
                $_POST['time_onsale']=DATETIME;
            }
        }
        $pro->put($_POST);
        if($pro->save()){
            self::doneResponese($pro->getArrayCopy(), 1203, 'Update Successed', false);
        }
        self::failResponese(1403, 'Update Faild', false);
    }

    public function delete($id, array $options = []){
        if(empty($id)||($pro = ProductionModel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'Table row you want to update is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if($pro->destroy()){
            self::doneResponese([], 1212, 'Delete Successed', false);
        }
        self::failResponese(1403, 'Delete Faild', false);
    }
}