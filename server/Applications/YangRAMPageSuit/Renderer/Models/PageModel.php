<?php
namespace Pages\Main\Models;

// 引入相关命名空间，以简化书写
use AF\Models\BaseR3Model;

class PageModel extends BaseR3Model {
    const
    ID_ASC		    =	[['id', false, PageModel::SORT_REGULAR]],
    ID_DESC	        =	[['id', true, PageModel::SORT_REGULAR]],
    CTIME_ASC	    =	[['crttime', false, PageModel::SORT_REGULAR]],
    CTIME_DESC	    =	[['crttime', true, PageModel::SORT_REGULAR]],
    MTIME_ASC	    =	[['modtime', false, PageModel::SORT_REGULAR]],
    MTIME_DESC	    =	[['modtime', true, PageModel::SORT_REGULAR]],
    PUBTIME_ASC	    =	[['pubtime', false, PageModel::SORT_REGULAR]],
    PUBTIME_DESC	=	[['pubtime', true, PageModel::SORT_REGULAR]];

    protected static
    $rdbConnectionIndex = CACAC,
    $rdbConnectionType = 0,
    $tablenamePrefix = CACAT,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'pages',
    $fileStoragePath = true,
    $fileStoreLifetime = 0,
    $recordType = self::RECORD_TO_MRC,
    $defaultPorpertyValues  = [
        'id'  =>  0,
        'alias'  =>  '',
        'archive'  =>  0,
        'parent'  =>  0,
        'title'  =>  '',
        'crttime'  =>  DATETIME,
        'modtime'  =>  DATETIME,
        'pubtime'  =>  DATETIME,
        'thumb_inlist'  =>   '',
        'description'  =>  '',
        'banner'    =>  '',
        'banner_link'   =>  '',
        'content'  =>  '',
        'template'  =>  '',
        'more'  =>  '',
        'state'  =>  1
    ];

    public static function getPagesByAlias($alias, $parent_id = 0){
        return self::query("state = 1 AND alias = '". $alias . "' AND parent = '". $parent_id . "'" , [['1', false, self::SORT_REGULAR]], 1);
    }

    public static function getPublishedPages($orderby, $range = 0, $selecte = '*'){
        return self::query("state = 1" , $orderby, $range, self::LIST_AS_OBJS, $selecte);
    }

    public static function getPageByAlias($alias, $parent_id = 0){
        $array = self::query("state = 1 AND alias = '". $alias . "' AND parent = '". $parent_id . "'" , [['1', false, self::SORT_REGULAR]], 1);
        if($array&&isset($array[0])){
            return $array[0];
        }
        return NULL;
    }

    private static function privateGetPagePaths($pages, $level, $parent_id = 0, $ignore = NULL, $require = '`state` = 1'){
        if($ignore&&is_numeric($ignore)){
            $_pages = self::query("$require AND `id` <> '". $ignore . "' AND `parent` = '". $parent_id . "'" , [['1', false, self::SORT_REGULAR]], 0, self::LIST_AS_ARRS, 'id, parent, title');
        }else{
            $_pages = self::query("$require AND `parent` = '". $parent_id . "'" , [['1', false, self::SORT_REGULAR]], 0, self::LIST_AS_ARRS, 'id, parent, title');
        }
        foreach($_pages as $page){
            $page['level'] = $level;
            $pages[] = $page;
            $pages = self::privateGetPagePaths($pages, $level + 1, $page['id'], $ignore, $require);
        }
        return $pages;
    }
    
    public static function getPagePaths($ignore = NULL, $require = '`state` = 1'){
        return self::privateGetPagePaths([], 0, 0, $ignore, $require);
    }

    public function getParents($aliasonly = false){
        $parents = [];
        $parent_id = $this->modelProperties['parent'];
        while($parent_id){
            $parentpage = self::byGUID($parent_id);
            if($aliasonly){
                $parents[] = $parentpage->alias;
            }else{
                $parents[] = $parentpage;
            }
            $parent_id = $parentpage->parent;
        }
        return $parents;
    }

    public function getRelativeURL(){
        $dirs = array_reverse($this->getParents(true));
		$dirs[] = $this->alias;
		return implode('/', $dirs);
    }

    public function archive(){
        if($this->modelProperties['archive']){
            return ArchiveModel::byGUID($this->modelProperties['archive']);
        }
        return NULL;
    }

    protected function __afterDelete(){
        self::update('`parent` = '.$this->__guid, ['parent'=>0]);
        return true;
    }
}