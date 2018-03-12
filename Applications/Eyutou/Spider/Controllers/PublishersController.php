<?php
namespace Eyutou\Spider\Controllers;

// 引入相关命名空间，以简化书写
use Eyutou\Spider\Models\PublisherModel;

class PublishersController extends \AF\Controllers\BaseResourcesController {
    public function get($code, array $options){
        if($code){
            var_dump($code, PublisherModel::byGUID($code));
        }else{
            var_dump(PublisherModel::getALL());
        }
    }
}