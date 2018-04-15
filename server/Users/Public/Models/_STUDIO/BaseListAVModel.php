<?php
namespace PM\_STUDIO;

use Request;
use Tangram\MODEL\ObjectModel;

use Lib\models\DocumentElementModel;
use Lib\models\PageListModel;

abstract class BaseListAVModel extends BaseAdminViewModel {
    protected static
    $listname = NULL,
    // 分类标签
    $__avmtabs = [],
    $__avmtags = [],
    // 单页数量
    $prepage = 20,
    // 排序方式别名映射表
    $__sorts = [],
    // 默认排序方式
    $__sortby = NULL;

    protected static function __viewLimit($options = NULL){
        if(empty($options)){
            $options = Request::instance()->INPUTS->__get;
        }
        if(is_numeric(static::$prepage)){
            $length = static::$prepage;
        }else{
            $length = 0;
        }
        if(isset($options['page'])&&is_numeric($options['page'])&&$options['page']>0){
            $page = intval($options['page']);
            $start = ($page - 1) * $length;
        }else{
            $page = 1;
            $start = 0;
        }
        return [$start, $length, $page];
    }
    
    protected static function __viewOrderBy($options = NULL){
        if(empty($options)){
            $options = Request::instance()->INPUTS->__get;
        }
        if(isset($options['sort'])&&isset(static::$__sorts[$options['sort']])){
            $orderby = static::$__sorts[$options['sort']];
        }elseif(empty(static::$__sortby)){
            $orderby = [['1', false, ObjectModel::SORT_REGULAR]];
        }else{
            $orderby = static::$__sortby;
        }
        return $orderby;
    }

    public static function buildTabs($basedir){
        if(empty(static::$__avmtabs)){
            return '';
        }
        $tabs = new DocumentElementModel('ul');
        $tabs->addClass('class-tabs');
        if(empty(static::$__avmtabswithoutall)){
            if(empty($_GET['tabid'])){
                $li = new DocumentElementModel('li.tab-item', '<i>全部</i>');
            }else{
                $li = new DocumentElementModel('li.tab-item', '<a href="'.$basedir.'" title="全部">全部</a>');
            }
            $tabs->appendElement($li);
        }
        
        foreach(static::$__avmtabs as $alias=>$tab){
            if(isset($_GET['tabid'])&&$_GET['tabid']===$alias){
                $li = new DocumentElementModel('li.tab-item', '<i>'.$tab['name'].'</i>');
            }else{
                $title = empty($tab['title']) ? $tab['name'] : $tab['title'];
                $li = new DocumentElementModel('li.tab-item', '<a href="'.$basedir.'?tabid='.$alias.'" title="'.$title.'">'.$tab['name'].'</a>');
            }
            $tabs->appendElement($li);
        }
        return $tabs->str();
    }

    public static function buildTags($basedir){
        if(empty(static::$__avmtags)){
            return '';
        }
        $tags = new DocumentElementModel('ul');
        $tags->addClass('class-tags');
        foreach(static::$__avmtags as $alias=>$tag){
            if(isset($_GET['tagid'])&&$_GET['tagid']===$alias){
                $li = new DocumentElementModel('li.tag-item', '<i>'.$tag['name'].'</i>');
            }else{
                $title = empty($tag['title']) ? $tag['name'] : $tag['title'];
                $li = new DocumentElementModel('li.tag-item', '<a href="'.$basedir.'?tagid='.$alias.'" title="'.$title.'">'.$tag['name'].'</a>');
            }
            $tags->appendElement($li);
        }
        return $tags->str();
    }

    public static function buildList($inputs){
        $list = new DocumentElementModel('ul');
        $classnames = ['left-item','center-item','right-item'];
        foreach($inputs as $i=>$item){
            $li = new DocumentElementModel('li.list-item.'.$classnames[$i%3]);
            $title = new DocumentElementModel('p.list-title', '<a href="'.$item['url'].'">'.$item['title'].'</a>');
            $li->appendElement($title);

            $desc = new DocumentElementModel('p.list-desc', $item['desc']);
            $li->appendElement($desc);

            if(isset($item['link'])){
                $link = new DocumentElementModel('a.list-link.fa.fa-external-link');
                $link->setAttr('href', $item['link']);
                $link->setAttr('target', '_blank');
                $li->appendElement($link);
            }

            $list->appendElement($li);
        }
        if(1){

        }
        $list->addClass('list-view');
        return $list->str();
    }

    public static function buildPageList($count, $page = NULL, $prepage = NULL){
        $options = Request::instance()->INPUTS->__get;
        if($page === NULL){
            if(isset($options['page'])&&is_numeric($options['page'])){
                $page = intval($options['page']);
            }else{
                $page = 1;
            }
        }
        unset($options['page']);
        if($prepage === NULL){
            $prepage = static::$prepage;
        }
        $pagelist = new PageListModel($page, 9);
        $pagelist->setPageNumberByCount($count, $prepage);
        return '<ul class="page-list">'.$pagelist->str('上一页', '下一页', '首页', '末页', 'page-list-item', 'on', false, $options).'</ul>';
    }
}