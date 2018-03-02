<?php
namespace PM\_8;

// 引入相关命名空间，以简化书写
use App;
use AF\Models\BaseR3Model;

class CategoryAttrModel extends BaseR3Model {

    protected static
    $staticQuerier,
    $staticTablrnamePrefix,
    $tablenameAlias = 'category_attrs',
    $fileStoragePath = RUNPATH_CA.'GOODS/category_attrs/',
    $fileStoreLifetime = 0,
    $defaultPorpertyValues  = [
        'id'                =>  0,
        'category_id'       =>  0,
        'attribute_name'    =>  '',
        'attribute_alias'   =>  '',
        'attribute_type'    =>  '',
        'max_value_length'  =>  '',
        'max_float_length'  =>  '',
        'values_range'      =>  '',
        'default_value'     =>  ''
    ];

    protected static function initQuerier(){
        if(!self::$staticQuerier){
            $app = new App(8);
            self::$staticTablrnamePrefix = $app->DBTPrefix;
            self::$staticQuerier = new \DBQ($app->CONN);
        }
        return self::$staticQuerier->using(self::$staticTablrnamePrefix.self::$tablenameAlias);
    }

    public static function byCategoryId($category_id){
        $category_ids = [$category_id];
        $category = CategoryModel::byGUID($category_id);
        while($category->parent_id){
            $category_ids[] = $category->parent_id;
            $category = CategoryModel::byGUID($category->parent_id);
        }
        if(count($category_ids)>1){
            return self::query('`category_id` IN (' . implode(',', $category_ids) .') AND `state` = 1', [['id', false, self::SORT_REGULAR]]);
        }
        return self::query(['category_id'=>$category_id, 'state'=>1], [['id', false, self::SORT_REGULAR]]);
    }

    public function getAttributesRestraintByCategoryId($category_id){
        $category_attrs = self::byCategoryId($modelProperties['category_id']);
        $attrs = [];
        foreach($category_attrs as $category_attr){
            switch($category_attr->attribute_type){
                // case 'all'      :
                // 此类类型，不允许设置约束
                // 所以只需传入取值范围和首选值

                // case 'scalar'        :
                // 此类类型自带类型判断，所以只需要传入取值范围和首选值

                case 'file'         :
                case 'ip'           :
                case 'ipv6'         :
                case 'url'          :
                case 'imgtext'      :
                case 'date'         :
                case 'time'         :
                case 'email'        :
                case 'datetime'     :
                case 'color'        :
                case 'bin'          :
                case 'hex'          :
                // 此类类型预设了pattrrn,
                // 且不需要控制长度和浮点长度（实际上此类类型的RestraintModel是支持长度的，但此处为图方便省略掉了）
                // 所以只需要再补充取值范围（不建议）和首选值

                case 'text'         :
                case 'string'       :
                // case 'int'          :
                // case 'float1'       :
                // case 'float2'       :
                // case 'float3'       :
                // 此类类型可以设置长度、取值范围和首选值
                // 其中前三项是不需要设置浮点长度，后三项是自带浮点长度
                if($category_attr->max_value_length){
                    $length = json_decode($category_attr->max_value_length , true) || 0;
                }
                if($category_attr->values_range){
                    $range = json_decode($category_attr->values_range , true) || [];
                }
                $attrs[$category_attr->attribute_name] = [
                    'type'          =>      $category_attr->attribute_type,
                    'default'       =>      $category_attr->default_value,
                    'length'        =>      $length,
                    'range'         =>      $range
                ];
                break;
                break;

                case 'char'         :
                case 'varchar'      :
                // 此类类型预设了文本的长度
                // 因为并不打算对普通用户暴露『正则』这种专业的东西，所以直接省略掉
                // 所以，同上面一样只需要再补充取值范围（不建议）和首选值
                if($category_attr->values_range){
                    $range = json_decode($category_attr->values_range , true) || [];
                }
                $attrs[$category_attr->attribute_name] = [
                    'type'          =>      $category_attr->attribute_type,
                    'default'       =>      $category_attr->default_value,
                    'range'         =>      $range
                ];
                break;

                case 'percentage'   :
                case 'dayofweek'    :
                // 此类类型预设了取值范围、字符长度和浮点长度（其基本型是int，浮点长度也没什么作用）
                // 只需要再传入首选值即可（其实RestraintModel是可以添加取值范围的，此处省略）
                $attrs[$category_attr->attribute_name] = [
                    'type'          =>      $category_attr->attribute_type,
                    'default'       =>      $category_attr->default_value,
                ];
                break;

                case 'radio'        :
                case 'tags'         :
                case 'checkbox'     :
                // 此类类型必须给定取值范围，否则不予录入
                // 且除取值范围外，不再允许传入其他
                if($range = json_decode($category_attr->values_range , true)){
                    $attrs[$category_attr->attribute_name] = [
                    'type'          =>      $category_attr->attribute_type,
                    'default'       =>      $category_attr->default_value,
                    ];
                }
                break;
                
                // case 'stamp'        :
                // case 'float'        :
                // case 'double'       :
                // // 此三项可传入正则模式外的所有5类约束
                // if($category_attr->max_value_length){
                //     $length = json_decode($category_attr->max_value_length , true) || 0;
                // }
                // if($category_attr->max_float_length){
                //     $floatlen = json_decode($category_attr->max_float_length , true) || 0;
                // }
                // if($category_attr->values_range){
                //     $range = json_decode($category_attr->values_range , true) || [];
                // }
                // $attrs[$category_attr->attribute_name] = [
                //     'type'          =>      $category_attr->attribute_type,
                //     'default'       =>      $category_attr->default_value,
                //     'length'        =>      $length,
                //     'flote'         =>      $floatlen,
                //     'range'         =>      $range
                // ];
                // break;

                case 'is'           :
                case 'boolean'      :
                case 'bool'         :
                // 此三类型仅允许传入整型默认值
                // 它们将都被录入为is类型
                $attrs[$category_attr->attribute_name] = [
                    'type'          =>      'is',
                    'default'       =>      strval(intval($category_attr->default_value))
                ];
                break;

                case 'files'        :
                case 'json'         :
                // 此二类型不需要传入参数
                $attrs[$category_attr->attribute_name] = [
                    'type'          =>      $category_attr->attribute_type,
                    'default'       =>      NULL
                ];
                break;;
            }
        }
        if(isset(self::$attributesRestraint[$category_id])){
            return self::$attributesRestraint[$category_id];
        }
        if(count($attrs)){
            return self::$attributesRestraint[$category_id] = new XtdAttrsModel($attrs);
        }
        return NULL;
    }

    public function getCategory(){
        return CategoryModel::byGUID($this->modelProperties['category_id']);
    }
}