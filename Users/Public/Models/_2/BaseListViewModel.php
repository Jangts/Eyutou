<?php
namespace PM\_2;

use Request;
use Tangram\MODEL\ObjectModel;

use Lib\models\DocumentElementModel;
use Lib\models\PageListModel;

abstract class BaseListViewModel extends BaseAdminViewModel {
    protected static
    // 单页数量
    $prepage = 10,
    // 排序方式别名映射表
    $__sorts = [],
    // 默认排序方式
    $__sortby = NULL;

    protected static function __viewLimit($options = NULL){
        if(empty($options)){
            $options = Request::instance()->FORM->__get;
        }
        // if(empty($options['prepage'])){
            if(is_numeric(static::$prepage)){
                $length = static::$prepage;
            }else{
                $length = 0;
            }
        // }else{
        //     $length = $options['prepage'];
        // }
        if(isset($options['page'])){
            $page = $options['page'];
            $start = ($page - 1) * $length;
        }else{
            $page = 1;
            $start = 0;
        }
        return [$start, $length, $page];
    }
    
    protected static function __viewOrderBy($options = NULL){
        if(empty($options)){
            $options = Request::instance()->FORM->__get;
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
        if($page === NULL){
            $options = Request::instance()->FORM->__get;
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
        return '<ul class="page-list">'.$pagelist->str('上一页', '下一页', '首页', '末页', 'page-list-item', 'on', false).'</ul>';
    }
}