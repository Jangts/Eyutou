<?php
namespace Pages\Main\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use Packages\NIML;

use Pages\Main\Models\OptionsModel;
use Pages\Main\Models\PageModel;

class OptionsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;

    public function checkReadAuthority(array $options = []) : bool {
        return true;
    }

    public function update($id = null, array $opts = []){
        $this->checkAuthority('A', $opts) or Status::cast('No permissions to update resource.', 1411.3 );
        $options = OptionsModel::autoloadItems();
        $diff = OptionsModel::array_diff($options, $_POST);
        if(count($diff['__M__'])){
            foreach($diff['__M__'] as $option=>$value){
                OptionsModel::byGUID($option)->set('option_value', $value)->save();
            }
            self::doneResponese(OptionsModel::autoloadItems(), 1203, 'Update Successed', false);
        }
        self::doneResponese($options, 200, 'No Change', false);
    }
}