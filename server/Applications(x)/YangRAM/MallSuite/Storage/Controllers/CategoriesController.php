<?php
namespace Mall\Goods\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

use Mall\Goods\Models\CategoriesModel;

class CategoriesController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    protected static
    $__sorts = [
        'id'            =>  ['id', false, self::SORT_REGULAR],
        'id_reverse'    =>  ['id', true, self::SORT_REGULAR],
        'name'          =>  ['category_name', false, self::SORT_CONVERT_GBK],
        'name_reverse'  =>  ['category_name', true, self::SORT_CONVERT_GBK]
    ],
    $__sortby = ['id', false, self::SORT_REGULAR];

    public function checkReadAuthority(array $options = []) : bool {
        return true;
    }

    public function get($id, array $options = []){
        if($id){
            $category = CategoriesModel::byGUID($id);
            // var_dump($category);
        }else{
            $orderby = self::__standardOrderByOptions($options);
            $range = self::__standardRangeByOptions($options);
            
            if(isset($options['parent'])){
                $categories = CategoriesModel::query("state = 1 AND parent_id = " . intval($options['parent']) , $orderby, $range, CategoriesModel::LIST_AS_ARRS);
            }else{
                $categories = CategoriesModel::query("state = 1" , $orderby, $range, CategoriesModel::LIST_AS_ARRS);
            }
            $categories = CategoriesModel::getRowsByRESTOptions($options, CategoriesModel::LIST_AS_ARRS);
            // var_dump($categories);
        }
    }
}