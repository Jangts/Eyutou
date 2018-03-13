<?php
namespace Pages\Main\Plugins\goods\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_1008\BrandModel;

class BrandsController extends \Controller {
    public function read($id, array $options){
        if($id){
            return BrandModel::byGUID($id);
        }
        return BrandModel::query($options);
    }
}