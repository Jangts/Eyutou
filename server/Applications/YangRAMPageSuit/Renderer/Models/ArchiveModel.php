<?php
namespace Pages\Main\Models;

// 引入相关命名空间，以简化书写
use AF\Models\BaseR3Model;

class ArchiveModel extends BaseR3Model {

    protected static
    $rdbConnectionIndex = CACAC,
    $rdbConnectionType = 0,
    $tablenamePrefix = CACAT,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'archives',
    $fileStoragePath = true,
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'                =>  0,
        'archive_name'      =>  '',
        'archive_desc'      =>  '',
        'archive_image'     =>  '',
        'archive_hp'        =>  '',
        'archive_remark'    =>  ''
    ];

    public function getHomepageURL($app){
        if($this->modelProperties['archive_hp']){
            return $this->modelProperties['archive_hp'];
        }
        static $default_page_url;
        if($default_page_url===NULL){
            OptionsModel::__correctTablePrefix($app);
            $default_page_url = OptionsModel::select('option_value', true, "`option_name` = 'default_page_url'")['default_page_url'];
        }
        return $default_page_url.'?archive='.$this->modelProperties['id'];
    }

    public function pages(){
        $pages = PageModel::getPagesByArchive($this->modelProperties['id']);
        foreach ($pages as $page) {
            $page->getRelativeURL();
            $page->col = 'page_'.$page->id;
        }
        // var_dump($pages);
        // exit;
        return $pages;
    }
}