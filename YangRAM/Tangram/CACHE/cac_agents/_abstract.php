<?php
namespace Tangram\CACHE\cac_agents;

// 引入相关命名空间，以简化书写
use Status;

/**
 * @class Tangram\CACHE\cac_agents\_abstract
 * General Data Storage Driver
 * 存取器(缓存介质)
 * 
 * @abstract
 * @author      Jangts
 * @version     5.0.0
**/
abstract class _abstract {
    // 实例缓存区
    protected static $instances = [];

    /**
     * 获取存取器实例(缓存介质)
     * 
     * @access public
     * @static
     * @param array $options 配置项
	 * @param bool $keepAlive 是否使用长连接
     * @return int
     */
	public static function instance(array $options, $keepAlive){}
    
    protected
	/**
	 * @var string $namespace 命名空间
     * @var int $expiration 生存时间
	**/
	$namespace = '',
    $expiration = 0;

    /**
	 * 配置命名空间
	 * 
	 * @access public
	 * @param string interface 命名空间
	 * @return object
	 */
	public function setNameSpace($namespace){
		$this->namespace = strval($namespace);
		return $this;
    }

    /**
	 * 检查项目是否存在
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return bool
	 */
	public function exists($index){}
    
    /**
	 * 提取项目信息
	 * 
	 * @access public
	 * @param string $index 项目索引
	 * @return string|bool
	 */
	public function read($index){}
    
     /**
	 * 写入项目信息
	 * 
	 * @access public
	 * @param string $index 项目索引
     * @param string $value 要写入的值
	 * @param int $expiration 生命周期
	 * @return bool
	 */
    public function write($index, $value, $expiration){}
    
    /**
     * 获取项目剩余有效期
	 * 项目不存在返回-2
	 * 项目已过期返回-1
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return int|bool
     */
    public function time($index){
		return false;
	}
    
    /**
	 * 删除项目
	 * 
	 * @access public
	 * @param string $index 项目索引
	 * @return bool
	 */
	public function delete($index){}
		
	/**
     * 将内容推到列表中
	 * 返回数组长度
     * 
     * @access public
	 * @param string $index 项目索引
     * @param string $value 要写入的值
	 * @param bool $add2top 是否追加到文件头部或Redis列表头部
	 * @return int|bool
     */
	public function push($index, $value, $add2top = true){
		return false;
	}

	/**
     * 返回列表长度
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return int|bool
     */
	public function listsize($index){
		return false;
	}

	/**
     * 返回并移除列表中的第一个元素
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return mixed
     */
	public function pop($index){
		return false;
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
		return false;
	}

    /**
	 * 清空仓库
	 * 
	 * @access public
	 * @return bool
	 */
	public function clean(){}
		
	/**
	 * 销毁链接
	 * 
	 * @access public
	 * @return bool
	 */
    public function closeConn(){
		return false;
	}
}