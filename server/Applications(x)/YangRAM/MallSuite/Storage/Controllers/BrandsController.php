<?php
namespace Goods\Controllers;

// 引入相关命名空间，以简化书写
use PM\_1008\CategoryModel;
use PM\_1008\BrandModel;

class BrandsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function checkReadAuthority(array $options = []){
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
                $category_id = $options['categories'];
                if($category = CategoryModel::byGUID($category_id)){
                    // 查询类目的所有子类目
                    // 需要包含自己，所传进去的容器中，包含自己
                    $categories_ids = $category->getOffspringIds(true);
                    $types = BrandModel::query("`category_id` IN ('".implode("','", $categories_ids)."')", $order, $range, \Model::LIST_AS_ARRS);
                }else{
                    $types = [];
                }
            }else{
                $types = BrandModel::query("1 = 1", $order, $range, \Model::LIST_AS_ARRS);
            }
            return self::doneResponese($types);
        }
    }
}