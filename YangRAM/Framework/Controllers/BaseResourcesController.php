<?php
namespace AF\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Tangram\MODEL\ObjectModel;
use App;

/**
 * @class AF\Controllers\BaseResourcesController
 * Resources Controller Base Class
 * 资源控制器基类
 * 与RESTFful路由对接，提供处理RESTFful接口的方法
 * 
 * @abstract
 * @author 		Jangts
 * @version    	5.0.0
**/
abstract class BaseResourcesController extends BaseController {
    use traits\crudmethods;

    public static
    // 排序方式别名映射表
    $__sorts = [],
    // 默认排序方式
    $__sortby = [['1', false, ObjectModel::SORT_REGULAR]];

    public static function __standardOrderByOptions($options){
        if(empty($options)){
            $options = $this->request->FORM->__get;
        }
        if(isset(static::$__sorts)&&is_array(static::$__sorts)){
            if(isset($options['sortby'])&&(isset(static::$__sorts[$options['sortby']]))){
                return static::$__sorts[$options['sortby']];
            }
        }
        if(!empty(static::$__sortby)&&is_array(static::$__sortby)&&is_array(static::$__sortby[0])){
            return static::$__sortby;
        }
        
        return [['1', false, \Model::SORT_REGULAR]];
    }

    public static function __standardRangeByOptions($options){
        if(empty($options)){
            $options = $this->request->FORM->__get;
        }

        if(isset($options['from'])){
            $start = $options['from'] - 1;
            if(isset($options['to'])){
                $length = $options['to'] - $start;
            }elseif(isset($options['num'])){
                $length = $options['num'];
            }else{
                $length = 0;
            }
        }else{
            $start = 0;
            if(isset($options['num'])){
                $length = $options['num'];
            }elseif(isset($options['to'])){
                $length = $options['to'];
            }else{
                $length = 0;
            }
        }
        return [$start, $length];
    }

    /**
     * MOF错误处理函数
     * MOF：Method Not Found
     * 
     * @access public
     * @return null
    **/
    public function returnMOFError(){
        $sp = new Status(1442.4, '', 'Method For "' . $this->request->METHOD . '" Not Found');
        $sp->respond(Status::JSON);
    }
}
