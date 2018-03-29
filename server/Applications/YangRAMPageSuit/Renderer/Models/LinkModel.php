<?php
namespace Pages\Main\Models;

// 引入相关命名空间，以简化书写
use AF\Models\BaseDeepModel;

class LinkModel extends BaseDeepModel {

    protected static
    $rdbConnectionIndex = CACAC,
    $rdbConnectionType = 0,
    $tablenamePrefix = CACAT,
    $tablenamePrefixRewritable = true,
    $tablenameAlias = 'links',
    $__parentFieldName = 'parent_id',
    $fileStoragePath = true,
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'            =>  0,
        'menu'          =>  0,
        'parent_id'     =>  0,
        'name'          =>  '',
        'type'          =>  '',
        'value'         =>  '',
        'alt'           =>  '',
        'sort'          =>  0,
        'state'         =>  1
    ];

    public static function getLinksByMenu($menu_id){
        return self::query("state = 1 AND menu = " . $menu_id , [['sort', false, self::SORT_REGULAR]], $range = 0, self::LIST_AS_OBJS);
    }

    public function getLinkURL($frontdir = NULL){
        if($frontdir===NULL){
            OptionsModel::__correctTablePrefix($this->app);
            $options = OptionsModel::autoloadItems();
            $frontdir = $options['default_page_url'];
        }
		switch($this->type){
            case 'page':
            if($page = PageModel::byGUID($this->value)){
                $dirs = array_reverse($page->getParents(true));
                $dirs[] = $page->alias;
            }else{
                $dirs = [];
            }
            return $frontdir.implode('/', $dirs);
            

            case 'achv':
            if($achv = ArchiveModel::byGUID($this->value)){
                if($achv->archive_hp){
                    return $achv->archive_hp;
                }
            }
            return $frontdir.'?archive='.$this->value;

            case 'link':
            return $frontdir.$this->value;

            case 'http':
            return $this->value;
        }
        return false;
    }

    protected function __checkInsertData(array $post){
        $post = self::correctArrayByTemplate($post, static::$defaultPorpertyValues);
        // var_dump($post);
        
        switch($post['type']){
            case 'page':
            if(PageModel::byGUID($post['value'])==false){
                return NULL;
            }

            case 'achv':
            if(ArchiveModel::byGUID($post['value'])==false){
                return NULL;
            }

            case 'link':
            case 'http':
            break;
            
            default:
            return NULL;
        }

        if(empty($post['parent_id'])||self::byGUID($post['parent_id'])){
            return $post;
        }
        return NULL;
    }

    protected function __checkUpdateData(array $update, array $savedProperties){
        $update = array_intersect_key($update, $savedProperties);
        if(isset($update['type'])){
            $value = isset($update['value']) ? $update['value'] : $savedProperties['value'];
            switch($update['type']){
                case 'page':
                if(PageModel::byGUID($value)==false){
                    return NULL;
                }
    
                case 'achv':
                if(ArchiveModel::byGUID($value)==false){
                    return NULL;
                }
    
                case 'link':
                case 'http':
                break;
                
                default:
                return NULL;
            }
        }
        if(empty($update['parent_id'])||self::byGUID($update['parent_id'])){
            return $update;
        }
        return NULL;
    }
}