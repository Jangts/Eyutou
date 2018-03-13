<?php
namespace Pages\Main\Models;

// 引入相关命名空间，以简化书写
use Request;
use Tangram\MODEL\ObjectModel;

class MenuModel extends ObjectModel {

    public static function extendsPageLink($link){
        if($page = PageModel::byGUID($link->value)){
            $item = $link->getArrayCopy();
            // $item['parents'] = $page->getParents();
            // $item['archive'] = $page->archive();
            $parents = $page->getParents();
            $dir = '';
            foreach($parents as $parent){
                $dir = $parent->alias.'/'.$dir;
            }
            $item['url'] = Request::instance()->ARI->dirname.$dir.$page->alias.'/';
            return $item;
        }
        return false;
    }

    public static function extendsAchvLink($link){
        if($archive = ArchiveModel::byGUID($link->value)){
            $item = $link->getArrayCopy();
            $item['url'] = $archive->archive_hp;
            return $item;
        }
    }

    public static function extendsLinkLink($link){
        $item = $link->getArrayCopy();
        $item['url'] = Request::instance()->ARI->dirname.$link->value;
        return $item;
    }

    public static function extendsHttpLink($link){
        $item = $link->getArrayCopy();
        $item['url'] = $link->value;
        return $item;
    }

    public function __construct($id){
        $modelProperties = [];
        $links = LinkModel::getLinksByMenu($id);
        foreach($links as $link){
            if(in_array($link->type, ['page', 'achv', 'link', 'http'])){
                $methodname = 'extends'.$link->type.'link';
                if($item = self::$methodname($link)){
                    $item['guid'] = $item['type'] . '_' . $item['value'];
                    $modelProperties[] = $item;
                }
            }
        }
        $this->modelProperties= $modelProperties;
    }
}