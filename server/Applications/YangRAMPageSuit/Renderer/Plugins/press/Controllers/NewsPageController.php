<?php
namespace Pages\Main\Plugins\press\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_PAGES\ColumnModel;

use PM\_CLOUD\TableMetaModel;
use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableRowModel;
use Lib\models\PageListModel;

use Pages\Main\Models\FrontPageViewModel;
use Pages\Main\Models\OptionsModel;

class NewsPageController extends \Controller {
    public static $prepage = 5;

    public function main($patharr){
        if(isset($patharr[1])){
            if(in_array($patharr[1], ['detail', 'archive'])){
                if(isset($patharr[2])){
                    $methodname = 'get'.$patharr[1];
                    $this->$methodname($patharr[2]);
                }
            }else{
                # 404
            }
        }else{
            $methodname = 'getArchive';
            $archive = TRGroupModel::getDefaultGroup('news');
            $this->$methodname($archive);
        }
        return false;
    }

    private function getArchive($archive){
        if(is_numeric($archive)){
            $archive = TRGroupModel::byGUID($archive);
        }
        if(is_a($archive, 'PM\_CLOUD\TRGroupModel')){
            $request = Request::instance()->INPUTS->__get;
            if(isset($request['page'])&&is_numeric($request['page'])){
                $page = intval($request['page']);
            }else{
                $page = 1;
            }
            $start = ($page - 1) * static::$prepage;

            $options = OptionsModel::autoloadItems();
            $column = new ColumnModel('link_news/archive/'.$archive->id);
            $column->push('link_news/');
            $count = TableRowMetaModel::getCOUNT(NULL, $archive->id, TableRowMetaModel::PUBLISHED);
            $list = TableRowModel::getRows(NULL, $archive->id, TableRowMetaModel::PUBLISHED, TableRowMetaModel::GLDPD, $start, $num = static::$prepage);

            $renderer = new FrontPageViewModel();

		    $renderer->assign("title", $archive->name);
		    $renderer->assign($options, "option_");
            $renderer->assign("column", $column);
            $renderer->assign("archive", $archive);
            $renderer->assign("list", $list);
            $renderer->assign('pagelist', self::buildPageList($count, $page, static::$prepage));
		
            $renderer->using($options['use_theme']);

		    $renderer->display('plugins/news/archive.niml');
        }
        # 404
    }

    public static function buildPageList($count, $page = NULL, $prepage = NULL){
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
                $column->push('link_news/archive/'.$news->GROUPID);
                $column->push('link_news/');

                $renderer = new FrontPageViewModel();

		        $renderer->assign("title", $news->TITLE);
                $renderer->assign($options, "option_");
                $renderer->assign($news->getArrayCopy(), "");
                $renderer->assign("column", $column);
                $renderer->assign("tablemeta", TableMetaModel::byGUID($news->TABLENAME));
                if($news->GROUPID){
                    $renderer->assign("archive", TRGroupModel::byGUID($news->GROUPID));
                }else{
                    $renderer->assign("archive", TRGroupModel::create(['未分类', 'news', 0]));
                }
		
                $renderer->using($options['use_theme']);

		        $renderer->display('plugins/news/detail.niml');
            }
        }
        # 404
    }
}