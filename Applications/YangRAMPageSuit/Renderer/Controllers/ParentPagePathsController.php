<?php
namespace Pages\Main\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use Pages\Main\Models\OptionsModel;
use Pages\Main\Models\PageModel;

class ParentPagePathsController extends \AF\Controllers\BaseResourcesController {
    use \AF\Controllers\traits\administration;
    
    public function select($id, array $options = []){
        if(empty($options['pages'])){
            $ignore = NULL;
        }else{
            $ignore = $options['pages'];
        }
        if(empty($options['archives'])){
            $paths = PageModel::getPagePaths($ignore, '`state` = 1');
        }else{
            $paths = PageModel::getPagePaths($ignore, '`state` = 1 AND `archive` = '.$options['archives']);
        }
        self::doneResponese($paths);
    }
}