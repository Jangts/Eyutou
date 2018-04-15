<?php
namespace Tangram\CACHE;

use Tangram\MODEL\ObjectModel;

/**
 * @class Tangram\CACHE\Resource
 * Resource Cache Model
 * 资源缓存对象模型
 * 新建、读取、删改委托给资源索引器的对象
 * 
 * 设计思路
 * ……
 * 若干疑问
 * ……
 * 这里应该先检查一下应用ID，然后将请求分应用存好，否则应用很难及时更新和清楚缓存
 * 但实际上还是存在一个问题，即管理某数据的应用，可能和输出他的应用不同，输出应用缓存了消息，管理应用还是无法删除
 * 另一个解决办法就是此处至存储长时效资源，且缓存时设置失效时间，在有效时间内始终有效，过期自动删除
 * 或者私有表应用自查，公共表需要触发几个应用的检查，这又需要应用提供清理接口
 * 所以使用公有表应用还是不要申请
 * ……
 * 解决方案
 * ……
 * 1. Resource 按应用ID和资源HASH存储数据
 * 2. 应用ID直接从Request中获取，不由外部提供。核心态时的ID即'TANGRAM'
 * 3. ResourceIndexer负责将Request对象放入到Resource类中
 * 4. ResourceIndexer会实例化一个Resource对象，并存在私有属性中，以保证活动应用能且只能委托核心来写入数据
 * 5. 应用可选择性的自己实例化Resource对象，并用它来读写输删数据
 * 6. 核心态时HASH数据来源与TRI，应用态时HASH数据来源于ARI
 * 7. 如果数据寄存在核心资源区，则会在分析路由前输出，前数据不能删改，只能等待失效，故只宜写入稳定长时效的数据
 * 8. 数据寄存在核心资源区，响应速度会高于寄存在活动应用资源区，如果两个都合适时，应优先考虑委托给ResourceIndexer缓存数据
 * 9. 活动应用使用资源缓存器，应尽量保证数据的独占性，且有缓存更删机制
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class Resource extends ObjectModel {
    protected static $request = NULL;

    private
    $appid = 'tangram',
    $key = NULL,
    $files = NULL;

    protected
    $data = [
        'hash'          =>  '',
        'mime'          =>  'text/html',
        'charset'       =>  'UTF-8',
        'content'       =>  '',
        'assign'        =>  [],
        'defind'        =>  [],
        'template'      =>  []
    ];
    
    /**  
	 * 初始化
     * 将$request对象存为静态属性
	 * 
     * @static
	 * @access public
     * @param object $request Tangram\MODEL\Request实例
	 * @return self 初次执行会返回一个实例
     * @return bool false
    **/ 
    public static function config($request){
		if(self::$request === NULL){
            self::$request = $request;
            return new static;
		}
		return false;
    }
    
    /**  
	 * 缓存资源对象构造函数
	 * 
	 * @access public
     * @param null|string $hash 哈希标识，核心时段无效，可为空
	 * @return 构造函数无返回值
	**/ 
    public function __construct($hash = NULL){
        // 获取活动应用ID，核心态时为'tangram'
        $appid = self::$request->TRI->patharr[0];
        if($appid==='tangram'){
            $this->key = self::$request->TRI->hash;
        }else{
            $this->appid = $appid;
            $this->key = $hash ? $hash : self::$request->ARI->hash;
        }
    }

    /**  
	 * 写入或跟新资源
	 * 
	 * @access public
     * @param object $data 
     * @param int $time 有效时间，单位为秒，默认是604800秒，即一周
	 * @return bool
	**/ 
    public function update($data, $time = 604800){
		#
		return false;
	}

    
    
    /**  
	 * 渲染缓存资源，并响应给客户端
	 * 
	 * @access public
	 * @return bool
	**/ 
    public function render(){
		#
		return false;
	}

    /**  
	 * 取出缓存资源备份
	 * 
	 * @access public
	 * @return bool
	**/ 
    public function copy(){
		#
		return false;
    }

    /**  
	 * 销毁该缓存
	 * 
	 * @access public
     * @param bool $comfirm 为防止误操作，仅当传入true时方法生效
	 * @return bool
	**/ 
    public function distroy($comfirm = false){
		#
		return false;
    }
    
    /* **/
    /**  
	 * 清空该应用下的所有资源
	 * 
	 * @access public
     * @param bool $comfirm 为防止误操作，仅当传入true时方法生效
	 * @return bool
	**/ 
    public function clear($comfirm = false){
		if($comfirm===true){
            #
            return true;
        }
        return false;
	}

}
