<?php
namespace Pages\Main\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use Pages\Main\Models\PluginModel;

class PluginsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function create($id = NULL, array $options = []){
        // var_dump($_POST);
    }

    public function update($id, array $options = []){
        if($puglin = PluginModel::byGUID($id)){
            if($puglin->put(stripslashes($_POST['options']))&&$puglin->save()){
                \Controller::doneResponese([
                    'appalias'	=>	$puglin->appalias,
                    'options'	=>	$puglin->getOptionsText()
                ], 1203, 'Update Successed', false);
            }
        }
        \Controller::doneResponese([], 1403, 'Update Faild', false);
    }

    public function delete($id, array $options = []){
        // var_dump($_POST);
    }
}