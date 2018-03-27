<?php
namespace PM\_STUDIO\traits;

use Request;
use Lib\models\DocumentElementModel;

trait table {
    public static $columns = [];
    
    public static function __loadTableColumns(){
        return static::$columns;
    }

	public static function buildTable($list, $page = 1, $options = NULL){
        $columns = self::__loadTableColumns();
        $table = new DocumentElementModel('table.tangram-table');
        $tr = new DocumentElementModel('tr');
        foreach($columns as $index=>$column){
            if(empty($column['sorting_name'])){
                $column['sorting_name'] = $column['field_name'];
            }
            if(empty($column['max_length'])){
                $columns[$index]['max_length'] = 30;
            }else{
                $columns[$index]['max_length'] = intval($column['max_length']);
            }
            if(empty(static::$__sorts[$column['sorting_name']])){
                $th = $column['display_name'];
            }else{
                if(empty($options)){
                    $options = Request::instance()->INPUTS->__get;
                }
                if(isset($options['sort'])&&($options['sort']===$column['sorting_name'].'_reverse')){
                    $th = '<a href="?page='.$page.'&sort='.$column['sorting_name'].'">'.$column['display_name'].'↓</a>';
                }elseif(isset($options['sort'])&&($options['sort']===$column['sorting_name'])){
                    $th = '<a href="?page='.$page.'&sort='.$column['sorting_name'].'_reverse">'.$column['display_name'].'↑</a>';
                }else{
                    $th = '<a href="?page='.$page.'&sort='.$column['sorting_name'].'">'.$column['display_name'].'</a>';
                }
            }
            $cell = new DocumentElementModel('th', $th);
            
            $tr->appendElement($cell);
        }
        $table->appendElement($tr);
        foreach($list as $row){
            $tr = new DocumentElementModel('tr');
            foreach($columns as $column){
                if(isset($row[$column['field_name']])){
                    $field = $row[$column['field_name']];
                    $length = $column['max_length'];
                    if(isset($field[2])&&$field[2]==true){
                        $td = '<a href="'.$field[1].'" target="_blank">'.mb_substr($field[0], 0, $length).'</a>';
                    }
                    elseif(isset($field[1])){
                        $td = '<a href="'.$field[1].'">'.mb_substr($field[0], 0, $length).'</a>';
                    }
                    elseif(isset($field[0])){
                        $td = $field[0];
                    }elseif(isset($column['default'])){
                        $td = $column['default'];
                    }
                    else{
                        $td = '';
                    }
                }else{
                    $td = '';
                }
                $cell = new DocumentElementModel('td', $td);
                if(isset($column['classname'])){
                    $cell->addClass($column['classname']);
                }
                $tr->appendElement($cell);
            }
            $table->appendElement($tr);
        }
        if(!empty(static::$creater)){
            $tr = new DocumentElementModel('tr.tangram-creator');
            if(isset(static::$creater['name'])){
                $td = '<a class="block" href="'.static::$creater['url'].'">'.static::$creater['name'].'</a>';
            }else{
                $td = '<a class="block" href="'.static::$creater['url'].'">新建</a>';
            }
            $cell = new DocumentElementModel('td', $td);
            $cell->setAttr('colspan', count($columns));
            $tr->appendElement($cell);
            $table->appendElement($tr);
        }
        $table->addClass('table-view');
        return $table->str();
    }
}