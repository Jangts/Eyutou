<?php
namespace Pages\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use Packages\NIML;

use Pages\Models\OptionsModel;
use Pages\Models\PageModel;

class OptionsController extends \AF\Controllers\BaseResourcesController {
    public function update($id = null, array $opts = []){
        $options = OptionsModel::autoloadItems();
        $diff = OptionsModel::array_diff($options, $_POST);
        if(count($diff['__M__'])){
            foreach($diff['__M__'] as $option=>$value){
                OptionsModel::byGUID($option)->set('option_value', $value)->save();
            }
            self::doneResponese(OptionsModel::autoloadItems(), 1205, 'Update Successed', false);
        }
        self::doneResponese($options, 200, 'No Change', false);
    }
}