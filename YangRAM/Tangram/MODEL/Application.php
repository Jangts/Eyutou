<?php
// 核心控制模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

// 引入相关命名空间，以简化书写
use Status;
use Storage;
use Tangram\ClassLoader;
use Tangram\CTRLR\RDBQuerier;

/**
 * @class Tangram\MODEL\Application
 * Common Application
 * 通用应用控制器
 * 应用数据模型的基类，封包了应用的基本信息和方法
 * 并可以拓展应用的配置选项，并提供应用拓展信息的查询
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class Application {
    protected static
    $initialized = false,
    $conn = NULL,
	$public = [];
	
	/**
	 * 缓存活动应用使用的PDO Extended Connection
	 * 仅一次有效
	 * 
	 * @access public
	 * @final
	 * @static
	 * @param object(rdb_drivers\_abstract)|null $conn
	 * @return bool
	**/
    public static function cacheActivityAppPDOXConn($conn){
        if(self::$initialized===false){
			self::$initialized = true;
			if(is_a($conn, 'Tangram\CTRLR\rdb_drivers\_abstract')){
				self::$conn = $conn;
				return true;
			}
			return false;
        }
    	new Status(1400.4, 'Initialized Alrady', 'Class Common Have Been Initialized.', true);
	}
	
	/**
	 * 获取活动应用PDOX链接实例
	 * 
	 * @access public
	 * @final
	 * @static
	 * @return object
	**/
    public static function getActivityAppPDOXConn(){
        return self::$conn;
	}
	
	/**
	 * 获取商城应用信息
	 * 
	 * @access private
	 * @final
	 * @static
	 * @param int $code 应用代号，商城应用的应用代号即应用ID
	 * @return array
	**/
	private static function getOAMAppInfo($code){
		$querier = new RDBQuerier;
		$result = $querier->using(DB_REG.'apps')->where('app_id', $code)->select('app_id, app_name, app_scode, app_authorname, app_installpath, app_usedb');
		if($result&&$app = $result->item()){
			return [
				'APPID'		=>	$code,
				'ID'		=>	$app['app_id'],
				'Name'		=>	$app['app_name'],
				'SCode'		=>	$app['app_scode'],
				'Author'	=>	$app['app_authorname'],
				'DIR'		=>	preg_replace('/\/+/', '/', X_RPN.$app['app_authorname'].'/'.$app['app_installpath'].'/'),
				'DBTPrefix'	=>	_DBPRE_.$code.'_',
				'CONN'		=>	$app['app_usedb']
			];
		}else{
			new Status(1441.2, 'Application Not Found', 'No Suck Application.[APPID #'.$code.']', true);
		}
	}

	/**
	 * 获取原装应用（包括系统内建应用和开发者自增应用，不包括预装的商城应用）信息
	 * 
	 * @access private
	 * @final
	 * @static
	 * @param int $code 应用代号，原装应用的应用代号有时与应用ID略有不同
	 * @return array
	**/
	private static function getOEMAppInfo($code){
		$code = strtoupper($code);
		if(is_file(APATH.'applications.json')&&($apps=json_decode(file_get_contents(APATH.'applications.json'), true))){
			if(isset($apps[$code])){
				$app = $apps[$code];
				return [
					'APPID' 	=>	$app['id'],
					'ID' 		=>	$app['id'],
					'Name' 		=>	$app['name'],
					'SCode'		=>	$app['scode'],
					'Author'	=>	'YangRAM',
					'DIR' 		=>	preg_replace('/\/+/', '/', A_RPN.$app['installpath'].'/'),
					'DBTPrefix'	=>	_DBPRE_.strtolower($app['id']).'_',
					'CONN'		=>	$app['usedb']
				];
			}else{
				new Status(1441.1, 'Application Not Found', 'No Suck Application.[APPID #'.$code.']', true);
			}
		}else{
			new Status(1421.1, '', 'Cannot Find Applications Map.', true);
		}
	}

	/**
	 * 获取应用信息列表
	 * 
	 * @access public
	 * @final
	 * @static
	 * @param bool $includeOEMApp 是否包含内建应用，默认为否
	 * @return array
	**/
	public static function all($includeOEMApp = false){
		$objs = [];
        $querier = new RDBQuerier;
		$result = $querier->using(DB_REG.'apps')->select('app_id, app_name, app_scode, app_authorname, app_installpath, app_usedb, app_version');
        if($result = $querier->using(DB_REG.'columns')->select()){
            $pdos = $result->getIterator();
            while($app = $pdos->fetch(PDO::FETCH_ASSOC)){
				$props = [
					// 应用ID
					'APPID'		=>	$app['app_id'],
					'ID'		=>	$app['app_id'],

					// 应用名称
					'Name'		=>	$app['app_name'],

					// 自编短号，S取自Self和short
					'SCode'		=>	$app['app_scode'],

					// 应用版本，内建应用与系统核心模块同号
					'Version'	=>	$app['app_version'],

					// 应用作者，开发组织或机构，另有一个复数Developers译为开发者，只参与应用开发的自然人
					'Author'	=>	$app['app_authorname'],

					// 应用安装目录
					'DIR'		=>	($code>1000) ? X_RPN.$app['app_authorname'].'/'.$app['app_installpath'].'/' : S_RPN.$app['app_installpath'].'/',
					
					// 应用数据表前缀
					'DBTPrefix'	=>	_DBPRE_.'a'.$code.'_',

					// 应用数据表所在数据库编号
					'CONN'		=>	$app['app_usedb'],
				];
                $files = new Storage(RUNPATH_CA.$app['app_id'].'/', Storage::JSN, true);
				$files->store('baseinfo', $props);
                $obj = new self($app['app_id']);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    protected
	$appid,
	$code = -1,
	$level = 0,
	$properties = [],
	$files;

	/**
	 * 扩展应用信息
	 * 激活应用属性对象，获取更多应用信息
	 * 
	 * @access private
	 * @return array
	**/ 
    private function extendsProperties(){
		if(empty($this->properties['ExtendedProperties'])){
			if(_USE_DEBUG_MODE_){
				// 调试模式下，不使用缓存
				$props = $this->properties;
				$xProps = new ApplicationExtendedPropertiesModel($props);
				$props['ExtendedProperties'] = $xProps->getArrayCopy();
				$props['NAMESPACE'] = '\\'.$props['ExtendedProperties']['Namespace'].'\\';
			}else{
				$files = $this->files;
				$props = $files->take('appprops');
				if(!$props){
					$props = $this->properties;
					$xProps = new ApplicationExtendedPropertiesModel($props);
					$props['ExtendedProperties'] = $xProps->getArrayCopy();
					$props['NAMESPACE'] = '\\'.$props['ExtendedProperties']['Namespace'].'\\';
					$files->store('appprops', $props);
				}
			}
			ClassLoader::setNSMap([$props['ExtendedProperties']['Namespace'] =>	$props['Path']]);
			// if($props['ExtendedProperties']['Suitspace']){
			// 	if(is_numeric($this->appid)){
			// 		$props['SuitPath'] = APATH.$props['Author'].'/'.$props['ExtendedProperties']['Suitspace'].'/';
			// 	}else{
			// 		$props['SuitPath'] = dirname($props['Path']);
			// 	}
			// 	ClassLoader::setNSMap([$props['ExtendedProperties']['Suitspace'] =>	$props['SuitPath']]);
			// }
			$this->properties = $props;
		}
		return $this->properties;
	}

	/**
	 * 应用控制器构造函数
	 * 
	 * @access public
	 * @param int|string $code 应用代号
	 * @return 构造函数无返回值
	**/ 
    public function __construct($code){
		$code = strtoupper($code);
		if(_USE_DEBUG_MODE_){
			// 调试模式下，不使用缓存
			if(is_numeric($code)){
				$this->properties = self::getOAMAppInfo($code);
			}else{
				$this->properties = self::getOEMAppInfo($code);
			}
		}else{
			$files = new Storage(RUNPATH_CA.$code.'/', Storage::JSN, true);
			$files->setNameSpace();
			if($props = $files->take('baseinfo')){
				$this->properties = $props;
			}else{
				if(is_numeric($code)){
					$this->level = 2;
					$this->properties = self::getOAMAppInfo($code);
				}else{
					$this->properties = self::getOEMAppInfo($code);
				}
				$files->store('baseinfo', $this->properties);
			}
			$this->files = $files;
		}
		$this->properties['Path'] = __ROOT__.$this->properties['DIR'];
		$this->appid = $code;
    }   

	/**
	 * 容错性可写
	 * 表面上对象属性可增写，实际上不能
	 * 
	 * @access public
	 * @param string $name 属性名
	 * @param mixed $value 属性值
	 * @return bool
	**/
    public function __set($name, $value){
        return false;
	}
	
	/**
	 * 可拓展型魔法读取接口
	 * 
	 * @access public
	 * @param string $name 属性名
	 * @return mixed
	**/
	public function __get($name){
        if(isset($this->properties[$name])){
            return $this->properties[$name];
        }
		if($name==='xProps'){
			$this->extendsProperties();
            return $this->properties['ExtendedProperties'];
        }
		if($name==='Borthers'){
			$this->getBorthers();
            return $this->properties['Borthers'];
        }
        return NULL;
	}

	/**
	 * 读取应用ID
	 * 
	 * @access public
	 * @return int|string
	**/
	public function getAPPID(){
        return $this->appid;
    }

	/**
	 * 重写进程权限表
	 * 检查活动应用权限，同步到进程权限表，关闭权限表的写权限
	 * 
	 * @access public
	 * @return object
	**/
    public function rewritePermissions(){
		// 激活此应用
		$PERMISSIONS = $this->active(ApplicationPermissions::instance());
		
		// 检查应用权限表JSON版
		$filename = __ROOT__.$this->properties['DIR']._PERMISSIONS_FILE_NAME_.'.json';
		if(is_file($filename)){
			$content = file_get_contents($filename);
			if($content==='FULLPOWER'){
				// 全权应用
				// $init_val = true;
				$PERMISSIONS->RUN_LEVEL = $this->level;
				return $PERMISSIONS;
			}
			if($props = json_decode($content, true)){
				// 有详细权限设置的应用
				foreach ($props as $key => $value) {
					$PERMISSIONS->$key = $value;
				}
				return $PERMISSIONS;
			}
		}
		// 零权应用
		$init_val = false;
		$props = [
			"RUN_LEVEL" 				=>	$this->level,

			"ALL_PDOX_USEABLE"			=>	$init_val,
			"DEFAULT_PDOX_USEABLE"		=>	$init_val,
			"ACTIVITY_PDOX_USEABLE"		=>	$init_val,

			"ALL_RDBTABLE_READABLE"		=>	$init_val,
			"ALL_RDBTABLE_WRITEABLE"	=>	$init_val,
			"CLOUD_RDBTABLE_READABLE"	=>	$init_val,
			"PUBLIC_RDBTABLE_WRITEABLE"	=>	$init_val,
			"USR_RDBTABLE_READABLE"		=>	$init_val,
			"REG_RDBTABLE_WRITEABLE"	=>	$init_val,
	
			"ALL_STORAGESG_READABLE"	=>	$init_val,
			"ALL_STORAGESG_WRITEABLE"	=>	$init_val,
			"RUNDATA_READABLE"			=>	$init_val,
			"RUNDATA_WRITEABLE"			=>	$init_val,
			"SYSDATA_READABLE"			=>	$init_val,
			"USRDATA_READABLE"			=>	$init_val,
			"USRDATA_WRITEABLE"			=>	$init_val,
	
			"MEMORYCACHE_USEABLE"		=>	$init_val,
			"MEMORYCACHE_READABLE"		=>	$init_val,
			"MEMORYCACHE_WRITEABLE"		=>	$init_val,

			"ALL_REMOTEURL_GETABLE"		=>	$init_val,
			"ALL_REMOTEURL_SETABLE"		=>	$init_val
		];
		$content = json_encode($props);

		$file = @fopen($filename, 'w') or Status::cast('Unable to write file! The current log file may be read-only.', 1411.3, 'Permission Denied');
		fwrite($file, $content);
		fclose($file);
			
		foreach ($props as $key => $value) {
			$PERMISSIONS->$key = $value;
		}
		return $PERMISSIONS;
	}

	/**
	 * 激活应用，激活后应用即为活动应用
	 * 此方法一次有效
	 * 
	 * @access public
	 * @param object(Tangram\MODEL\ApplicationPermissions) $permissions 进程权限表
	 * @return object
	**/
	public function active(ApplicationPermissions $permissions){
		if(defined('AI_CURR')){
			return false;
		}
		$appdata = $this->extendsProperties();

		define('AI_CURR', $appdata['APPID']);
		define('TP_CURR', $appdata['DBTPrefix']);
		define('CI_CURR', $appdata['CONN']);
		define('AD_CURR', $appdata['DIR']);
		define('AP_CURR', $appdata['Path']);
		return $this->properties['POWERS'] = $permissions;
	}

	/**
	 * 运行应用
	 * 
	 * @access public
	 * @param string|int $router 路由ID
	 * @param array	$ipcOptions	IPC参数
	**/
	private function run($classFile, $className, array $ipcOptions = []){
		$GLOBALS['APPLICATION'] = $this;
		$GLOBALS['REQUEST'] = RequestModel::instance();

		// 加载路由脚本文件
		if(is_file($classFile)){
			include($classFile);
		}else{
			new Status(1400.1, 'Application '.$this->properties['Name'].' Initialization Failure', 'Cannot Find Router File [' . $classFile . ']', true, true);
		}

		// 实例化路由器，并运行之
		if(class_exists($className)){
			if(!defined('APPTIME')) define('APPTIME', microtime(TRUE));
        	new $className($this, $GLOBALS['REQUEST'], $ipcOptions);
		}else{
	        new Status(1442.1, 'Router Not Found', "Files of application [".$this->properties['Name']."] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
	    }
	}

	public function handleError(){
		$appdata = $this->properties;
		if(is_file($classFile = $appdata['Path'].'Routers/ResourceNotFound.php')){
			$className = $appdata['NAMESPACE'].'Routers\ResourceNotFound';
			return $this->run($classFile, $className);
		}
		return $this->handleRouterById();
	}

	public function handleResource(){
		$classFile = FPATH.'Routers/RESTfulRouter.php';
		$className = 'AF\Routers\RESTfulRouter';
		return $this->run($classFile, $className);
	}

	public function handleStdAPI(){
		$appdata = $this->properties;
		$classFile = $appdata['Path'].'Routers/StandardRouter.php';
		$className = $appdata['NAMESPACE'].'Routers\StandardRouter';
		return $this->run($classFile, $className);
	}

	public function handleRouterById(){
		$appdata = $this->properties;
		if(isset($this->xProps['Routers'][RI_CURR])){
			$classFile = $appdata['Path'].'Routers/'.$this->xProps['Routers'][RI_CURR].'.php';
			$className = $appdata['NAMESPACE'].'Routers\\'.$this->xProps['Routers'][RI_CURR];
			return $this->run($classFile, $className);
		}
		// 如果指定路由不存在
		new Status(1442, '', 'Router ['.RI_CURR.'] Not Defined', true);
	}

	public function testController(){
		$classFile = FPATH.'Routers/StdTestRouter.php';
		$className = 'AF\Routers\StdTestRouter';
		return $this->run($classFile, $className);
	}

	public function workInOtherProcess(array $ipcOptions){
		$appdata = $this->extendsProperties();
		$classFile = $appdata['Path'].'Routers/IPCInterface.php';
		$className = $appdata['NAMESPACE'].'Routers\IPCInterface';
		return $this->run($classFile, $className, $ipcOptions);
	}
}