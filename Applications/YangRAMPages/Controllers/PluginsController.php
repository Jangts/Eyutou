<?php
namespace Pages\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use Pages\Models\PluginModel;

class PluginsController extends \AF\Controllers\BaseResourcesController {
    public function create($id = NULL, array $options = []){
        // var_dump($_POST);
    }

    public function update($id, array $options = []){
        if($puglin = PluginModel::byGUID($id)){
            if($puglin->put($_POST['options'])->save()){
                \Controller::doneResponese([
                    'appalias'	=>	$puglin->appalias,
                    'options'	=>	$puglin->getOptionsText()
                ], 1205, 'Update Successed', false);
            }
        }
        \Controller::doneResponese([], 1405, 'Update Faild', false);
    }

    public function delete($id, array $options = []){
        // var_dump($_POST);
    }
}