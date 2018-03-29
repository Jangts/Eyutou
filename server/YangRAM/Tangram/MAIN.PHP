<?php
// +-----------------------------------------------------------------------+
// | Module		Core Classes package 系统核心类包
// +-----------------------------------------------------------------------+
// | Contain	Tangram\IDEA主控单元类和Tangram\Loader加载器类
// +-----------------------------------------------------------------------+
// | Author		Jangts
// +-----------------------------------------------------------------------+
// | Time		2018年1月1日
// +-----------------------------------------------------------------------+

// 核心模块公用的命名空间，即核心模块的名称Tangram
namespace Tangram;

// 引入相关命名空间，以简化书写
use PDO;
use Tangram\CTRLR\StatusProcessor;
use Tangram\MODEL\ResponseModel;

/**
 * @class Tangram\IDEA
 * Bus Control Unit (Intellective/Digital/Effective/Accurate)
 * 总控制单元————智能化的、数据化的、高效的、精准的，俗称主控制器
 * 由启动脚本（即启动文件中的代码）调用的仿单例类，其唯一实例即全局变量$NEWIDEA
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class IDEA {
	/**
	 * YangRAM的公开版本号，格式为
	 * {Major_Version_Number}.{Minor_Version_Number}.{Revision_Number}
	 * 其中
	 * -- 主版本号为营销意义上的版本号，以市场为主导来更改；
	 * -- 子版本号为实际意义上的版本号，反映版本的实际变动，
	 * -- --- 一个营销周期内（即主版本号不变的情况下），版本的变更由子版本号体现，
	 * -- --- 无论是主版本号变更，还是子版本号变更，都意味著YangRAM已经升级，其中主版本号的变更亦可称为迭代，
	 * -- --- 应尽力一代一版，最多不应超过四版，以保证版代差的稳定，同时采取主版本号跳号的方式来调整版代差；
	 * -- 修正号是在同一版本（子版本号为准）号下的更新号，是相对版本跳动所产生的变更较小的变更，多以修复bug和完善为主，
	 * -- --- 修正号正常情况采用累加法，但也会因为其他因素（如重大调整或营销噱头）跳号，
	 * -- --- 修正号变更代表YangRAM更新，会产生相应的更新补丁，开啓了自动更新的I4s会自动更新；
	 * -- --- 负责更改修正号的团队或个人需要呈交修正报告，中含修正原因、与上一次修正有哪些变动、与上一个版本有哪些变动等；
	 * -- --- 负责更改修正号的团队或个人同时负责製作补丁；
	 * -- --- 负责更改修正号的团队或个人对新补丁产生的错误和漏洞负责。
	**/
	const VERSION = '5.0.0';
	
	/**
	 * YangRAM的内部版本号，正式名为‘YangRAM修正增删日志号’，简称‘日志号’，其中
	 * -- 修，修改错误和漏洞，
	 * -- 正，优化逻辑和代码，
	 * -- 增，增加模块和功能，
	 * -- 删，删除模块和功能；
	 * 日志号用以记录YangRAM的每一次修改（包括架构/内核/标准库/系统应用等的修改），一般来说，
	 * -- 以工作日为周期，特殊情况下，如单日出现多次重大修正，或者同时存在多个分支（如两个团队同一时间分别进行着2.1版的维护和3.0的开发）时
	 * -- 允许一日记录多次，在数日没有实质进展的情况下，也
	 * -- 允许多日记录一次；
	 * 日志号用以记录实际修改次数，所以必需严格
	 * -- 使用累加法，不得跳号，同时为保证日志号得唯一性，
	 * -- 所有分支都应采用同一组日志号，即使这样会导致
	 * -- 连续的日志号不代表连续的修改；
	 * 只有特定的日志号会被公开，多数日志号不会被公开，因为
	 * -- 日志号的变更仅代表YangRAM团队的工作进度，不能代表YangRAM的更新进度，YangRAM的更新以修正号的变更为准；原则上，
	 * -- 只有稳定而具有实际生产意义的修改才会同时产生日志号和修正号，并一同公开给外界，公开的完整的YangRAM版本格式为
	 * -- --- YangRAM {Major_Version_Number}.{Minor_Version_Number}.{Revision_Number} Dev {Dev_ID_Number}[, {Version Alias}]，如
	 * -- --- YangRAM 5.0.0 Dev 0 (此版没有别名，故省略{Version Alias})，或
	 * -- --- YangRAM 5.1.8 Dev 518 2018；
	 * 负责更改日志号的工作人员需要同时收集、整理、记录修改细则，必要时还要记录操作缘由；
	 * 日志号藉鉴了编译式程序的Build号，但是产生方式不大相同，且产生更加频繁。
	**/
	const DEVID = '0';
	
	/**
	 * 'DEVELOP' 开发模式
	 * -- 可用于测验流程设计和数据设计的合理性，重点在于测试框架和业务逻辑，不必过于强调UI等细节；
	 * -- 可用于检验功能是否可用、细节是否符合预期，以及在出现非预期情形时查验错误并修改之；
	 * -- 此模式下会强制开启技术员调试模式（即使你在配置表里关闭了调试模式）；
	 * 'RELEASE' 发行模式
	 * -- 系统上线时的必需模式
	 * -- 此模式下仍可以开启争对一般技术员的调式模式，该模式下系统出现异常时技术员可以得到更详细的提示；
	 * -- 关闭调试模式时，系统遇到异常时会选择忽略错误，容错，或友好地中断（并在服务器的运行时目录以文件形式记录问题）；
	 * -- 如何使用调试模式取决于开发者，YangRAM鼓励但不强制要求开发者去使用（虽然这个明显有利于开发者）。
	**/
	// const MODE = 'DEVELOP';
	const MODE = 'RELEASE';
	
	const PHP_REQUIRE = '5.4.0'; 	// 此版本YangRAM所需的最低PHP版本好

	private static
	$instance,						// 总控单元实例，唯一实例
	$isBoot = false;				// 系统启动标识符

	public
	/**
	 * @var object $RI				资源索引器，resource indexer
	 * @var object $RQ 				标注化请求对象
	 * @var object $LANGS 			可选语言列表
	 * @var object $Permissions 	活动应用权限表，Application Permissions
	 * @var object $AW				应用WORKER的实例
	 * @var object $AA				动应用实例，Active App
	**/
	$AI = 'TANGRAM',
	$RI, $RQ, $LANGS, $Permissions, $AW, $AA;
	
	private $LANGUAGE;				// 核心模块的运行语言语言

	/**
	 * 总控单元构造函数，仅一次有效
	 * 
	 * @access public
	 * @return 构造函数无返回值
	**/ 
	public function __construct(){
		// 如果已经存在一个MAIN的实例，则判定为重复实例化，系统将中断，并抛出文本提示。
		IDEA::$instance === NULL or exit('Cannot Reinstance System');

		// 检查PHP版本，低于指定版本则中断系统，并抛出文本提示。
        if (version_compare(PHP_VERSION, IDEA::PHP_REQUIRE, '<') ) exit("Sorry, this version of YangRAM will only run on PHP version " . IDEA::PHP_REQUIRE . " or greater!\n");
        
        // 将本实例对象赋值给IDEA::$instance，以便内部应用
		IDEA::$instance = $this;

		// 初始化状态码处理器
		CTRLR\StatusProcessor::init();

		// 开启缓冲
		ob_start();

		// 通过标头设置默认的输出格式
        header('Content-Type: text/plain;charset=utf-8');
	}

	/**
	 * 启动核心模块
	 * 
	 * @access public
	 * @return boolean true
	 * @return null
	**/
	public function run_kernel_modules(){
		// 记录核心模块的启动时间戳，精确到0.1微妙
		define('NIKTIME', microtime(TRUE));

		// 初始化文件加载器
		ClassLoader::instance();

		// 为常用的类创建别名
		class_alias('Tangram\CACHE\DataStorage', 'Storage');
		class_alias('Tangram\CACHE\Session', 'SESSION');
		class_alias('Tangram\CTRLR\RDBQuerier', 'DBQ');
		class_alias('Tangram\MODEL\interfaces\model', 'DataModel');
		class_alias('Tangram\MODEL\interfaces\collection', 'Collection');
		class_alias('Tangram\MODEL\Application', 'App');
		class_alias('Tangram\MODEL\RequestModel', 'Request');
		class_alias('Tangram\MODEL\ResponseModel', 'Response');
		class_alias('Tangram\MODEL\UserPassportModel', 'Passport');

		// 初始化数据库连接
		$this->prepare_db_connections($this->initialize());

		// 比对入口脚本，以保证系统的安全性
		if(strcasecmp(dirname($_SERVER['SCRIPT_FILENAME']), __ROOT__)===0){
			IDEA::$isBoot = true;

			// 初始化资源索引器（又称总路由器）
			$this->RI = CTRLR\ResourceIndexer::instance();
			$this->RQ = MODEL\RequestModel::instance();
			if(APPID==='tangram'){
				if(route){
					if(route===400){
						return new StatusProcessor(400, '', 'Please Specify The Application ID Or Resource Type!', true);
					}
					return new StatusProcessor(route, '', $_SERVER['REQUEST_URI'], true);
				}
				$sp = new StatusProcessor(200);
				return $sp->respond(StatusProcessor::AUTO);
			}
			return true;
		}

		$sp = new CTRLR\StatusProcessor(403);

		return $sp->respond(CTRLR\StatusProcessor::TLOG);
	}

	/**
	 * 初始化系统
	 * 
	 * @access public
	 * @return array 数据库链接配置
	**/
	private function initialize(){
		// 检查是否存在配置文件，并检查配置文件的正确性
		if(is_file(__ROOT__.'/configuration.ini')&&$config = json_decode(file_get_contents(__ROOT__.'/configuration.ini'), true)){
			// 检查系统运行模式
			if(self::MODE==='DEVELOP'&&!defined('_USE_DEBUG_MODE_')){
				define('_USE_DEBUG_MODE_', 1);
				ini_set('display_errors','1');
			}

			// 检查是否强制指定调试模式的开闭
			if(defined('_USE_DEBUG_MODE_')){
				unset($config['constants']['_USE_DEBUG_MODE_']);
			}

			// 检查是否强制指定后台任务运行的禁许
			if(defined('_TASKER_ENABLE_')){
				unset($config['constants']['_TASKER_ENABLE_']);
			}

			// 常量化各配置项
			$config['constants']['_DBPRE_'] .= '_';
			foreach($config['constants'] as $const=>$value){
				define($const, $value);
			}

			// 根据运行模式配置服务器是否需要显示错误详情
			if(_USE_DEBUG_MODE_){
				ini_set("display_errors", "On");
			}

			// 配置服务器分配给进程的最大内存量
			ini_set('memory_limit', _MAXML_);

			// 定义数据表表头常量
			define('DB_PUB',				_DBPRE_.'_'); 	// 公共表，由应用注册和关联
			// 以下分别是内建应用RegEditor，Users和Cloud的表
			define('DB_REG',				_DBPRE_.'reg_');		// 系统信息注册表
			define('DB_USR',				_DBPRE_.'users_');		// 用户信息表
			define('DB_YUN',				_DBPRE_.'cloud_');		// 应用YangRAN Cloud（鞅云）的私有表
			
			// 明确当前进程语言
			$this->LANGUAGE = _LANG_;
			// 写入可用语言，多语言站点使用
			if(isset($config['i18n'])&&count($config['i18n'])){
				$this->LANGS = $config['i18n'];
			}

			if(defined('__CODE__')){
				new CTRLR\StatusProcessor(__CODE__, true);
			}

			// 初始化一般数据存储仓
			CACHE\DataStorage::initialize($config['rdsconnections'], $config['memconnections']);

			// 返回数据库配置
			return $config['rdbconnections'];
		}

		// 配置文件丢失或损坏，系统因无法初始化而系统中断
		exit('Initialization Failure! Cannot Load Configure File.');
	}

	/**
	 * 初始化系统
	 * 
	 * @access private
	 * @param array $connArray 一组JSON格式的数据库链接配置
	 * @return null
	**/
	private function prepare_db_connections($connArray){
		// 初始化应用权限表
		$Permissions = MODEL\ApplicationPermissions::instance();

		// 初始化RDB链接相关的模块
		$CONNS = [];
		if(count($connArray)){
			foreach($connArray as $n => $conn){
				$CONNS[] = [
					'driver'	=>  $conn['driver'],
					'options'	=>	$conn,
					'instance'	=>	NULL
				];
			}
			$Permissions->DEFAULT_PDOX_USEABLE = true;

			// 初始化关系数据库查询器RDBQuerier
			CTRLR\RDBQuerier::initialize($Permissions, $CONNS);
			// 链接首选数据库
			// CTRLR\RDBQuerier::connectPDOX(0);

			// 将进程权限表赋值给总控单元的$Permissions属性，以便全局调用
			$this->Permissions = $Permissions;

			// 中断方法，将进程的控制权交还给run_core_modules()方法
			return;
		}
		
		// 我也忘了这是一段干什么的代码
		if(defined('L')){
			$Permissions->APP_APDOX_GETABLE = false;
			$Permissions->APP_PDOXS_GETABLE = false;

			// 将进程权限表赋值给总控单元的$Permissions属性，以便全局调用
			$this->Permissions = $Permissions;

			// 中断方法，将进程的控制权交还给run_core_modules()方法
			return;
		}
		
		/**
		 * 因系统运行所需的部分数据是记录在关系数据库之中的，所以必需为系统配置连接到该数据库的参数
		 * 如果未配置该数据库的连接参数，系统将会中断
		**/
		$sp = new CTRLR\StatusProcessor(1403.1, 'Default Database Not Found', 'Must set a default database connection configuration.');
		return $sp->respond(CTRLR\StatusProcessor::TLOG);
	}

	/**
	 * 激活并运行活动应用
	 * 
	 * @access public
	 * @return boolean true
	 * @return null
	**/
	public function run_async_taskworkers(){
		return;
		if(_TASKER_ENABLE_&&APPID!=='TASKS'){
			// 如果AW尚未被创建，则创建之
			if($this->AW===NULL){
				$this->AW = new MODEL\Application('TASKS');
			}
		
			switch (ROUTE) {
				case 1:
				case 3:
				if(($_SERVER['REQUEST_METHOD']!='GET')&&(substr(DATETIME, 17, 1)=='1')) $this->RI->checktasks($this->AW);
				break;
	
				case 5:
				case 6:
				case 7:
				if(substr(DATETIME, 17, 2)=='11') $this->RI->checktasks($this->AW);
				break;
			}
		}
	}

	/**
	 * 激活并运行活动应用
	 * 
	 * @access public
	 * @return boolean true
	 * @return null
	**/
	public function run_activity_application(){
		// 如果AA尚未被激活
		if($this->AA===NULL){
			$this->AI = APPID;
			// 创建活动应用实例
			$this->AA = new MODEL\Application(APPID);

			// 重写进程权限表，关闭权限表的写权限
			$Permissions = $this->AA->rewritePermissions();

			if($Permissions->ALL_PDOX_USEABLE||$Permissions->ACTIVITY_PDOX_USEABLE){
				$PDOX = CTRLR\RDBQuerier::getLastPDOXConn(intval($this->AA->app_usedb));
				MODEL\Application::cacheActivityAppPDOXConn($PDOX);
			}else{
				MODEL\Application::cacheActivityAppPDOXConn(NULL);
			}
			$this->RI->route($this->AA, $Permissions);
		}else{
			exit('Subapplication is already running.');
		}
	}

	/**
	 * 记录运行日志
	 */
	public function log($type, $text){
		if(is_string($type)&&preg_match('/^\w+$/',$type)){
			$path = RUNPATH_LOG.'notes/'.$type.'/'.date('Ym').'/';
			$filename = $path.date('Ymd');
			//$text = # 需要对text进行检查;
			if (!file_exists($path)){
				mkdir($path, 0777, true);
			}
			$file = @fopen($filename, 'a') or new CTRLR\StatusProcessor(1406.3, 'Permission Denied', 'Unable to write run log! The current log file may be read-only.', true);
			fwrite($file, $text);
			fclose($file);
		}
		return false;
	}

	/**
	 * 获取当前语言等属性的魔术方法
	 */
	final public function __get($name){
		if($name==='LANGUAGE'){
			if(empty($this->LANGUAGE)||!is_string($this->LANGUAGE)){
				return $this->LANGUAGE = _LANG_;
			}
			return $this->LANGUAGE;
		}
		return null;
	}
	
	/**
	 * 设置当前语言等属性的魔术方法
	 */
    final public function __set($name, $value){
        if($name==='LANGUAGE'){
			if(!empty($value)&&is_string($value)){
				$this->LANGUAGE = $value;
			}
		}
		return $value;
    }
}




/**
 * @class Tangram\ClassLoader
 * Scripts ClassLoader Module
 * 脚本加载器模块
 * 仿单例类，由总控单元（全局引用名为$NEWIDEA）抢先实例化
 * 提供安全的批量加载（主动）方法和懒加载（被动）的配置接口
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class ClassLoader {
	private static $instance = NULL;

	/**
     * 服务器文件系统是否忽略大小写
     * 由静态方法ClassLoader::formatPathnameCase()初始化
     * 
     * @access public
     * @var null|bool $is_ignore_case
     */
    public static $is_ignore_case = null;

    /**  
	 * 路径格式化函数
     * 之所有存在这个函数，是因为linux系统默认区分大小写，如果强制小写化路径，可能导致有些本地文件无法读取到
     * 所以我们进行一次了检查，如果系统不区分大小写，就转小写，否则不操纵
	 * 
	 * @access public
     * @static
     * @param string $string
	 * @return bool
	**/ 
    public static function formatPathnameCase($string){
        if(self::$is_ignore_case===null){
            if(is_file(__DIR__.'/main.php')){
                self::$is_ignore_case = true;
            }else{
                self::$is_ignore_case = false;
            }
		}
        if(self::$is_ignore_case===true){
            return strtolower($string);
        }
        return $string;
    }

	private
	// 自动加载路径翻译表
	$map = [];

	/**
	 * 加载器实例获取方法
	 * 第一次调用时创建加载器实例并进行初始配置
	 * 
	 * @access public
	 * @static
	 * @return object(Tangram\ClassLoader) 脚本加载器
	**/ 
	public static function instance(){
		if(self::$instance === NULL){
			// 创建加载器实例，并将该实例的includeClassOfNSMap()方法注册到PHP的自动加载方位法库中
			// 改实例将被保存在此类的$instance属性中，以便以后调用
			spl_autoload_register([new self, 'includeClassOfNSMap']);
			self::setNSMap([
				'Tangram'	=>	CPATH,
				'Lib'	=>	LIB_PATH,
				'Packages'	=>	LIBX_PATH,
				'AF'		=>	FPATH,
				'PM'		=>	PMPATH
			]);
		}
		return self::$instance;
	}

	/**
	 * 路径与命名空间映射关系注册方法
	 * 
	 * @access public
	 * @static
	 * @param array $map	需要注册的路径与命名空间映射表
	 * @return 				构造函数无返回值
	**/
	public static function setNSMap(array $map){
		foreach ($map as  $ns => $path) {
			self::$instance->map[] = [str_replace('\\', '\/', $ns), $path];
		}
		return true;
	}

	/**
	 * 脚本加载器构造函数
	 * 将构造函数私有化以保证其实例的单一性
	 * 调用时还将加载主要核心模块所在的文件
	 * 
	 * @access private
	 * @return 构造函数无返回值
	**/
	private function __construct(){
		// 将此实例赋值给类的$instance属性，以便静态方法instance()传递给外部
		self::$instance = $this->load_modules();
	}


	private function load_modules(){
		// 加载数据模型所需的文件
		include(CPATH.'MODEL/interfaces/model.php');
		include(CPATH.'MODEL/interfaces/collection.php');

		
		if(IDEA::MODE==='RELEASE'){
			include(CPATH.'MODEL/traits/_package.php');
		}else{
			include(CPATH.'MODEL/traits/arraylike.php');
			include(CPATH.'MODEL/traits/conversion.php');
			include(CPATH.'MODEL/traits/formatting.php');
			include(CPATH.'MODEL/traits/magic.php');
		}

		include(CPATH.'MODEL/ObjectModel.php');
		include(CPATH.'MODEL/DataList.php');
		
		include(CPATH.'MODEL/RequestModel.php');
		include(CPATH.'MODEL/RouteCollection.php');
		include(CPATH.'MODEL/InputsModel.php');
		include(CPATH.'MODEL/ResponseModel.php');

		include(CPATH.'MODEL/Application.php');
		include(CPATH.'MODEL/ApplicationPermissions.php');
		include(CPATH.'MODEL/ApplicationExtendedPropertiesModel.php');

		include(CPATH.'MODEL/RDBRowsCollection.php');

		include(CPATH.'MODEL/UserPassportModel.php');

		// 加载存储模块所需的文件
		include(CPATH.'CACHE/cac_agents/_abstract.php');
		include(CPATH.'CACHE/cac_agents/Filesys.php');

		include(CPATH.'CACHE/DataStorage.php');

		// 加载数据模型所需的文件
		include(CPATH.'CTRLR/rdb_drivers/_abstract.php');

		if(IDEA::MODE==='RELEASE'){
			include(CPATH.'CTRLR/rdb_traits/_package.php');
		}else{
			include(CPATH.'CTRLR/rdb_traits/common.php');
			include(CPATH.'CTRLR/rdb_traits/staticmethods.php');
		}

		include(CPATH.'CTRLR/RDBQuerier.php');
		include(CPATH.'CTRLR/ResourceIndexer.php');
		

		// 传回实例本身
		return $this;
	}

	private function includeOne($filename){
		$filename = str_replace('\\', '/', $filename);
		$realpath = realpath($filename);
		if($realpath){
			include_once($realpath);
		}else{
			new StatusProcessor(1444, '', 'Could Not Find Required File : '.ResponseModel::trimServerFilename($filename), true);
		}
	}

	private function includeMultiple($names, $mainPath = '', $extensions = '.php'){
		if(is_array($names)){
			foreach ($names as $name) {
				$this->includeOne((string)$mainPath.$name.$extensions);
			}
		}elseif(is_string($names)){
			$this->includePermissionsath($mainPath->$mainPath, $extensions);
		}
	}

	private function includePermissionsath($path, $extensions){
		$dh = opendir($path);
		$pa = "/\.".$extensions."$/";
		while ($filename = readdir($dh)) {
			if ($filename != "." && $filename != "..") {
				$fullpath = $path."/".$filename;
				if (!is_dir($fullpath)) {
					$this->includePermissionsath($fullpath, $extensions);
				} else {
					if(preg_match($pa, $fullpath)){
						$this->includeOne($fullpath);
					}
				}
			}
		}
		closedir($dh);
	}

	public function includeClassOfNSMap($classname){
		$_classname = str_replace('\\', '/', $classname);
		foreach ($this->map as $set) {
			$ns = $set[0];
			$path = $set[1];
			if(@preg_match('/^'.$ns.'\//', $_classname)){
				if($ns==='Packages'){
					$filename = preg_replace('/^'.$ns.'\//', $path, $_classname).'/package.nip';
				}else{
					$filename = preg_replace('/^'.$ns.'\//', $path, $_classname).'.php';
				}
				if(is_file($filename)){
					$this->includeOne($filename);
					if((class_exists($classname)||trait_exists($classname)||interface_exists($classname)||function_exists($classname))==false){
						$sp = new StatusProcessor(1422, '', 'Class '.$classname.' not found in file '.$filename.', this file must be tampered.');
						$tracedata = $sp->getTrace();
						$sp->write(isset($tracedata[1]['file']) ? $tracedata[1]['file'] : $tracedata[1]['function']);
						return $sp->respond(StatusProcessor::LOG);
					}
				}else{
					// var_dump($this->map);
					// exit;
					$sp = new StatusProcessor(1444.1, 'File Not Found', 'File of class '.$classname.' ( ' .$filename. ' ) not found, files on your yangram may be tampered, or just a spelling mistake.');
					$tracedata = $sp->getTrace();
					$sp->write(isset($tracedata[1]['file']) ? $tracedata[1]['file'] : $tracedata[1]['function']);
					return $sp->respond(StatusProcessor::LOG);
				}
				return;
			}
		}
	}

	public static function execute($names, $mainPath = '', $extensions = '.php'){
		if(is_string($names)&&($names!=='*')){
			self::$instance->includeOne($mainPath.$names.$extensions);
		}else{
			self::$instance->includeMultiple($names, $mainPath, $extensions);
		}
		return true;
	}
}

// 载入必要文件
include(CPATH.'CACHE/Session.php');
include(CPATH.'CTRLR/StatusProcessor.php');

// 为常用核心类设置全局别名
class_alias('Tangram\IDEA', 'IDEA');
class_alias('Tangram\CTRLR\StatusProcessor', 'Status');