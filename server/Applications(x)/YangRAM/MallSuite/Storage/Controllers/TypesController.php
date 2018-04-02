<?php
namespace Goods\Controllers;

// 引入相关命名空间，以简化书写
use PM\_1008\BrandModel;
use PM\_1008\ProductionTypeModel;

class TypesController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function checkReadAuthority(array $options = []){
        return true;
    }
    
    public function get($id = NULL, $options = []){
        if($id){
            if($type = ProductionTypeModel::byGUID($id)){
                return self::doneResponese($type->getArrayCopy());
            }else{
                $sp = new Status(404);
                $sp->respond(Status::JSON);
            }
        }else{
            $order = self::__standardOrderByOptions($options);
            $range = self::__standardRangeByOptions($options);
            
            if(isset($options['brands'])){
                $types = ProductionTypeModel::getTypesByBrand($options['brands'], $order, $range, ProductionTypeModel::LIST_AS_ARRS);
            }else{
                $types = ProductionTypeModel::query("1 = 1", $order, $range, ProductionTypeModel::LIST_AS_ARRS);
            }
            return self::doneResponese($types);
        }
    }
}