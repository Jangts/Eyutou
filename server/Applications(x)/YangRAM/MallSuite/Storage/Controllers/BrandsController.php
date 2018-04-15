<?php
namespace Goods\Controllers;

// 引入相关命名空间，以简化书写
use PM\_1008\CategoryModel;
use PM\_1008\BrandModel;

class BrandsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function checkReadAuthority(array $options = []) : bool {
        return true;
    }
    
    public function get($id = NULL, $options = []){
        if($id){
            if($type = BrandModel::byGUID($id)){
                return self::doneResponese($type->getArrayCopy());
            }else{
                $sp = new Status(404);
                $sp->respond(Status::JSON);
            }
        }else{
            $order = self::__standardOrderByOptions($options);
            $range = self::__standardRangeByOptions($options);
            
            if(isset($options['categories'])){
                $types = BrandModel::getBrandsByCategory($options['categories'], $order, $range, BrandModel::LIST_AS_ARRS, true);
            }else{
                $types = BrandModel::query("1 = 1", $order, $range, BrandModel::LIST_AS_ARRS);
            }
            return self::doneResponese($types);
        }
    }
}