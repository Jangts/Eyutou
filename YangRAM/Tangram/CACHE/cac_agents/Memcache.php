<?php
namespace Tangram\CACHE\cac_agents;

// 引入相关命名空间，以简化书写
use Status;

/**
 * @class Tangram\CACHE\cac_agents\Filesys
 * General Data Filesys Storage
 * 存取器(缓存介质)
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class Memcache extends _abstract {
    // 实例缓存区
    protected static $instances = [];

    /**
     * 获取存取驱动器实例(缓存介质)
     * 
     * @access public
     * @static
     * @param array $options 配置项
	 * @param bool $keepAlive 是否使用长连接
     * @return int
     */
	public static function instance(array $options, $keepAlive = false){
		if(isset($options['host'])){
            $host = $options['host'];
        }else{
            new Status(1415, '', 'Must hava a hostname for Memcache Storage', true);
        }
        if(isset($options['port'])){
            $port = strval($options['port']);
        }else{
            $port = 11211;
        }
        if(isset($options['timeout'])){
            $timeout = intval($options['timeout']);
        }else{
            $timeout = 1;
		}
		if(isset($options['expiration'])){
            $expiration = intval($options['expiration']);
        }else{
            $expiration = 0;
        }
        if(isset(self::$instances[$host.':'.$port])){
            return self::$instances[$host.':'.$port];
        }
        return self::$instances[$host.':'.$port] = new self($host, $port, $timeout, $keepAlive, $expiration);
	}
    
    protected
	/**
	 * @var object(Memcache|Memcached) $conn memcache链接实例
    **/
	$conn;

    /**
     * 存取驱动器实例(缓存介质)构造函数
     * 
     * @param string $host 服务器主机地址
	 * @param string $port 端口号
	 * @param int $expiration 超时时间
	 * @param bool $keepAlive 是否使用长连接
	 * @param int $expiration 生存时间
	 * @return 构造函数无返回值
     */
    private function __construct($host, $port, $timeout = 1, $keepAlive = false, $expiration = 0){
        if(extension_loaded('memcached')){
			$conn = new \Memcached();
			$conn->addServers([[$host, $port]]);
			$conn->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
		}elseif(extension_loaded('memcache')){
			$conn = new \Memcached();
			if($keepAlive){
				$conn->addServer($host, $port, true, 1, $timeout);
			}else{
				$conn->addServer($host, $port, false, 1, $timeout);
			}
		}else{
            new Status(1501, '', 'Extension "memcached" or "memcache" not loaded', true);
		}
		$this->expiration = $expiration;
    }

    /**
	 * 获取键名
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return string|int|bool
	 */
	public function key($index){
		return $this->namespace.':'.$index;
    }

    /**
	 * 检查项目是否存在
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return bool
	 */
	public function exists($index){
		return !!$this->read($index);
	}
    
    /**
	 * 提取项目信息
	 * 
	 * @access public
	 * @param string $index 项目索引
	 * @return string|bool
	 */
	public function read($index){
		return $this->conn->get($this->key($index));
	}
    
     /**
	 * 写入项目信息
	 * 
	 * @access public
	 * @param string $index 项目索引
     * @param string $value 要写入的值
	 * @param int $expiration 生命周期
	 * @return bool
	 */
    public function write($index, $value, $expiration = 0){
		$key = $this->key($index);
		$expiration = $expiration ? $expiration : $this->expiration;
		if($this->conn->set($key, $value, $expiration)){
			return true;
		}
		$this->conn->replace($key, $value, $expiration);
	}
    
    /**
	 * 删除项目
	 * 
	 * @access public
	 * @param string $index 项目索引
	 * @return bool
	 */
    public function delete($index){
		return $this->conn->delete($this->key($index));
	}

    /**
	 * 清空仓库
	 * 
	 * @access public
	 * @return bool
	 */
	public function clean(){
		return $this->conn->flush();
	}
		
	/**
	 * 销毁链接
	 * 
	 * @access public
	 * @return bool
	 */
    public function closeConn(){
		if(extension_loaded('memcached')){
			return false;
		}
		return $this->conn->close();
	}
}