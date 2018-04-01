<?php
namespace PM\_CLOUD;

use PDO;
use Status;
use Storage;
use App;
use Model;



require_once('TableRowMetaModel.php');

/**
 * @class PM\_CLOUD\TableRowModel
*  Model Of Formatted Data In Special Use
*  专有用途格式化数据模型
*  预设内容，专用内容
*  提供针对专用内容进行增删改查的接口
*/
final class TableRowModel extends BaseCloudItemModel {
    use traits\trm_collecting;
    use traits\trm_checking;
    use traits\trm_context;

    protected static
    $extendedProperties = [],
    $fileStoragePath = DPATH.'CLOUDS/tablerows/rows/',
    $propStoragePath = DPATH.'CLOUDS/tablemeta/',
    $staticQuerier,
    $staticMemorizeStorage = [],
    $staticFileStorage,
    $tablenameAlias = 'tablerowmeta',
    $tables = [];

    private static function getFullDefaultPropertyValues($tablename, $tablemeta = NULL){
        if(isset(self::$staticMemorizeStorage['props'][$tablename])){
            return self::$staticMemorizeStorage['props'][$tablename];
        }
        $storage = new Storage([
            'path'      =>  self::$propStoragePath,
            'filetype'  =>  'props'
        ], Storage::SER, true);
        if($props = $storage->take($tablemeta)){
            self::$staticMemorizeStorage['props'][$tablename] = $props;
            return $props;
        }
        if($tablemeta==NULL){
            $tablemeta = TableMetaModel::byGUID($tablename);
        }
        if(is_a($tablemeta, 'PM\_CLOUD\TableMetaModel')){
			$defaultMetaPorpertyValues = TableRowMetaModel::getDefaultPorpertyValues($tablemeta);
            $defaultTypePropertyValues = self::getDefaultPorpertyValues($tablemeta);
            $attrRestraint = $tablemeta->getAttributesRestraint();
            $props = [$defaultMetaPorpertyValues, $defaultTypePropertyValues, $attrRestraint];
            $storage->store($tablename, $props);
            self::$staticMemorizeStorage['props'][$tablename] = $props;
            return $props;
        }
        return NULL;
    }

    /**
     * 拓展
     */
    private static function getExtendedProperties($meta, $id, $type){
        self::init();
        if($modelProperties = self::$staticFileStorage->take($id)){
            return $modelProperties;
        }
        $result = self::$staticQuerier->using(DB_YUN.'schema_'.$type)->requires()->where('ID', $id)->select();
        if($result&&$rows = $result->item()){
            $rows = array_map('htmlspecialchars_decode', $rows);
            if($props = self::getFullDefaultPropertyValues($meta['TABLENAME'])){
                $attrRestraint = $props[2];
            }else{
                self::$staticQuerier->using(DB_YUN.'tablerowmeta')->requires('ID = '.$meta['ID'])->delete();
                new Status(1443, '', 'Unknow Tablename', true);
            }
            if(is_object($meta)){
                $meta = $meta->getArrayCopy();
            }
            $modelProperties = array_merge($meta, self::getExtendedAttributes($rows, $attrRestraint));
            self::$staticFileStorage->store($id, $modelProperties);
            return $modelProperties;
        }
        self::$staticQuerier->using(DB_YUN.'tablerowmeta')->requires('ID = '.$meta['ID'])->delete();
        return false;
    }

    /**
     * extract extended attributes
     */
    private static function getExtendedAttributes($modelProperties, $attrRestraint){
        if($attrRestraint){
            $defaultValues = $attrRestraint->getDefaultValues();
            if(!empty($modelProperties['X_ATTRS'])&&$modelProperties = json_decode($modelProperties['X_ATTRS'], true)&&(is_array($modelProperties))){
                $attributes = $attrRestraint->correntValues($modelProperties, true);
                $modelProperties['__attributes'] = array_merge($defaultValues, $attributes);
            }
        }
        return $modelProperties;
    }

    private static function buildRowsByMetaInfos($metainfos, $format){
        $objs = [];
        if($format===Model::LIST_AS_ARRS){
            foreach($metainfos as $meta){
                if($fullprops = self::getExtendedProperties($meta, $meta['ID'], $meta['TYPE'])){
                    $objs[] = $fullprops;
                }
            }
        }else{
            foreach($metainfos as $meta){
                if($obj = self::byMETA($meta)){
                    $objs[] = $obj;
                }
            }
        }
		return $objs;
    }
    
    public static function getDefaultPorpertyValues($table, $fullprops = false){
		if(is_string($table)){
			$table = TableMetaModel::byGUID($table);
		}
		if(is_a($table, 'PM\_CLOUD\TableMetaModel')){
            $props = $table->getDefaultTypePropertyValues();
            if($fullprops){
                return array_merge(TableRowMetaModel::getDefaultPorpertyValues($table), $props);
            }
			return $props;
		}
		return NULL;
    }

    /**
     * 统计数量
     */
    public static function getCOUNT($tablename = NULL, $group_id = NULL, $state = TableRowMetaModel::UNRECYCLED) {
		return TableRowMetaModel::getCOUNT($tablename, $group_id, $state);
    }
    
    /**
	 * 统计某标签下的行
	 */
	public static function getCountOfTag($tag, $class = NULL){
		return TableRowMetaModel::getCountOfTag($tag, $class);
    }

    /**
     * 按id获取
     */
    public static function byGUID($id, $published = false){
        if(isset(self::$staticMemorizeStorage[$id])){
            $obj->meta = TableRowMetaModel::byGUID($id);
            return $obj->__put(self::$staticMemorizeStorage[$id], true);
        }
        if(is_numeric($id)){
            $meta = TableRowMetaModel::byGUID($id, $published);
            if($meta){
                return self::byMETA($meta);
            }
            return NULL;
        }else{
            new Status(1460, 'Using Module Error', 'CM\TableRowModel::guid Must be given a numeric.', true);
        }
    }

    /**
     * 按metainfo获取
     */
    public static function byMETA(TableRowMetaModel $meta){
        if($fullprops = self::getExtendedProperties($meta, $meta->ID, $meta->TYPE)){
            $obj = new static;
            $obj->meta = $meta;
            return $obj->__put($fullprops, true);
        }
        return NULL;
    }

    /**
	 * 移动到指定文件夹
	 */
    public static function moveto($require, $cat = 0){
        $metainfos = TableRowMetaModel::moveto($require, $cat);
		$objs = [];
        foreach($metainfos as $meta){
            if($obj = self::byMETA($meta)){
                $objs[] = $obj;
            }
        }
		return $objs;
    }

    public static function create(array $options = []){
        if(isset($options['tablename'])){
            $tablename = $options['tablename'];
        }else{
            new Status(1415, '', 'function PM\_CLOUD\TableRowModel::create must be given a tablename in options.');
        }
        $obj = new static;
        return $obj->__put([
            'TABLENAME' =>  $tablename
        ]);
	}

    public static function post(array $post){
        // 检查数据
        $inserts = self::checkPostData($post);
        self::init();
        #使用事务
        #开启事务
        $__key = self::$staticQuerier->beginAndLock();
        if(self::$staticQuerier->using(DB_YUN.'tablerowmeta')->insert($inserts[0])){
            $inserts[1]["ID"] = self::$staticQuerier->lastInsertId('ID');
            if(self::$staticQuerier->using(DB_YUN.'schema_'.$post['TYPE'])->insert($inserts[1])){
                if(!empty($post["TAGS"])){
                    $tags = explode(',', $post["TAGS"]);
                    $intersect_base["TAGS"] = join(",", $tags);
                    TagModel::__correctTablePrefix(new App('CLOUD'));
                    TagModel::resetTags($tags, $inserts[1]["ID"], $post['TYPE'], $post['TABLENAME']);
                }
                #提交事务
                self::$staticQuerier->unlock($__key)->commit();
                return self::byGUID($inserts[1]["ID"])->clearRelativeCache();
            }
        }
        #回滚事务
        self::$staticQuerier->unlock($__key)->rollBack();
        return false;
    }
    
    /**
	 * 按条件批量移除或隐藏行
	 */
    public static function update($require, $input){
        self::init();
        $__key = self::$staticQuerier->beginAndLock();
		$objs = self::query($require);
		foreach($objs as $obj){
            if(!$obj->put($input)->__update()){
                self::$staticQuerier->unlock($__key)->rollBack();
                return false;
            }
		}
        self::$staticQuerier->unlock($__key)->commit();
		return true;
    }
    
    /**
	 * 按条件批量移除或隐藏行
	 */
    public static function remove($require, $recycleType = TableRowMetaModel::RECYCLE){
        if($metainfos = TableRowMetaModel::remove($require, $recycleType)){
            $objs = [];
            foreach($metainfos as $meta){
                if($obj = self::byMETA($meta)){
                    $objs[] = $obj;
                }
            }
		    return $objs;
        }
		return false;
	}

    /**
     * 按条件删除
     */
    public static function delete($require){
        self::init();
        $__key = self::$staticQuerier->beginAndLock();
		$objs = self::query($require);
		foreach($objs as $obj){
            if(!$obj->destroy()){
                self::$staticQuerier->unlock($__key)->rollBack();
                return false;
            }
		}
        self::$staticQuerier->unlock($__key)->commit();
		return true;
    }

    protected $meta;

    /**
     * 对象构造函数
     */
    private function __construct(){
        self::init();
    }
    
    /**
     * 数据生成函数
     */
    protected function __put(array $input, $isSaved = false){
        if($props = self::getFullDefaultPropertyValues($input['TABLENAME'])){
            $defaults = array_merge($props[0], $props[1]);
            if($props[2]){
                $defaults['__attributes']  = $props[2]->getDefaultValues();
            }
            $this->modelProperties = self::correctArrayByTemplate($input, $defaults);
            if($isSaved){
                $this->savedProperties = $this->modelProperties;
                if(isset($this->savedProperties['ID'])){
                    $this->__guid = $this->savedProperties['ID'];
                    self::$staticMemorizeStorage[$this->__guid] = $input;
                }
            }
        }else{
            new Status(1443, '', 'Unknow Tablename', true);
        }
        $this->xml = NULL;
        return $this;
    }

    /**
     * 重置属性
     */
    public function put(array $input){
        $modelProperties = self::correctArrayByTemplate($input, $this->savedProperties);
        return $this->__put($modelProperties);
    }

    /**
     * 保存行信息的修改
     */
	public function __update(){
        if(empty($this->savedProperties['ID'])){
            return false;
        }
        $querier = self::$staticQuerier;
        // 开启事件，并锁定
        $__key = $querier->beginAndLock();
        $meta = $this->meta;
        // 检查更新数据
        $updates = self::checkPutData($this->modelProperties, $this->savedProperties);

        if($meta->put($updates[0])->save()){
            foreach ($meta as $key => $val) {
                $this->savedProperties[$key] = $val;
            }

            unset($this->modelProperties['ID']);
            $diff = self::array_diff($this->savedProperties, $updates[1],  self::DIFF_SIMPLE);
            $update = $diff['__M__'];

            $this->modelProperties['ID'] = $this->savedProperties['ID'];
            if(count($update)==0){
                $querier->unlock($__key)->commit();
                return $this;
            }

            $result = self::$staticQuerier->using(DB_YUN.'schema_'.$this->modelProperties['TYPE'])->requires()->where('ID', $this->savedProperties['ID'])->update($update);
            if($result!==false){
                foreach ($update as $key => $val) {
                    $this->savedProperties[$key] = $val;
                }
                $this->modelProperties = $this->savedProperties;
                $querier->unlock($__key)->commit();
                return $this;
            }
        }
        $querier->unlock($__key)->rollBack();
        return false;
    }
    
    /**
	 * 移除或隐藏行
	 */
	public function recycle($recycleType = self::RECYCLE){
        if($this->savedProperties){
            return $this->meta->recycle($recycleType);
        }
        return false;
    }

    /**
     * 删除行
     */
    public function destroy() {
        if($this->savedProperties){
            #使用事务
            $__key = self::$staticQuerier->beginAndLock();
            if(self::$staticQuerier->using(DB_YUN.'tablerowmeta')->requires('ID = '.$this->savedProperties['ID'])->delete()!==false){
                if(self::$staticQuerier->using(DB_YUN.'schema_'.$this->modelProperties['TYPE'])->requires('ID = '.$this->savedProperties['ID'])->delete()!==false){
                    // TagModel::__correctTablePrefix(new App('CLOUD'));
                    if(TagModel::delete('type = \''.$this->modelProperties['TYPE'].'\' AND item = '.$this->savedProperties['ID'])){
                        $this->meta->clearRelativeCache();
                        self::$staticQuerier->unlock($__key)->commit();
                        return true;
                    }
                }
            }
            self::$staticQuerier->unlock($__key)->rollBack();
			return false;
		}
    }
    
    /**
     * 清空相关缓存
     */
    public function clearRelativeCache(){
        if($this->savedProperties){
            $this->meta->clearRelativeCache();
        }
        return $this;
    }
}
        