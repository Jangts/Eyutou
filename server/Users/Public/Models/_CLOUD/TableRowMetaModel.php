<?php
namespace PM\_CLOUD;

use PDO;
use Status;
use Storage;
use Model;

require_once('traits/collecting.php');
require_once('traits/checking.php');
require_once('traits/context.php');

/**
 * @class PM\_CLOUD\TableRowMetaModel
 * Special Use Content Light Model
 * 专用内容轻模型
 * 是一个忽略不同预设字段差异的精简的单一标准模型
**/
final class TableRowMetaModel extends BaseCloudItemModel {
	use traits\trmm_collecting;
	use traits\trmm_checking;
	use traits\trmm_context;

	const
	SEARCH_ALL = 0,
	SEARCH_TYPE = 1,
	SEARCH_TABLE = 2,
	SEARCH_FOLDER = 3,

	ALL = 0,
	RECYCLED = 1,
	UNRECYCLED = 2,
	PUBLISHED = 3,
	UNPUBLISHED = 4,
	POSTED = 5,

	UNRECYCLE = 0,
	RECYCLE = 1,
	HIDE = 2,

	ID_DESC = [['ID', true, self::SORT_REGULAR]],
	ID_ASC = [['ID', false, self::SORT_REGULAR]],
	CTIME_DESC = [['ID', true, self::SORT_REGULAR]],
	CTIME_ASC = [['ID', false, self::SORT_REGULAR]],
	PUBTIME_DESC = [['PUBTIME', true, self::SORT_REGULAR]],
	PUBTIME_ASC = [['PUBTIME', false, self::SORT_REGULAR]],
	MTIME_DESC = [['SK_MTIME', true, self::SORT_REGULAR]],
	MTIME_ASC = [['SK_MTIME', false, self::SORT_REGULAR]],
	LEVEL_DESC = [['LEVEL', true, self::SORT_REGULAR]],
	LEVEL_ASC = [['LEVEL', false, self::SORT_REGULAR]],
	TITLE_DESC = [['TITLE', true, self::SORT_REGULAR]],
	TITLE_ASC = [['TITLE', false, self::SORT_REGULAR]],
	TITLE_DESC_GBK = [['TITLE', true, self::SORT_CONVERT_GBK]],
	TITLE_ASC_GBK = [['TITLE', false, self::SORT_CONVERT_GBK]],

	LDCD = [['LEVEL', true, self::SORT_REGULAR], ['ID', true, self::SORT_REGULAR]],
	LDCA = [['LEVEL', true, self::SORT_REGULAR], ['ID', false, self::SORT_REGULAR]],
	LACD = [['LEVEL', false, self::SORT_REGULAR], ['ID', true, self::SORT_REGULAR]],
	LACA = [['LEVEL', false, self::SORT_REGULAR], ['ID', false, self::SORT_REGULAR]],

	LDPD = [['LEVEL', true, self::SORT_REGULAR], ['PUBTIME', true, self::SORT_REGULAR]],
	LDPA = [['LEVEL', true, self::SORT_REGULAR], ['PUBTIME', false, self::SORT_REGULAR]],
	LAPD = [['LEVEL', false, self::SORT_REGULAR], ['PUBTIME', true, self::SORT_REGULAR]],
	LAPA = [['LEVEL', false, self::SORT_REGULAR], ['PUBTIME', false, self::SORT_REGULAR]],

	LDTD = [['LEVEL', true, self::SORT_REGULAR], ['TITLE', true, self::SORT_REGULAR]],
	LDTA = [['LEVEL', true, self::SORT_REGULAR], ['TITLE', false, self::SORT_REGULAR]],
	LATD = [['LEVEL', false, self::SORT_REGULAR], ['TITLE', true, self::SORT_REGULAR]],
	LATA = [['LEVEL', false, self::SORT_REGULAR], ['TITLE', false, self::SORT_REGULAR]],

	LDTD_GBK = [['LEVEL', true, self::SORT_REGULAR], ['TITLE', true, self::SORT_CONVERT_GBK]],
	LDTA_GBK = [['LEVEL', true, self::SORT_REGULAR], ['TITLE', false, self::SORT_CONVERT_GBK]],
	LATD_GBK = [['LEVEL', false, self::SORT_REGULAR], ['TITLE', true, self::SORT_CONVERT_GBK]],
	LATA_GBK = [['LEVEL', false, self::SORT_REGULAR], ['TITLE', false, self::SORT_CONVERT_GBK]],

	RLD = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR]],
	RLA = [['RANK', false, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR]],

	RLDCD = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['ID', true, self::SORT_REGULAR]],
	RLDCA = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['ID', false, self::SORT_REGULAR]],
	RLACD = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['ID', true, self::SORT_REGULAR]],
	RLACA = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['ID', false, self::SORT_REGULAR]],

	RLDPD = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['PUBTIME', true, self::SORT_REGULAR]],
	RLDPA = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['PUBTIME', false, self::SORT_REGULAR]],
	RLAPD = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['PUBTIME', true, self::SORT_REGULAR]],
	RLAPA = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['PUBTIME', false, self::SORT_REGULAR]],

	RLDTD = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['TITLE', true, self::SORT_REGULAR]],
	RLDTA = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['TITLE', false, self::SORT_REGULAR]],
	RLATD = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['TITLE', true, self::SORT_REGULAR]],
	RLATA = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['TITLE', false, self::SORT_REGULAR]],

	RLDTD_GBK = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['TITLE', true, self::SORT_CONVERT_GBK]],
	RLDTA_GBK = [['RANK', true, self::SORT_REGULAR], ['LEVEL', true, self::SORT_REGULAR], ['TITLE', false, self::SORT_CONVERT_GBK]],
	RLATD_GBK = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['TITLE', true, self::SORT_CONVERT_GBK]],
	RLATA_GBK = [['RANK', false, self::SORT_REGULAR], ['LEVEL', false, self::SORT_REGULAR], ['TITLE', false, self::SORT_CONVERT_GBK]],

	SORTKEYS = ['ID', 'PUBTIME', 'SK_MTIME', 'TITLE'];
	
	protected static
	$fileStoragePath = DPATH.'CLOUDS/tablerows/meta/',
	$fullStoragePath = DPATH.'CLOUDS/tablerows/rows/',
	$listStoragePath = DPATH.'CLOUDS/tablerows/list/',
	$listFileStorage,
	$staticQuerier,
    $staticMemorizeStorage = [],
    $staticFileStorage,
	$tablenameAlias = 'tablerowmeta',
	$defaultPorpertyValues  = [
		'ID'				=>	NULL,
		'TYPE'				=>	'default',
		'TABLENAME'			=>	'',
		'FOLDER'			=>	0,
		'TITLE'				=>	'',
		'DESCRIPTION'		=>	'',
		'PUBTIME'			=>	DATETIME,
		'RANK'              =>  5,
		'LEVEL'				=>	0,
		'SK_COMMENTS'		=>	1,
		'SK_CTIME'          =>  DATETIME,
		'SK_MTIME'			=>	DATETIME,
		'SK_STATE'			=>	1,
		'SK_IS_RECYCLED'	=>	0
	],
	$constraints  = [
		'TABLENAME'			=>	'a',
		'FOLDER'			=>	1,
		'TITLE'				=>	'a'
	];

	protected static function init(){
		if(!self::$staticFileStorage){
			self::$staticMemorizeStorage = [
				'items'	=>	[],
				'props'	=>	[]
			];
			self::$staticFileStorage = new Storage(static::$fileStoragePath, Storage::JSN, true);
			self::$listFileStorage = new Storage(static::$listStoragePath, Storage::JSN, true);
		}
		self::initQuerier();
	}
	
	protected static function formatPorpertyValuesForUse($props){
        return $props;
    }

    protected static function formatPorpertyValuesToSave($props){
        return $props;
	}

	public static function getDefaultPorpertyValues($table){
		if(is_string($table)){
			$table = TableMetaModel::byGUID($table);
		}
		if(is_a($table, 'PM\_CLOUD\TableMetaModel')){
			$props = $table->getDefaultMetaPropertyValues();
			return array_merge(self::$defaultPorpertyValues, $props, [
				'TYPE'				=>	$table->type,
				'TABLENAME'			=>	$table->name
			]);
		}

		return NULL;
	}

	public static function __checkValues($values, $isPost = false){
		if($isPost){
			if(empty($values['TABLENAME'])||!($table = TableMetaModel::byGUID($values['TABLENAME']))){
				Status::cast(1418);
			}
			$defaultPorpertyValues = self::getDefaultPorpertyValues($table);
		}else{
			$defaultPorpertyValues = self::$defaultPorpertyValues;
		}
        foreach($values as $name=>$value){
            if(!self::__checkValue($name, $value)){
                if(isset($defaultPorpertyValues[$name])&&self::__checkValue($name, $defaultPorpertyValues[$name])){
                    $values[$name] = $defaultPorpertyValues[$name];
                }else{
                    if($isPost){
                        self::throwValueError($name, $value);
                    }
                    unset($values[$name]);
                }
            }
        }
        return $values;
    }

	/**
	 * 获取预处理后的DBQ实例
	 */
	public static function getQuery($tablename = NULL, $folder = NULL, $state = self::UNRECYCLED, array $orderby = self::ID_DESC, $start = 0, $num = 18){
		self::init();
		$querier = self::$staticQuerier->requires();
		if(is_string($tablename)||(is_numeric($folder)&&$folder!=0)){
			if(is_string($tablename)){
				$querier->where('TABLENAME', $tablename);
			}
			if(is_numeric($folder)){
				$querier->where('FOLDER', $folder);
			}
			switch($state){
				case self::RECYCLED:
				$querier->where('SK_IS_RECYCLED', 1);
				break;
				case self::UNRECYCLED:
				$querier->where('SK_IS_RECYCLED', 0);
				break;
				case self::PUBLISHED:
				$querier->where('SK_IS_RECYCLED', 0);
				$querier->where('SK_STATE', 1);
				break;
				case self::UNPUBLISHED:
				$querier->where('SK_IS_RECYCLED', 0);
				$querier->where('SK_STATE', 0);
				break;
				case self::POSTED:
				$querier->where('SK_IS_RECYCLED', 0);
				$querier->where('SK_STATE', -1);
				break;
			}
			foreach ($orderby as $order) {
				if(isset($order[0])&&isset($order[1])){
					$querier->orderby((string)$order[0], !!$order[1]);
				}
			}
			return $querier->take($num, $start);
		}
		return NULL;
	}

	/**
	 * 统计数量
	 */
	public static function getCOUNT($tablename = NULL, $folder = NULL, $state = self::UNRECYCLED) {
		if($querier = self::getQuery($tablename, $folder, $state, self::ID_DESC, 0, 0)){
			return $querier->count();
		}else{
			return 0;
		}
	}

	/**
	 * 统计某标签下的行
	 */
	public static function getCountOfTag($tag, $class = NULL){
		if(is_string($tag)){
			if(is_numeric($class)&&$class!='0'){
				$folder = FolderModel::byGUID($class);
				$tablename = $folder->tablename;
			}elseif(is_string($class)){
				$tablename = $class;
			}
			return count(TagModel::byTablename($tablename));
		}
		return 0;
	}

	/**
	 * 查询单例
	 */
	public static function byGUID($id, $published = false){
		self::init();
		$obj = new self;
		if(isset(self::$staticMemorizeStorage['items'][$id])){
			$obj->__put(self::$staticMemorizeStorage['items'][$id], true);
		}elseif($modelProperties = self::$staticFileStorage->take($id)){
			self::$staticMemorizeStorage['items'][$id] = $modelProperties;
			$obj->__put($modelProperties, true);
		}else{
			$result = self::$staticQuerier->requires()->where('ID', $id)->take(1)->select();
			if($result&&$modelProperties = $result->item()){
				self::$staticFileStorage->store($id, $modelProperties);
				self::$staticMemorizeStorage['items'][$id] = $modelProperties;
				$obj->__put($modelProperties, true);
			}
		}
		if($obj->ID&&($published==false||($obj->SK_STATE==1&&$obj->SK_IS_RECYCLED==0))){
			return $obj;
		}
		return NULL;
	}

	/**
	 * 移动到指定文件夹
	 */
	public static function moveto($require, $folder = 0){
		self::init();
		$__key = self::$staticQuerier->beginAndLock();
        $objs = self::query($require);
		foreach($objs as $obj){
			$obj->FOLDER = $folder;
			if(!$obj->save()){
				self::$staticQuerier->unlock($__key)->rollBack();
                return false;
			}
		}
		self::$staticQuerier->unlock($__key)->commit();
		return $objs;
	}

	/**
	 * 按条件批量移除或隐藏行
	 */
	public static function remove($require, $recycleType = self::RECYCLE){
		self::init();
		$__key = self::$staticQuerier->beginAndLock();
		$objs = self::query($require);
		foreach($objs as $obj){
            if(!$obj->recycle($recycleType)){
				self::$staticQuerier->unlock($__key)->rollBack();
                return false;
			}
		}
		self::$staticQuerier->unlock($__key)->commit();
		return $objs;
	}

	/**
	 * 构造方法
	 * 不同于其他模型思维的构造函数
	 */
	protected function __construct(array $savedProperties = []){
		self::init();
		if(empty($savedProperties)){
			$this->modelProperties = self::$defaultPorpertyValues;
		}else{
			$this->__put($savedProperties, true);
		}
	}

	protected function __put(array $input, $isSaved = false){
		if($isSaved){
			$this->__guid = $input['ID'];
			$this->savedProperties = $this->modelProperties = $input;
		}elseif(is_array($input)){
			$this->modelProperties = self::correctArrayByTemplate($input, static::$defaultPorpertyValues, $this->modelProperties, false);
		}
	}

	public function put(array $input){
        $this->xml = NULL;
        $this->__put($input);
        return $this;
	}
	
	public function correctFolder(){
		if($this->modelProperties['FOLDER']<6){
			if($this->savedProperties['FOLDER']>5){
				$this->modelProperties['FOLDER'] = $this->savedProperties['FOLDER'];
			}else{
				$this->modelProperties['FOLDER'] = 0;
			}
		}
		if($folder = FolderModel::byGUID($this->modelProperties['FOLDER'])){
			if($folder->type===$this->TYPE){
				if($folder->SK_IS_RECYCLED){
					// 检查是否应该隐藏
					$this->modelProperties['SK_IS_RECYCLED'] = self::HIDE;
				}
			}else{
				$this->modelProperties['FOLDER'] = 0;
			}
		}else{
			$this->modelProperties['FOLDER'] = 0;
		}
		return $this->modelProperties['FOLDER'];
	}

	/**
	 * 保存行信息
	 */
	public function save(){
		// 此类只管理已存在的行，新建行请使用完整版
        if($this->savedProperties){
			// 校正文件夹
			$this->correctFolder();

			if(empty($this->modelProperties['ID'])){
                return false;
			}

			// 移除不可改键
			unset($this->modelProperties['ID']);
			unset($this->modelProperties['TYPE']);
			unset($this->modelProperties['TABLENAME']);

			$querier = self::$staticQuerier;
			$this->modelProperties = self::checkPutData($this->modelProperties);
            $diff = self::array_diff($this->savedProperties, $this->modelProperties, self::DIFF_SIMPLE);
			$update = $diff['__M__'];

			$this->modelProperties['ID'] = $this->savedProperties['ID'];
			$this->modelProperties['TYPE'] = $this->savedProperties['TYPE'];
			$this->modelProperties['TABLENAME'] = $this->savedProperties['TABLENAME'];
            if(count($update)==0){
                return true;
			}
			
            if($querier->requires()->where('ID', $this->savedProperties['ID'])->update($update)){
				foreach ($update as $key => $val) {
					$this->savedProperties[$key] = $val;
				}
				$this->modelProperties = $this->savedProperties;
            }else{
                return false;
            }
		}
		
        return $this->clearRelativeCache();
	}

	/**
	 * 拓展为完整的行模型
	 */
	public function getExtendedModel(){
		return TableRowModel::byMETA($this);
	}
	
	/**
	 * 获取分类信息
	 */
	public function getFolderInfo(){
		if($this->FOLDER){
			return FolderModel::byGUID($this->FOLDER);
		}
		return NULL;
	}

	/**
	 * 获取表信息
	 */
	public function getTableInfo(){
		if($this->modelProperties['TABLENAME']){
			return TableMetaModel::byGUID($this->modelProperties['TABLENAME']);
		}
		return NULL;
	}

	/**
	 * 获取属性约束
	 */
	public function getAttributesRestraint(){
		return $this->getTableInfo()->getAttributesRestraint();
	}

	/**
	 * 切换状态
	 */
	public function toggleState($state){
		$$state = intval(!!$state);
		$this->modelProperties['SK_STATE'] = $state;
		if($this->savedProperties){
			return $this->save();
		}
		return $this;
	}

	/**
	 * 记录浏览
	 */
	public function record(){
		return 0;
	}

	/**
	 * 移除或隐藏行
	 */
	public function recycle($recycleType = self::RECYCLE){
		$recycleType = in_array($recycleType, [0, 1, 2]) ? $recycleType : 1;
		$this->modelProperties['SK_IS_RECYCLED'] =	$recycleType;
		if($this->savedProperties){
			return $this->save();
		}
		return $this;
	}
	/**
	 * 删除行
	 */
	public function destroy(){
		if($obj = $this->getExtendedModel()){
			return $obj->destroy();
		}
		return false;
	}

	/**
     * 清空相关缓存
     */
    public function clearRelativeCache(){
		// 清空相关缓存
		self::$staticFileStorage->store($this->__guid);
		(new Storage(self::$fullStoragePath, Storage::JSN, true))->store($this->__guid);
		foreach(self::SORTKEYS as $sort){
			self::$listFileStorage->setNameSpace($this->modelProperties['TABLENAME'].'/')->store($sort);
			if($this->modelProperties['FOLDER']){
				// 判断是否为0
				self::$listFileStorage->setNameSpace($this->modelProperties['FOLDER'].'/')->store($sort);
			}
		}
        return $this;
    }
}