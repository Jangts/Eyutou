<?php
// 核心缓存模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\CACHE;

// 引入相关命名空间，以简化书写
use Status;

/**
 * @class Tangram\CACHE\DataStorage
 * General Data Storage
 * 一般数据存储仓
 * 
 * @author      Jangts
 * @version     5.0.0
**/
class DataStorage {
	use \Tangram\MODEL\traits\formatting;

	const
	NAMESPACE_REGULAR = '/^[\w\.\@\-\/]+$/',
	INDEX_REGULAR = '/^[\w\.\@\#\-]+$/',

	STR = 0,	// 字串格式代号速记常量
	NUM = 1,	// 字串格式代号速记常量
    SER = 2,	// PHP序列化文本格式代号速记常量
	JSN = 3,	// JSON文本格式代号速记常量
	
	USE_FILES = 0,		// 使用fs进行缓存
	USE_MEMORY = 1,		// 使用内存缓存，自动判断使用redis、memcache或memcached
	USE_REDIS = 2,		// 使用redis缓存
	USE_MEMCA = 3;		// 使用memcache或memcached缓存

	protected static
	// 是否已初始化
	$initialized = false,
	// 链接配置表
	$redis_conns = [],
	$memca_conns = [];

	/**
     * 数据库链接相关类的通用初始化方法
     * 
	 * @access public
     * @static
     * @param array $redis_conns	拷贝一份Redis链接配置表
     * @param array $memca_conns	拷贝一份Memcache链接配置表
     * @return bool
    **/
    public static function initialize(array $redis_conns, array $memca_conns){
		if(self::$initialized==false){
			self::$redis_conns = $redis_conns;
            self::$memca_conns = $memca_conns;
			self::$initialized = true;
            return true;
		}
        return false;
	}

	protected
	/**
	 * 配置变量解释
	 * @var int $agent_type 存取驱动器实例(缓存介质)类型，建议使用速记常量
	 * @var object  $agent 存取驱动器实例(缓存介质)
	 * @var int $encodedMode 数据格式，支持普通字窜、数字、PHP序列化字串、JSON字串，建议使用速记常量
	 * @var bool $isArray 是否数组，使用JSON格式存储的数据时生效
	 * @var null|bool $emptyEnable 允许清空
	**/
	$agent_type = NULL,
	$agent,
	$namespace = '',
	$encodedMode = self::STR,
	$isArray = true,
	$emptyEnable = true;

	/**
	 * 创建存取驱动器实例(缓存介质)
	 * 
	 * @access protected
	 * @param int $agent_type 存取驱动器类型
	 * @param array $options 配置项
	 * @param bool $keepAlive 是否使用长连接
	 * @return object
	 */
	final protected function create_agent($agent_type, $options, $keepAlive = false){
		$this->agent_type = $agent_type;
		switch($agent_type){
			case self::USE_FILES:
			return cac_agents\Filesys::instance($options);

			case self::USE_REDIS:
			return cac_agents\Redis::instance($options, $keepAlive); 

			case self::USE_MEMORY:
			if(extension_loaded('redis')){
				$this->agent_type = self::USE_REDIS;
				return cac_agents\Redis::instance($options, $keepAlive); 
			}
			$this->agent_type = self::USE_MEMCA;

			case self::USE_MEMCA:
			return cac_agents\Memcache::instance($options, $keepAlive);
		}
		new Status(1415.3, '', 'Unknow Storage agent Type', true);
	}

	/**
	 * 加密索引
	 * 
	 * @access protected
	 * @param int $agent_type 存取驱动器类型
	 * @param array $options 配置项
	 * @return object
	 */
	final protected function key($index){
		$array = preg_split('/[\\\\\/]+/', $index);
		foreach ($array as $key => $value) {
			if(preg_match(self::INDEX_REGULAR, $value)){
				$array[$key] = $value;
			}else{
				$array[$key] = hash('md4', $value);
			}
		}
		
		return implode('/', $array);
	}

	/**
	 * 编码内容
	 * 
	 * @access protected
	 * @param int $agent_type 存取驱动器类型
	 * @param array $options 配置项
	 * @return object
	 */
	final protected function encode($data){
		switch ($this->encodedMode) {
            case self::SER:
			return serialize($data);
			case self::JSN:
			return json_encode($data);
			case self::NUM:
			if(is_numeric($data)){
                return strval($data);
			}
			default:
            if(is_string($data)||is_numeric($data)){
				// $this->encodedMode = 0;
                return $data;
			}
            return serialize($data);
		}
	}

	/**
	 * 解码内容
	 * 
	 * @access protected
	 * @param int $agent_type 存取驱动器类型
	 * @param array $options 配置项
	 * @return object
	 */
	final protected function decode($text){
		switch ($this->encodedMode) {
			case self::NUM:
			if(is_numeric($text)){
				return floatval($text);
			}
            case self::STR:
			return $text;
			case self::JSN:
			return json_decode($text, $this->isArray);
			default:
			if($data = unserialize($text)){
				return $data;
			}
			return $text;
		}
        return false;
	}
	
	/**
	 * 存储仓构造函数
	 * 
	 * @access public
	 * @param array|string|int $options 实例配置项
	 * @param int $encodedMode 数据格式，支持普通字窜、数字、PHP序列化字串、JSON字串，建议使用速记常量
	 * @param bool $isArray 是否数组，使用JSON格式存储的数据时生效
	 * @return 构造函数无返回值
	 */
	public function __construct($options, $encodedMode = self::JSN, $isArray = true){
		// 分析$options
		switch(gettype($options)){
			case 'integer' :
			if(($this->agent_type===NULL||$this->agent_type===self::USE_REDIS)&&isset(self::$redis_conns[$options])){
				$this->agent = $this->create_agent(self::USE_REDIS, self::$redis_conns[$options], true);
			}elseif(($this->agent_type===NULL||$this->agent_type===self::USE_MEMCA)&&isset(self::$memca_conns[$options])){
				$this->agent = $this->create_agent(self::USE_MEMCA, self::$memca_conns[$options], true);
			}else{
				new Status(1415.1, '', 'Unknow Storage Options', true);
			}
			break;

			case 'string' :
			if((stripos($options, __ROOT__)===0)&&!$this->agent_type){
				$this->agent = $this->create_agent(self::USE_FILES, [
					'path'			=>	$options,
					'filetype'		=>	'ni',
					'expiration'	=>	0
				]);
			}else{
				new Status(1415.1, '', 'Unknow Storage Options', true);
			}
			break;

			case 'array' :
			if(isset($options['htype'])&&is_numeric($options['htype'])&&$options['htype']>=0&&$options['htype']<=4){
				if($this->agent_type===NULL||$this->agent_type==$options['htype']){
					$this->agent = $this->create_agent($options['htype'], $options);
				}else{
					new Status(1415.2, '', 'Cannot Redefine Agent Type '.$options['htype'], true);
				}
			}else{
				if(is_numeric($this->agent_type)&&$this->agent_type>=0&&$this->agent_type<=4){
					$this->agent = $this->create_agent($this->agent_type, $options);
				}elseif(isset($options['path'])){
					$this->agent = $this->create_agent(self::USE_FILES, $options);
				}else{
					new Status(1415.0, 'Parameters Error', true);
				}
			}
			break;

			default:
			new Status(1415.1, '', 'Unknow Storage Options', true);
		}
		if($this->namespace){
			$this->agent->setNameSpace($this->namespace);
		}
		$this->encodedMode = $encodedMode;
		$this->isArray = !!$isArray;
	}

	/**
	 * 配置命名空间
	 * 
	 * @access public
	 * @param string $namespace 命名空间
	 * @return object
	 */
	public function setNameSpace($namespace = ''){
		$namespace = str_replace('\\', '/', $namespace);
		if(preg_match(self::NAMESPACE_REGULAR, $namespace)){
			$this->agent->setNameSpace($this->namespace = $namespace);
		}else{
			$this->agent->setNameSpace($this->namespace = hash('md4', $namespace).'/');
		}
		return $this;
	}

	/**
	 * 返回当前命名空间
	 * 
	 * @access public
	 * @return string
	 */
	final public function getNamespace(){
		return $this->namespace;
	}
	
	/**
	 * 返回当前存取驱动器实例(缓存介质)类型
	 * 
	 * @access public
	 * @return string
	 */
	final public function agent_type(){
		return $this->agent_type;
	}

	/**
	 * 返回当前编码格式
	 * 
	 * @access public
	 * @return int
	 */
	final public function encodedMode(){
		return $this->encodedMode;
	}

	/**
	 * 当前是否数组格式
	 * 
	 * @access public
	 * @return bool
	 */
    final public function isArray(){
		return $this->isArray;
	}

	/**
	 * 检查项目是否存在
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return bool
	 */
	final public function exists($index){
		$this->agent->exists($this->key($index));
		return $this;
	}

	/**
	 * 提取项目信息
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return mixed
	 */
	final public function take($index){
		return $this->decode($this->agent->read($this->key($index)));
	}

	/**
	 * 提取项目信息
	 * $this->take($index)的别名
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return mixed
	 */
	final public function read($index){
		return $this->take($index);
	}

	public function toString($index){
    	return $this->agent->read($this->key($index));
	}

	public function getArrayCopy($index){
        return self::arrayEncode($this->take($index));
    }

    public function json_encode($index){
		if($this->encodedMode==='json'){
			return $this->toString($index);
		}
		return self::jsonEncode($this->take($index));
    }

	/**
	 * 写入项目信息
	 * 
	 * @access public
	 * @param string|int $index 项目索引
     * @param string $value 要写入的值
	 * @return object
	 */
	final public function store($index, $value = false){
		$index = $this->key($index);
		if(is_bool($value)){
			if($value===false){
				$this->agent->delete($index);
			}
			// 已经忘了为什么不能存储TRUE了
		}else{
			$this->agent->write($index, $this->encode($value));
		}
		return $this;
	}

	/**
	 * 写入项目信息
	 * $this->store($index, $value)的别名
	 * 
	 * @access public
	 * @param string|int $index 项目索引
     * @param string $value 要写入的值
	 * @return object
	 */
	final public function write($index, $value, $expiration = 0){
		return $this->store($index, $value, $expiration);
	}

	/**
     * 获取项目剩余有效期
	 * 项目不存在返回-2
	 * 项目已过期返回-1
	 * Memcache下返回false
     * 
     * @access public
	 * @param string|int $index 项目索引
	 * @return int
     */
	final public function time($index){
		return $this->agent->time($this->key($index));
	}

	/**
	 * 清空仓库
	 * 
	 * @access public
	 * @return bool
	 */
	public function clean(){
        if($this->emptyEnable){
            return $this->agent->clean();
        }
        return false;
	}

	/**
     * 获取文件或文件夹大小
	 * 仅$this->agent->type===self::USE_FILES时有效
     * 
     * @access public
	 * @param string|null $index 项目索引
	 * @return int
     */
	final public function size($index = NULL){
		if($this->agent->type===self::USE_FILES){
			return $this->agent->size($this->key($index));
		}
        return false;
	}
	
	/**
     * 获取文件名
	 * 仅$this->agent->type===self::USE_FILES时有效
     * 
     * @access public
	 * @param string|int $index 项目索引
	 * @return int
     */
	final public function getFile($index){
		if($this->agent->type===self::USE_FILES){
			return $this->agent->filename($this->key($index));
		}
        return false;
	}

	/**
     * 将内容推到列表中
	 * 返回数组长度
     * 
     * @access public
	 * @param string|int $index 项目索引
     * @param string $value 要写入的值
	 * @param bool $add2top 是否追加到文件头部或Redis列表头部
	 * @return int|bool
     */
	final public function push($index, $value, $add2top = true){
		// 列表型记录，仅支持FILES(JSON加行)和REDIS(lpush方法)
		return $this->agent->push($this->key($index), $value, $add2top);
	}

	/**
     * 返回列表长度
     * 
     * @access public
	 * @param string|int $index 项目索引
	 * @return int|bool
     */
	public function listsize($index){
		return $this->agent->listsize($this->key($index));
	}

	/**
     * 返回并移除列表中的第一个元素
     * 
     * @access public
	 * @param string|int $index 项目索引
	 * @return mixed
     */
	public function pop($index){
		return $this->agent->pop($this->key($index));
	}

	/**
     * 读取列表元素中的元素
     * 
     * @access public
	 * @param string|int $index 项目索引
     * @param int|null $count 读取长度，如果该值设置为正数，则返回该数量的元素；如果该值设置为负数，则反向取值返回该数量的元素。
	 * @param int $start 起始位置，如果该值设置为正数，则从前往后开始取，0 意味着第一个元素；如果该值设置为负数，则从后向前取 start 绝对值，-2 意味着从数组的倒数第二个元素开始。
	 * @return array|bool
     */
	final public function getRows($index, $count = NULL, $start = 0){
		return $this->agent->getRange($this->key($index), $count, $start);
	}
}