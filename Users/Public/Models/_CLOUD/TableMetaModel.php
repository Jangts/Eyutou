<?php
namespace PM\_CLOUD;

use PDO;
use Status;
use Tangram\MODEL\ObjectModel;
use Model;
use Storage;
use DBQ;

use Lib\models\XtdAttrsModel;
use Lib\models\RestraintModel;

/**
 * 
**/
final class TableMetaModel extends \AF\Models\BaseR3Model {
    const
    TYPES = ['album', 'article', 'artwork', 'bill', 'default', 'job', 'news', 'note', 'notice', 'project', 'resume', 'wiki'],

	ID_DESC = [['name', true, self::SORT_REGULAR]],
	ID_ASC = [['name', false, self::SORT_REGULAR]],
    MTIME_DESC = [['SK_MTIME', true, self::SORT_REGULAR]],
	MTIME_ASC = [['SK_MTIME', false, self::SORT_REGULAR]];

    protected static
    $AIKEY = NULL,
    $attributesRestraint = [],
    $fileStoragePath = DPATH.'CLOUDS/'.'tablemeta/',
    $fileStoreLifetime = 0,
    $staticFileStorage,
    $tablenamePrefix = DB_YUN,
    $tablenameAlias = 'tablemeta',
    $uniqueIndexes = ['name'],
	$defaultPorpertyValues  = [
		'name'				=>	'newtable',
        'type'		        =>	'default',
        'item'			    =>	'Item',
        'review'			=>	'0',
        'comments'			=>	'1',
        'fields'			=>	'[]',
        'app_id'		    =>	1,
		'app_data'			=>	'',
		'SK_STATE'		    =>	1
    ];

    private static function rewriteFields($input){
        if(isset($input['fields'])){
            if(is_array($input['fields'])){
                $fields = $input['fields'];
                $input['fields'] = json_encode($input['fields']);
            }else{
                $fields = json_decode($input['fields'], true);
                if(!is_array($fields)){

                    $input['fields'] = '[]';
                    $fields = json_decode($input['fields'], true);
                }
            }
        }else{
            $input['fields'] = '[]';
            $fields = json_decode($input['fields'], true);
        }
        return [$input, $fields];
    }

    public static function getAttributesRestraintgetRowsByTableName($tablename){
        if(isset(self::$attributesRestraint[$tablename])){
            return self::$attributesRestraint[$tablename];
        }
        if($object = self::byGUID($tablename)){
            return $object->getAttributesRestraint();
        }
        return NULL;
    }

    public static function byType($type, array $orderby = self::ID_ASC){
		// 获取默认数据行查询器
        $querier = static::initQuerier();
        $objs = [];
		$result = $querier->requires()->where('type', $type)->take(0)->orderby($orderby)->select();
        if($result){
            $pdos = $result->getIterator();
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static;
                $objs[] = $obj->__put($modelProperties, $modelProperties);
            }
        }
        return $objs;
	}

    public static function all($inUse = true, array $orderby = self::ID_ASC){
        // 获取默认数据行查询器
        $querier = static::initQuerier();
        $objs = [];
		if($inUse){
			$result = $querier->requires()->where('SK_STATE', 1)->take(0)->orderby($orderby)->select();
		}else{
			$result = $querier->requires()->take(0)->orderby($orderby)->select();
		}
        if($result){
            $pdos = $result->getIterator();
            while($pdos&&$modelProperties = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static;
                $objs[] = $obj->__put($modelProperties, true);
            }
        }
        return $objs;
    }

    public function addFolder($name, $parent_id = 0){
        return FolderModel::create($parent_id, $name, $this->name);
    }

    public function delFolder($id){
        if(is_numeric($id)){
            $cat = FolderModel::byGUID($id);
            if($cat->tablename == $this->savedProperties['name']){
               return $cat->destroy();
            }
        }elseif(is_string($id)){
            return FolderModel::delete(['tablename'=>$this->savedProperties['name'], 'name' => $id]);
        }
        return false;
    }

    public function getDefaultMetaPropertyValues(){
        $filename = dirname(__FILE__).'/metapros_defval_providers/'.$this->savedProperties['type'].'.php';
        if(is_file($filename)){
            return include($filename);
        }
        return [];
    }

    public function getDefaultTypePropertyValues(){
        $filename = dirname(__FILE__).'/pros_val_providers/'.$this->savedProperties['type'].'.php';
        if(is_file($filename)){
            return include($filename);
        }
        return [];
    }

    public function getAppData(){
        if($this->modelProperties['app_id']&&$array=self::isArrayJSON($this->modelProperties['app_data'])){
            return $array;
        }
        return NULL;
    }

    public function on(){
        // 获取默认数据行查询器
        $querier = static::initQuerier();
        $this->cache();
        if($this->savedProperties['SK_STATE']!=1){
            if($querier->requires()->where('name', $this->__guid)->update(['SK_STATE' => 1])){
                $this->modelProperties['SK_STATE'] = 1;
                $this->savedProperties['SK_STATE'] = 1;
				# 清空预设列表的缓存，及其所辖内容的缓存
                return true;
            }
            $this->modelProperties['SK_STATE'] = 0;
            return false;
        }
        $this->modelProperties['SK_STATE'] = 1;
        $this->savedProperties['SK_STATE'] = 1;
        return NULL;
    }

    public function off(){
        // 获取默认数据行查询器
        $querier = static::initQuerier();
        $this->cache();
        if($this->savedProperties['SK_STATE']!=0){
            if($querier->requires()->where('name', $this->__guid)->update(['SK_STATE' => 0])){
                $this->modelProperties['SK_STATE'] = 0;
                $this->savedProperties['SK_STATE'] = 0;
                $this->files->store($this->__guid, $this->modelProperties);
				# 清空预设列表的缓存，及其所辖内容的缓存
                return true;
            }
            $this->modelProperties['SK_STATE'] = 1;
            return false;
        }
        $this->modelProperties['SK_STATE'] = 0;
        $this->savedProperties['SK_STATE'] = 0;
        return NULL;
    }

    protected function updateFields($newValue){
        $update = [];
        foreach($newValue as $name=>$field){
            $update[$field['index']] = $field;
        }
        $json = json_encode($update);
        if($this->savedProperties['fields']===$json){
            $this->modelProperties['fields'] = $update;
            return true;
        }
        if($this->querier->requires()->where('name', $this->__guid)->update(['fields' => $json])){
            $this->savedProperties['fields'] = $json;
            $this->modelProperties['fields'] = $update;
            $this->files->store($this->__guid);
        }else{
            Status::cast('Cannot update fields for tablemeta', 1502);
        }
        return $this;
    }

    public function setField($name, array $field){
        $fields = $this->getFiles();
        if(isset($field['type'])&&RestraintModel::typeExsit($field['type'])){
            $field['fieldname'] = $name;
            if(isset($fields[$name])){
                $field['index'] = $fields[$name]['index'];
            }else{
                $field['index'] = count($fields);
            }
            $fields[$name] = $field;
            $this->updateFields($fields);
            return true;
        }
        return false;
    }

    public function removeField($name){
        $fields = $this->getFiles();
        if(isset($fields[$name])){
            unset($fields[$name]);
            $this->updateFields($fields);
            return true;
        }
        return false;
    }

    public function setFiles($input){
        $fields = [];
        foreach($input as $index=>$field){
            if(isset($field['fieldname'])&&isset($field['type'])&&RestraintModel::typeExsit($field['type'])){
                $field['index'] = $i++;
                $fields[$field['fieldname']] = $field;
            }
        }
        return $this->updateFields($fields);
    }

    public function getFiles($select = NULL){
        $fields = [];
        if(is_array($this->modelProperties['fields'])){
            $array = $this->modelProperties['fields'];
        }
        elseif(!preg_match('/^[\s\r\n]*\[[\s\r\n]*\{[\s\S]+\}[\s\r\n]*\][\s\r\n]*$/', $this->modelProperties['fields'])||!($this->modelProperties['fields'] = json_decode($string, true))||!is_array($array)){
            $this->updateFields([]);
            $array = [];
        }
        $i = 0;
        foreach($array as $index=>$field){
            if(isset($field['fieldname'])&&isset($field['type'])){
                if($select&&isset($field[$select])){
                    $fields[$field['fieldname']] = $field[$select];
                }else{
                    $field['index'] = $i++;
                    $fields[$field['fieldname']] = $field;
                }
            }
        }
        return $fields;
    }

    public function getDefaultAttributeValues(){
        return $this->getFiles('default_value');
    }

    public function getAttributesRestraint(){
        if(isset(self::$attributesRestraint[$this->savedProperties['name']])){
            return self::$attributesRestraint[$this->savedProperties['name']];
        }
        if($fields=$this->getFiles()){
            $this->updateFields($fields);
            return self::$attributesRestraint[$this->savedProperties['name']] = new XtdAttrsModel($fields);
        }
        return NULL;
    }

    public function __insert(){
        if(isset($input['name'])){
            // 检查是否已有该名称的表
            // 此处也可用self::name()方法，但是self::byGUID()方法可以读取缓存，速度会更快
			if(self::byGUID($input['name'])){
				return false;
            }
            
            if(empty($this->modelProperties['type'])||!is_array($this->modelProperties['type'], TYPES)){
                return false;
            }
			// 获取数据库链接
            $querier = $this->querier;

            list($input, $fields) = rewriteFields($this->modelProperties);
    
            // 直接将数据插入到数据表
            if(!$querier->insert($input)){
                return false;
            }
    
            $this->modelProperties['fields'] = $fields;
            $this->savedProperties = $this->modelProperties;
            return $this;
		}
		return false;
    }

    public function __update(){
        // 获取数据库链接
        $querier = $this->querier;

        if(empty($this->__guid)){
            return false;
        }
        // 比对更改
        $diff = self::array_diff($this->savedProperties, $this->modelProperties, self::DIFF_SIMPLE);
        $update = $diff['__M__'];
        if(count($update)==0){
            // 如果并无更新，则返回实例，意即成功
            return $this;
        }
        if(empty($this->modelProperties['type'])||is_array($this->modelProperties['type'], TYPES)){
            if(isset($update['fields'])){
                list($update, $fields) = rewriteFields($update);
            }else{
                $fields = $this->savedProperties['fields'];
            }
    
            // 将更新数据提交到数据库
            if($querier->requires()->where('name', $this->__guid)->update($update)){
                $update['fields'] = $fields;
                foreach ($update as $key => $val) {
                    $this->modelProperties[$key] = $val;
                }
                $this->savedProperties = $this->modelProperties;
                $this->files->store($this->__guid);
            }
        }

        return $this;
    }

    public function destroy(){
        #使用事务
        #开启事务
        // 获取默认数据行查询器
        $querier = static::initQuerier();
		$__key = $querier->beginAndLock();
        if($this->savedProperties&&($querier->requires()->where('name', $this->__guid)->delete())){
            $this->cache();
            if(FolderModel::delete("`tablename` = '$this->__guid'")){
                if(TableRowModel::delete("`TABLENAME` = '$this->__guid'")){
                    if(TagModel::delete("`tablename` = '$this->__guid'")){
                        if(TableAuthorityModel::delete("`tablename` = '$this->__guid'")){
                            #提交事务
                            $querier->unlock($__key)->commit();
                            return true;
                        }
                    }
                }
            }
        }
        #回滚事务
		$querier->unlock($__key)->rollBack();
        return false;
    }
    
    private function cache($modelProperties = false){
		$this->files->store($this->modelProperties['name'], $modelProperties);
        return $this;
	}
}