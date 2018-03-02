<?php
namespace Pages\Views;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_7\ColumnModel;

use Packages\NIML;

use Pages\Models\PageModel;
use Pages\Models\MenuModel;

class DefaultPageRenderer extends NIML {
    protected $_pages = [];
    /**
     * 自定义方法
     */
    public function showPageProp($pagealisas, $prop, $length = 0, $start = 0){
        if(isset($_pages[$pagealisas])){
            $page = $_pages[$pagealisas];
        }elseif($page = PageModel::getPageByAlias($pagealisas, 0)){
            $_pages[$pagealisas] = $page;
        }
        if($page&&$page->$prop){
            if($length==0){
                $length = NULL;
            }
            echo mb_substr($page->$prop, $start, $length);
        }
    }

    public function menu($id = 0){
        $menu = new MenuModel($id);
        return $menu->getArrayCopy();
    }

    public function checkcurrent($guid, ColumnModel $column, $true = 'current', $false = '', $selfonly = false){
        if(is_array($guid)&&isset($guid['type'])&&isset($guid['value'])){
            $guid = $guid['type'] . '_' . $guid['value'];
        }
        if(!is_string($guid)){
            echo $false;
        }else{
            if($column->match($guid, true, false, $selfonly)){
                echo $true;
            }else{
                echo $false;
            }
        }
    }
}