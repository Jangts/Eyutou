<?php
namespace Pages\Main\Plugins\press\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_PAGES\ColumnModel;

use PM\_CLOUD\TableMetaModel;
use PM\_CLOUD\FolderModel;
use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableRowModel;
use Lib\models\PageListModel;

use Pages\Main\Views\DefaultPageRenderer;
use Pages\Main\Models\OptionsModel;

class NewsPageController extends \Controller {
    public static $prepage = 5;

    public function main($patharr){
        if(isset($patharr[1])){
            if(in_array($patharr[1], ['detail', 'category'])){
                if(isset($patharr[2])){
                    $methodname = 'get'.$patharr[1];
                    $this->$methodname($patharr[2]);
                }
            }else{
                # 404
            }
        }else{
            $methodname = 'getCategory';
            $folder = FolderModel::getDefaultFolder('news');
            $this->$methodname($folder);
        }
        return false;
    }

    private function getCategory($folder){
        if(is_numeric($folder)){
            $folder = FolderModel::byGUID($folder);
        }
        if(is_a($folder, 'PM\_CLOUD\FolderModel')){
            $options = OptionsModel::autoloadItems();
            $column = new ColumnModel('link_news/category/'.$folder->id);
            $column->push('link_news/');
            $count = TableRowMetaModel::getCOUNT(NULL, $folder->id, TableRowMetaModel::PUBLISHED);
            $list = TableRowModel::getRows(NULL, $folder->id, TableRowMetaModel::PUBLISHED, TableRowMetaModel::RLDPD, $start = 0, $num = static::$prepage);

            $renderer = new DefaultPageRenderer();

		    $renderer->assign("title", $folder->name);
		    $renderer->assign($options, "option_");
            $renderer->assign("column", $column);
            $renderer->assign("category", $folder);
            $renderer->assign("list", $list);
            $renderer->assign('pagelist', self::buildPageList($count));
		
            $renderer->using($options['use_theme']);

		    $renderer->display('plugins/news/category.niml');
        }
        # 404
    }

    public static function buildPageList($count, $page = NULL, $prepage = NULL){
        if($page === NULL){
            $options = Request::instance()->INPUTS->__get;
            if(isset($options['page'])){
                $page = $options['page'];
            }else{
                $page = 1;
            }
        }
        if($prepage === NULL){
            $prepage = static::$prepage;
        }

        $pagelist = new PageListModel($page, 9);
        $pagelist->setPageNumberByCount($count, $prepage);
        return $pagelist->str('上一页', '下一页', '首页', '末页', 'page-list-item', 'on', false);
    }

    private function getDetail($id){
        if(is_numeric($id)){
            if($news = TableRowModel::byGUID($id)){
                $options = OptionsModel::autoloadItems();
                $column = new ColumnModel('link_news/detail/'.$id);
                $column->push('link_news/category/'.$news->FOLDER);
                $column->push('link_news/');

                $renderer = new DefaultPageRenderer();

		        $renderer->assign("title", $news->TITLE);
                $renderer->assign($options, "option_");
                $renderer->assign($news->getArrayCopy(), "");
                $renderer->assign("column", $column);
                $renderer->assign("tablemeta", TableMetaModel::byGUID($news->TABLENAME));
                $renderer->assign("category", FolderModel::byGUID($news->FOLDER));
		
                $renderer->using($options['use_theme']);

		        $renderer->display('plugins/news/detail.niml');
            }
        }
        # 404
    }
}