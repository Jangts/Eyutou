<?php
namespace Pages\Designer\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use Pages\Designer\Models\TemplateModel;

class TemplatesController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function create($id = NULL, array $options = []){
        // var_dump($_POST);
    }

    public function update($id, array $options = []){
       if(stripos(strtolower($_SERVER['CONTENT_TYPE']), 'text/x-niml-template')!==false){
            $content = file_get_contents('php://input');
            if($template = TemplateModel::byGUID($id)){
                if($template->put($content)&&$template->save()){
                    \Controller::doneResponese([
                        'content'	=>	$template->content
                    ], 1203, 'Update Successed', false);
                }
            }
            var_dump($id, $template);
            exit;
            \Controller::doneResponese([], 1403, 'Update Faild', false);
        }
        new Status(400, true);
    }

    public function delete($id, array $options = []){
        // var_dump($_POST);
    }
}