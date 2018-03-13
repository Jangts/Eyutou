<?php
namespace Tangram\CACHE\cac_agents;

// 引入相关命名空间，以简化书写
use Status;
use Tangram\CACHE\RedisExtended;

/**
 * @class Tangram\CACHE\cac_agents\Filesys
 * General Data Filesys Storage
 * 存取驱动器(缓存介质)
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class Redis extends _abstract {
	protected static
	// 实例缓存区
	$instances = [];

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
            new Status(1415, '', 'Must hava a hostname for Redis Storage', true);
        }
        if(isset($options['port'])){
            $port = strval($options['port']);
        }else{
            $port = 6379;
		}
		if(isset($options['password'])){
            $password = intval($options['password']);
        }else{
            $password = NULL;
		}
		if(isset($options['dbname'])){
            $dbname = intval($options['dbname']);
        }else{
            $dbname = 7;
        }
        if(isset($options['timeout'])){
            $timeout = intval($options['timeout']);
        }else{
            $timeout = 0;
		}
		if(isset($options['expiration'])){
            $expiration = intval($options['expiration']);
        }else{
            $expiration = 0;
		}
        if(isset(self::$instances[$host.':'.$port])){
            return self::$instances[$host.':'.$port];
        }
        return self::$instances[$host.':'.$port] = new self($host, $port, $dbname, $password, $timeout, $keepAlive, $expiration);
	}
    
    protected
	/**
	 * @var object(Redis) $conn redis链接实例
    **/
	$conn;

    /**
     * 存取驱动器实例(缓存介质)构造函数
     * 
     * @param string $host 服务器主机地址
	 * @param string $port 端口号
	 * @param int $dbname 数据库名，0-15，YangRAM默认使用7号库
	 * @param int $timeout 超时时间
	 * @param bool $keepAlive 是否使用长连接
	 * @param int $expiration 生存时间
	 * @return 构造函数无返回值
     */
    private function __construct($host, $port, $dbname = 7, $password = NULL, $timeout = 0, $keepAlive = false, $expiration = 0){
		if(extension_loaded('redis')){
			include(CPATH.'CACHE/RedisExtended.php');
			$conn = new RedisExtended;
			if($keepAlive){
				$resilt = $conn->pconnect($host, $port, $timeout);
			}else{
				$resilt = $conn->connect($host, $port, $timeout);
			}
			if(!$resilt){
				new Status(1417, 'Unable To Connect To The Redis Server', 'Host [' . $host . '] or port [' . $port . '] Error', true);
			}
		}else{
            new Status(1501, '', 'Extension "redis" not loaded', true);
		}
		if($password){
			$redis->auth($password); 
		}
		$conn->select($dbname);
		$this->conn = $conn;
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
		return $this->conn->exists($this->key($index));
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
		if($expiration){
			if($this->conn->exists($key)){
				$this->conn->delete($key);
			}
			return $this->conn->setex($key, $expiration, $value);
		}
		return $this->conn->set($key, $value);
	}
    
    /**
     * 获取项目剩余有效期
	 * 项目不存在返回-2
	 * 项目已过期返回-1
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return int
     */
    public function time($index){
		return $this->conn->ttl($this->key($index));
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
     * 将内容推到列表中
	 * 返回数组长度
     * 
     * @access public
	 * @param string $index 项目索引
     * @param string $value 要写入的值
	 * @param bool $add2top 是否追加到文件头部或Redis列表头部
	 * @return int
     */
	final public function push($index, $value, $add2top = true){
		if($add2top){
			return $this->conn->lpush($this->key($index), $value);
		}
		return $this->conn->rpush($this->key($index), $value);
	}

	/**
     * 返回列表长度
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return int|bool
     */
	public function listsize($index){
		return $this->conn->lsize($this->key($index));
	}
    
    /**
     * 返回并移除列表中的第一个元素
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return mixed
     */
	public function pop($index){
		return $this->conn->lpop($this->key($index));
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
	public function getRange($index, $count = NULL, $start = 0){
		if($count===0){
			return [];
		}
		$start = intval($start);
		$conn = $this->conn;
		$key = $this->key($index);
		if($count===1){
			return [$conn->lget($key, $start)];
		}
		$length = $conn->lsize($key);
		if($count===NULL){
			// 如果$count为null，那么终止坐标就为-1
			$end = $length -1;
		}else{
            // 校准起点
            if($start>=$length){
                $start = $length - 1;
            }elseif($start<0){
				$start = $start + count($array);
				if($start<0){
                    $start = 0;
                }
			}
			if($count>0){
				// 如果$count>0
				// 终止坐标即起始坐标加$count再减1
				$end = $start + $count - 1;
			}else{
				$end = $start + $count + 1;
			}
		}
		if($start>$end){
			return array_reverse($conn->lrange($key, $end, $start));
		}
		return $conn->lrange($key, $start, $end);
    }

    /**
	 * 清空仓库
	 * 
	 * @access public
	 * @return bool
	 */
	public function clean(){
		return $this->conn->flushDB();
	}
		
	/**
	 * 销毁链接
	 * 
	 * @access public
	 * @return bool
	 */
    public function closeConn(){
		return $this->conn->close();
	}
}