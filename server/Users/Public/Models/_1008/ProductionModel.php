<?php
namespace PM\_1008;

// 引入相关命名空间，以简化书写
use App;
use Model;
use AF\Models\BaseR3Model;

class ProductionModel extends Model {
    use \AF\Models\traits\querying;

    const
	ALL =       0,
	ONSALE =    ['state'       =>  1],
	INSTORAGE = ['state'    =>  0],

	ID_DESC = [['id', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	ID_ASC = [['id', false, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	CTIME_DESC = [['id', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
    CTIME_ASC = [['id', false, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
    BRAND_DESC = [['brand_id', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
    BRAND_ASC = [['brand_id', false, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
    TYPE_DESC = [['type_id', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	TYPE_ASC = [['type_id', false, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	TIME_DESC = [['time_onsale', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
    TIME_ASC = [['time_onsale', false, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
    RANK_DESC = [['rank', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	RANK_ASC = [['rank', false, self::SORT_REGULAR], ['sort_lv', false, self::SORT_REGULAR]],
	NAME_DESC = [['name', true, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	NAME_ASC = [['name', false, self::SORT_REGULAR], ['sort_lv', true, self::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, self::SORT_CONVERT_GBK], ['sort_lv', true, self::SORT_REGULAR]],
    NAME_ASC_GBK = [['name', false, self::SORT_CONVERT_GBK], ['sort_lv', true, self::SORT_REGULAR]];

    protected static
    $querier,
    $DBTPrefix,
    $fileStoragePath = RUNPATH_CA.'GOODS/productions/',
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'  =>  0,
        'code'  =>  '',
        'name'  =>  '',
        'description'  =>  '',
        'category_id'  =>  0,
        'brand_id'  =>  0,
        'type_id'  =>  0,
        'image'  =>  '',
        'standard'  =>  '',
        'description'  =>  '',
        'time_onsale'   =>  DATETIME,
        'attributes'  =>  '[]',
        'link'  =>  '',
        'detail'  =>  '',
        'rank'      =>  5,
        'sort_lv'      =>  0,
        'state'  =>  1
    ];

    final protected static function initQuerier($type = 'productions'){
        if(!self::$querier){
            $app = new App(1008);
            self::$DBTPrefix = $app->DBTPrefix;
            self::$querier = new \DBQ($app->CONN);
        }
        return self::$querier->using(self::$DBTPrefix.$type);
    }

    /**
	 * 统计记录行数
	 * 
	 * @access public
	 * @static
     * @param string|array $require                     查询条件，可以为整理好的SQL语句片段，也可以是数组形式的条件组
	 * @return int
	**/
    public static function getCOUNT($require = "1 = 1") {
		$querier = static::initQuerier();
		$querier->requires($require)->orderby(false)->take(0);
		return $querier->count();
    }

    public static function getALL(){
        // 获取默认数据行查询器
        $querier = self::initQuerier();
        $querier->requires(1)->take(0)->orderby(false);
        return self::returnRows($querier->select(), self::LIST_AS_OBJS);
    }

    /**
     * 获取产品列表
     * 
     * @access public
     * @param int|null      $type_id            类型ID        
     * @param int|null      $brand_id           品牌ID
     * @param int           $state              产品状态，其实一个SQL附加条件，只不过作为状态时提供了三个常量可选
     * @param array         $orderby            排序规则
     * @param array|int     $range              选取范围[start, length]
     * @param int|null      $category_id        类目ID
     * @param bool          $getsubcategories   包含子类目
     * @param int           $returnFormat       返回格式
     */
    public static function getRows($type = NULL, $brand = NULL, $state = self::ALL, array $orderby = [['1', false, self::SORT_REGULAR]], $range = 0, $category_id = NULL, $getsubcategories = false, $returnFormat = self::LIST_AS_OBJS){
        // 获取默认数据行查询器
        $querier = self::initQuerier();
        // 初始化查询条件
        if($state){
            $querier->requires($state);
        }else{
            $querier->requires();
        }

        if(is_numeric($category_id)){
            if($getsubcategories){
                # 此处调用查询子类的方法
                $category_id = [$category_id];
                $querier->where('category_id', $category_ids);
            }else{
                $querier->where('category_id', $category_id);
            }
        }
        

        if(is_numeric($type)){
            $querier->where('type_id', $type);
        }

        if(is_numeric($brand)){
            $querier->where('brand_id', $brand);
        }elseif(is_string($brand)){
            $brands = BrandModel::byName($brand, $category_id);
            $querier->where('brand_id', $brands);
        }

        // 整理并录入截取范围到查询器
        static::setQuerySelectRange($querier, $range);
        
        // 依次整理并录入排序规则到查询器
        $querier->orderby(false);
        foreach ($orderby as $order) {
            static::setQuerySelectOrder($querier, $order);
        }

        // 返回查询结果
        return self::returnRows($querier->select(), $returnFormat);
    }

    public static function query($require = "1 = 1", array $orderby = [['1', false, self::SORT_REGULAR]], $range = 0, $returnFormat = self::LIST_AS_OBJS, $selecte = '*'){
        // 获取默认数据行查询器
        $querier = self::initQuerier();
        
        // 查询数据库
        // 整理查询条件
		if(is_numeric($require)){
            $range = $require;
            $require = "1";
        }elseif(is_string($require)||is_array($require)){
            $require = $require;
        }else{
            $require = "1";
        }

        // 整理并录入截取范围到查询器
        static::setQuerySelectRange($querier->requires($require), $range);
        
        // 依次整理并录入排序规则到查询器
        $querier->orderby(false);
        foreach ($orderby as $order) {
            static::setQuerySelectOrder($querier, $order);
        }

        // 返回查询结果
        return self::returnRows($querier->select($selecte), $returnFormat);
    }

    protected static function returnRows($result, $returnFormat){
        // 准备一个空数组，用来存放查询结果
        $objs = [];
        if($result){
            $pdos = $result->getIterator();
            while($pdos&&$modelProperties = $pdos->fetch(\PDO::FETCH_ASSOC)){
                self::getFileStorage()->store($modelProperties['id'], $modelProperties = self::rewriteAttrs($modelProperties));
                if($returnFormat === self::LIST_AS_ARRS){
                    $obj = $modelProperties;
                }else{
                    $obj = new static($modelProperties['id']);
                }
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    private static function rewriteAttrs($modelProperties){
        // 数组化自定义属性
        if($modelProperties['attributes']){
            $modelProperties['__attributes'] = json_decode($modelProperties['attributes'], true) || [];
        }

        // 检查并拓展类目公用属性（包含类目的上级类目）
        if($attrRestraint = ProductionAttrValueModel::getAttributesRestraintByCategoryId($modelProperties['category_id'])){
            $category_attr_values = ProductionAttrValueModel::getProductionAttrs($modelProperties['id']);
            $modelProperties = array_merge($modelProperties, $attrRestraint->correntValues($category_attr_values, true));
        }
        if($category = CategoryModel::byGUID($modelProperties['category_id'])){
            $modelProperties['category'] = $category->getArrayCopy();
        }
        if($brand = BrandModel::byGUID($modelProperties['brand_id'])){
            $modelProperties['brand'] = $brand->getArrayCopy();
        }
        if($type = ProductionTypeModel::byGUID($modelProperties['type_id'])){
            $modelProperties['type'] = $type->getArrayCopy();
        }
        return $modelProperties;
    }

    public static function byGUID($id){
        if($modelProperties = self::getFileStorage()->take($id)){
            return new static($modelProperties['id']);
        }
        $result = self::initQuerier()->requires(null)->where('state', 1)->where('id', $id)->select();
        if($result&&$modelProperties = $result->item()){
            self::getFileStorage()->store($modelProperties['id'], self::rewriteAttrs($modelProperties));
            return new static($modelProperties['id']);
        }
        return false;
    }

    

    public static function create(array $options = ['category_id' => 0]){
        if(isset($options['category_id'])){
            $category_id = $options['category_id'];
        }else{
            $category_id = 0;
        }
        $obj = new self(0);
        if($category_id&&($category = CategoryModel::byGUID($category_id))){
            if($attrRestraint = ProductionAttrValueModel::getAttributesRestraintByCategoryId($category_id)){
                // $category_attr_values = ProductionAttrValueModel::getProductionAttrs($modelProperties['id']);
                $modelProperties = $obj->__put($attrRestraint->getDefaultValues(), true);
            }
            $obj->category_id = $category_id;
        }
        return $obj;
    }

    public static function post(array $post){
        $modelProperties = self::correctArrayByTemplate($post, static::$defaultPorpertyValues);
        if($attrRestraint = ProductionAttrValueModel::getAttributesRestraintByCategoryId($modelProperties['category_id'])){
            // $category_attr_values = ProductionAttrValueModel::getProductionAttrs($modelProperties['id']);
            $modelProperties = array_merge($modelProperties, $attrRestraint->correntValues($post, true));
        }
        if($modelProperties['attributes']){
            if(is_array($modelProperties['attributes'])){
                $__attributes = $modelProperties['attributes'];
                $modelProperties['attributes'] = json_encode($__attributes);
            }elseif(is_string($modelProperties['attributes'])){
                $__attributes = json_decode($modelProperties['attributes'], true);
                if(is_array($__attributes)){
                    $modelProperties['attributes'] = json_encode($__attributes);
                }else{
                    $__attributes = [];
                    $modelProperties['attributes'] = '[]';
                }
            }
        }else{
            $__attributes = [];
            $modelProperties['attributes'] = '[]';;
        }
        if(isset($modelProperties["id"])){
            unset($modelProperties["id"]);
        }

        if($modelProperties['category_id']){
            $category = CategoryModel::byGUID($modelProperties['category_id']);
            if(!$category){
                return false;
            }
        }else{
            if(count($productions = ProductionModel::query('1 = 1', ProductionModel::ID_DESC, 1))){
                $modelProperties['category_id'] = $productions[0]->category_id;
                $category = CategoryModel::byGUID($modelProperties['category_id']);
			}else{
                return false;
			}
        }
        $brand = BrandModel::byGUID($modelProperties['brand_id']);
        $type = ProductionTypeModel::byGUID($modelProperties['type_id']);
        if(self::initQuerier()->insert($modelProperties)){
            $modelProperties["id"] = self::$querier->lastInsertId('id');
            $modelProperties['__attributes'] = $__attributes;
            $modelProperties['category'] = $category->getArrayCopy();
            $modelProperties['brand'] = $brand->getArrayCopy();
            $modelProperties['type'] = $type->getArrayCopy();
            self::getFileStorage()->store($modelProperties['id'], $modelProperties);
            return new static($modelProperties['id']);
        }
        return false;
    }

    protected function __construct($guid){
        $this->modelProperties = static::$defaultPorpertyValues;
        if($modelProperties = self::getFileStorage()->take($guid)){
            $this->__guid = $guid;
            $this->__put($modelProperties, true);
            $this->savedProperties = $this->modelProperties;
        }
    }

    protected function __put(array $input, $extendedable = false){
        if(is_array($input)||is_object($input)){
            foreach ($input as $key => $value) {
                if($extendedable||array_key_exists($key, $this->modelProperties)){
                    $this->modelProperties[$key] = $value;
                }
            }
        }
        return $this;
    }

    public function save(){
        if($this->savedProperties){
            return $this->__update();
        }
        return false;
    }

    protected function __update(){
        $querier = self::initQuerier();
        // 比对更改
        $diff = self::array_diff($this->savedProperties, $this->modelProperties, self::DIFF_SIMPLE);
        $update = $diff['__M__'];
        
        if(isset($update['id'])){
            $this->modelProperties['id'] = $this->savedProperties['id'];
            unset($update['id']);
        }
        if(count($update)===0){
            // 如果并无更新，则返回实例，意即成功
            return $this;
        }
        if($querier->where('id', $this->__guid)->update($update)!==false){
            foreach ($update as $key => $val) {
                $this->savedProperties[$key] = $val;
            }
            self::getFileStorage()->store($this->__guid);
            return true;
        }else{
            // 如果失败，返回false
            return false;
        }
        return false;
    }


    public function destroy(){
        if($this->savedProperties){
            if(self::initQuerier()->where('id', $this->__guid)->delete()!==false){
                self::getFileStorage()->store($this->__guid);
                return true;
            }
        }
        return false;
    }
}