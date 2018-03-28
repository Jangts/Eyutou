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
final class Filesys extends _abstract {
    /**
     * 清空文件夹
     * 
     * @access public
     * @final
     * @static
     * @param string $path 文件夹路径
     * @param string $extn 后缀名，因时间原因，暂未实现
     * @return bool
     */
    public static function clearPath($path, $extn = '') {
	    $dh = opendir($path);
	    while ($file = readdir($dh)) {
	        if ($file != "." && $file != "..") {
	            $fullpath = $path."/".$file;
	            if (!is_dir($fullpath)) {
	                \unlink($fullpath);
	            } else {
	                self::clearPath($fullpath);
	            }
	        }
	    }
        closedir($dh);
        return true;
	}

    /**
     * 写入内容到文件
     * 
     * @access public
     * @final
     * @static
     * @param string $filename 文件名
     * @param string $txt 文本内容
     * @return bool
     */
    public static function writeContent($filename, $txt){
        $path = dirname($filename);
		if (!file_exists($path)){
			mkdir($path, 0777, true) or Status::cast("error path [$path]!");
        }
		$file = @fopen($filename, 'w') or Status::cast("Unable to open file [$filename]!");
		fwrite($file, $txt);
        fclose($file);
        return true;
    }

    /**
     * 追加信息到文件
     * 
     * @access public
     * @final
     * @static
     * @param string $filename 文件名
     * @param string $txt 文本内容
     * @return bool
     */
    public static function addContent($filename, $txt){
        $path = dirname($filename);
		if (!file_exists($path)){
			mkdir($path, 0777, true) or Status::cast("error path [$path]!");
        }
        $file = @fopen($filename, 'a') or Status::cast("Unable to open file [$filename]!");
		fwrite($file, $txt);
        fclose($file);
        return true;
    }

    /**
     * 读取文件内容
     * 
     * @access public
     * @final
     * @static
     * @param string $filename 文件名
     * @return string|bool
     */
	public static function getContent($filename){
    	if(is_file($filename)){
            return file_get_contents($filename);
    	}
    	return false;
    }

    /**
     * 获取文件中的列表
     * 
     * @access public
     * @static
	 * @param string $filename 文件名
	 * @return int
     */
	final private static function getListByFilename($filename){
        if ($txt = @self::getContent($filename)) {
            $json = '[' . $txt . ']';
            return json_decode($json, true);
        }else{
            return false;
        }
    }
    
    /**
     * 获取文件大小
     * 
     * @access public
     * @final
     * @static
     * @param string $filename 文件名
     * @return int
     */
    public static function getSize($filename){
    	if(is_file($filename)){
            return filesize($filename);
    	}
    	return 0;
    }

    /**
     * 获取文件夹大小
     * 
     * @access public
     * @final
     * @static
     * @param string $path 文件夹路径
     * @return int
     */
    public static function getPathSize($path) {
        $handle = opendir($path);
        $sizeResult = 0;
        while (false !== ($FolderOrFile = readdir($handle))) {
            if ($FolderOrFile != "." && $FolderOrFile != "..") {
                if (is_dir("$path/$FolderOrFile")) {
                    $sizeResult += self::getDirSize("$path/$FolderOrFile");
                } else {
                    $sizeResult += filesize("$path/$FolderOrFile");
                }
            }
        }
        closedir($handle);
        return $sizeResult;
    }

    // 实例缓存区
    protected static $instances = [];

    /**
     * 获取存取器实例(缓存介质)
     * 
     * @access public
     * @final
     * @static
     * @param array $options 配置项
     * @param bool $keepAlive 是否使用长连接
     * @return int
     */
	public static function instance(array $options, $keepAlive = false){
        if(isset($options['path'])){
            $path = $options['path'];
        }else{
            new Status(1415, '', 'Must hava a pathname for Filesys Storage', true);
        }
        if(isset($options['filetype'])){
            $extn = '.'.strval($options['filetype']);
        }else{
            $extn = '.ni';
        }
        if(isset($options['expiration'])){
            $expiration = intval($options['expiration']);
        }else{
            $expiration = 0;
        }
        if(isset(self::$instances[$path.':'.$extn])){
            return self::$instances[$path.':'.$extn];
        }
        return self::$instances[$path.':'.$extn] = new self($path, $extn, $expiration);
	}
    
    protected
	/**
	 * 配置变量解释
	 * @var string $path 缓存目录地址
	**/
	$path;

    /**
     * 存取器实例(缓存介质)构造函数
     * 
     * @param string $path 缓存目录地址
	 * @param string $extn 文件名后缀
	 * @param int $expiration 生存时间
	 * @return 构造函数无返回值
     */
    private function __construct($path, $extn, $expiration){
        if(stripos($path, __ROOT__)===0){
            $this->path = str_replace('\\', '/', $path);
        }else{
            new Status(1414, '', 'Error Pathname For Filesys Storage', true);
        }
        $this->extn = $extn;
        $this->expiration = $expiration;
    }

    /**
	 * 获取缓存文件的文件名
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return string|int|bool
	 */
	public function filename($index){
		return $this->path.$this->namespace.$index.$this->extn;
    }

    /**
	 * 检查项目是否存在
	 * 
	 * @access public
	 * @param string|int $index 项目索引
	 * @return bool
	 */
	public function exists($index){
		$filename = $this->filename($index);
		return  is_file($filename);
    }
    
    /**
	 * 提取项目信息
	 * 
	 * @access public
	 * @param string $index 项目索引
	 * @return string|bool
	 */
	public function read($index){
        $filename = $this->filename($index);
        if($expiration = intval($this->expiration)){
            if($this->time()>0){
		        if(is_file($filename)){
			        return self::getContent($filename);
                }
            }
        }else{
            if(is_file($filename)){
			    return self::getContent($filename);
            }
        }
		return false;
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
		$filename = $this->filename($index);
		return self::writeContent($filename, $value);
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
        $filename = $this->filename($index);
        if (is_file($filename)) {
            $time = filemtime($filename) + $this->expiration - time();
            if($time > 0){
                return $time;
            }
            \unlink($filename);
            return -1;
        }
        return -2;
    }
    
    /**
     * 获取文件或文件夹大小
     * 
     * @access public
	 * @param string|null $index 项目索引
	 * @return int
     */
    public function size($index = NULL){
		if($index){
			$filename = $this->filename($index);
			return self::getSize($filename);
		}
        return self::getPathSize($this->path);
    }
    
    /**
	 * 删除项目
	 * 
	 * @access public
	 * @param string $index 项目索引
	 * @return bool
	 */
    public function delete($index){
        $filename = $this->filename($index);
        if (is_file($filename)) {
            return \unlink($filename);
        }
        return false;
    }

    /**
     * 将内容推到列表中
	 * 返回数组长度
     * 
     * @access public
	 * @param string $index 项目索引
     * @param string|numrice $value 要写入的值
     * @param bool $add2top 是否追加到文件头部
	 * @return int|bool
     */
	final public function push($index, $value, $add2top = true){
        if(is_scalar($value)){
            if(is_string($value)){
                $value = '"' . addslashes($value) . '"';
            }
        }else{
            return false;
        }
        $filename = $this->filename($index);
        if (is_file($filename)) {
            if($array = self::getListByFilename($filename)){
                if($add2top){
                    self::writeContent($filename, $value . ',' . PHP_EOL . file_get_contents($filename));
                }else{
                    self::addContent($filename, ',' . PHP_EOL . $value);
                }
                return count($array) + 1;
            }else{
                return false;
            }
        }else{
            self::writeContent($filename, $value);
            return 1;
        }
    }

    /**
     * 返回列表长度
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return int|bool
     */
	public function listsize($index){
		$filename = $this->filename($index);
        if (is_file($filename)) {
            if($array=self::getListByFilename($filename)){
               return count($array);
            }
        }else{
            return 0;
        }
	}
    
    /**
     * 返回并移除列表中的第一个元素
     * 
     * @access public
	 * @param string $index 项目索引
	 * @return mixed
     */
	public function pop($index){
		$filename = $this->filename($index);
        if (is_file($filename)) {
            if($array=self::getListByFilename($filename)){
                $ele = array_shift($array);
                $txt = preg_replace('/(^\[|\]$)/', '', json_encode($array));
                self::writeContent($filename, $txt);
                return $ele;
            }else{
                return false;
            }
        }else{
            return false;
        }
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
		$filename = $this->filename($index);
        if (is_file($filename)) {
            if($array=self::getListByFilename($filename)){
                $length = count($array);
                // 校准起点
                if($start>=$length){
                    $start = $length - 1;
                }elseif($start<0){
                    $start = $start + $length;
                }
                
                if($count<0){
                    $count = abs($count);

                    // 重置并校准起点，并校准长度
                    $start = $start - $count;
                    if($start<0){
                        $count = $count + $start + 1;
                        $start = 0;
                    }
                    return array_reverse(array_slice($array, $start, $count));
                }
                // 再次校准起点
                if($start<0){
                    $start = 0;
                }
                return array_slice($array, $start, $count);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    /**
	 * 清空仓库
	 * 
	 * @access public
	 * @return bool
	 */
    public function clean(){
        if(is_dir($this->path)){
            self::clearPath($this->path);
            return true;
        }
        return false;
	}
}