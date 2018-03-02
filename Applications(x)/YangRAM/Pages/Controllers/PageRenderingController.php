<?php
namespace Pages\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Response;
use App;

use PM\_7\ColumnModel;

use Pages\Views\DefaultPageRenderer;
use Pages\Models\OptionsModel;
use Pages\Models\PageModel;
use Pages\Models\MenuModel;

class PageRenderingController extends \Controller {
    public function renderDefaultPage(){
        $options = OptionsModel::autoloadItems();
        $column = new ColumnModel('page_default');

        $renderer = new DefaultPageRenderer();

		$renderer->assign("title", $options['default_page_title']);
		$renderer->assign($options, "option_");
		$renderer->assign("column", $column);
		
        $renderer->using($options['use_theme']);

		$renderer->display($options['default_page_template']);
    }

    public function renderPageByAlias($patharr){
        if(empty($patharr)){
            return $this->renderDefaultPage();
        }
        $options = OptionsModel::autoloadItems();
        $renderer = new DefaultPageRenderer();
        $renderer->using($options['use_theme']);

        $patharr = array_reverse($patharr);
        $index = count($patharr);
        $parent = 0;
        $pages = [];

        while($index){
            $index--;
            if($page = PageModel::getPageByAlias($patharr[$index], $parent)){
                $pages[] = $page;
                $parent = $page->id;
            }else{
                new Status(404, true);
                // var_dump($patharr);
                exit('Page Not Found');
            }
        }
        
        $count = count($pages);
        $page = $pages[$count-1];
        $parents = $page->getParents();
        $column = new ColumnModel('page_'.$page->id);
        foreach($parents as $parent){
            $column->push('page_'.$parent->id);
        }
        if($archive = $page->archive()){
            $column->push('achv_'.$archive->id);
            $renderer->assign("archive_url", $archive->archive_hp);
        }else{
            $renderer->assign("archive_url", '');
        }

        $renderer->assign("page", $page);
        $renderer->assign("page_url", $this->request->URI->src);
        $renderer->assign("title", $options['default_page_title']);
		$renderer->assign($options, "option_");
        $renderer->assign("column", $column);
        
        while($count){
            $count--;
            if($pages[$count]->template){
                $renderer->display($pages[$count]->template);
            }
        }
        exit('No Template');
    }
}