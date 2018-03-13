<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

/**
 * @class Tangram\MODEL\ApplicationPermissions
 * Process Permissions Table
 * 进程权限表
 * 记录着进程在当前时段是否可以使用敏感操作
 * 其状态可分为两段三态四个阶段：
 * **  初始化时为全权状态，且所有权限可读可写（核心时段全权读写态）
 * **  激活子应用后，由总控单元（Tangram\IDEA）根据当前活动子应用权限进行改写，此时权限因子应用而异，所有权限仍可重写（核心时段半权读写态）
 * **  激活路由后，由资源索引器（Tangram\CTRLR\ResourceIndexer）微调并改写权限表自身的可写权，所有权限不再可写（核心时段半权只读态）
 * **  进入子应用时段，权限以上次微调为准，所有权限只读（应用时段半权只读态）
 * 
 * @var int $RUN_LEVEL 						运行级别，0为核心级，1为系统级，2为拓展级
 * @var Boolean $ALL_PDOX_USEABLE 			使用所有注册PDOX
 * @var Boolean $DEFAULT_PDOX_USEABLE 		使用连接首选数据库的PDOX
 * @var Boolean $ACTIVITY_PDOX_USEABLE 		使用活动应用数据所在数据库的PDOX，仅在活动应用数据未记录在首选数据库中时
 * 
 * @var Boolean $ALL_RDBTABLE_READABLE 		所有数据表可读
 * @var Boolean $ALL_RDBTABLE_WRITEABLE 	所有数据表可写
 * @var Boolean $CLOUD_RDBTABLE_READABLE 	云盘数据表可读
 * @var Boolean $PUBLIC_RDBTABLE_WRITEABLE 	公共数据表可写
 * @var Boolean $USR_RDBTABLE_READABLE 		用户数据表可读
 * @var Boolean $REG_RDBTABLE_WRITEABLE 	注册数据表可写
 * 
 * @var Boolean $ALL_STORAGESG_READABLE 	所有数据存储仓可读
 * @var Boolean $ALL_STORAGESG_WRITEABLE 	所有数据存储仓可写
 * @var Boolean $RUNDATA_READABLE 			运行时缓存盘数据可读
 * @var Boolean $RUNDATA_WRITEABLE 			运行时缓存盘数据可写
 * @var Boolean $SYSDATA_READABLE 			系统运行时缓存盘数据可读
 * @var Boolean $USRDATA_READABLE 			用户数据盘可读
 * @var Boolean $USRDATA_WRITEABLE  		用户数据盘可写
 * @var Boolean $MEMORYCACHE_USEABLE 		内存缓存数据可用
 * @var Boolean $MEMORYCACHE_READABLE 		内存缓存数据可读
 * @var Boolean $MEMORYCACHE_WRITEABLE 		内存缓存数据可写
 * @var Boolean $ALL_REMOTEURL_GETABLE 		远程数据可读
 * @var Boolean $ALL_REMOTEURL_SETABLE 		远程数据可写
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class ApplicationPermissions {
	
	private static $instance = NULL;

	/**
	 * 权限表获取方法
	 * 第一次调用时创建权限表
	 * 
	 * @access public
	 * @static
	 * @return object(Tangram\MODEL\Permissions) 进程权限表
	**/ 
	public static function instance(){
		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}

	private
	// 运行级别，0为核心级，1为系统级，2为拓展级
	$RUN_LEVEL = 0,

	// 使用注册PDOX的权限
	$ALL_PDOX_USEABLE    =   true,
    $DEFAULT_PDOX_USEABLE    =   true,
    $ACTIVITY_PDOX_USEABLE   =   true,

	// 数据表的读写权限
	$ALL_RDBTABLE_READABLE   =   true,
	$ALL_RDBTABLE_WRITEABLE  =   true,
	$CLOUD_RDBTABLE_READABLE   =   true,	
	$PUBLIC_RDBTABLE_WRITEABLE  =   true,
	$USR_RDBTABLE_READABLE   =   true,
	$REG_RDBTABLE_WRITEABLE  =   true,
	
	// 本地数据的读写权限
    $ALL_STORAGESG_READABLE  =   true,
    $ALL_STORAGESG_WRITEABLE =   true,
    $RUNDATA_READABLE  =   true,
	$RUNDATA_WRITEABLE =   true,
	$SYSDATA_READABLE  =   true,
    $USRDATA_READABLE    =   true,
	$USRDATA_WRITEABLE   =   true,
	
	// 内存缓存数据的读写权限
	$MEMORYCACHE_USEABLE    =   true,
	$MEMORYCACHE_READABLE    =   true,
	$MEMORYCACHE_WRITEABLE   =   true,

	// 远程数据的读写权限
	$ALL_REMOTEURL_GETABLE  =   true,
    $ALL_REMOTEURL_SETABLE  =   true;

	/**
	 * 进程权限表构造函数
	 * 将构造函数私有化以保证其实例的单一性
	 * 
	 * @access private
	 * @return 构造函数无返回值
	**/
	private function __construct(){}

	/**
	 * __get()方法用来使私有属性可读
	 * 因为
	 * 
	 * @access public
	 * @param string $name 属性名称
	 * @return int 仅当读取$RUN_LEVEL属性时为整型
	 * @return boolean
	**/
	public function __get($name){
		if(isset($this->$name)){
			return $this->$name;
		}else{
			return false;
		}
	}

	/**
	 * __set()方法用来使私有属性在核心态时可写
	 * 
	 * @access public
	 * @param string $name 属性名称
	 * @param boolean $value 属性值，如果给一个非布尔型的值，函数会自动将其转化为布尔型
	 * @return mixed
	**/
	public function __set($name, $value){
		if($this->RUN_LEVEL===0&&isset($this->$name)){
			if($name === 'RUN_LEVEL'){
				$this->RUN_LEVEL = intval($value);
			}else{
				$this->$name = !!$value;
			}
		}
	}
}
