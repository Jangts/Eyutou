<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

// 引入相关命名空间，以简化书写
use Storage;
use DBQ;

/**
 * @class Tangram\MODEL\RouteCollection
 * Custom Routemap Model
 * 自定义路由表模型
 * 根据主机名和路由表ID路由表及其缓存，以供统一资源索引器（$NEWIDEA->RI）进行分析
 * 
 * 以下各键并非路由表本身的属性，而是单行路由规则的属性
 * @var string HANDLER			应用ID或路由表ID，取决于,
 * @var string DIRNAME			$dirname,
 * @var int DEPTH				$depth,
 * @var int ROUTE				$row['ROUTE'],
 * @var string DEFAULTS			$row['DEFAULTS'],
 * @var int STATE				1
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class RouteCollection implements interfaces\collection {
	protected static
	$domains = NULL,
    $staticFileStorage = NULL,
	$querier = NULL;
	
	private $storage,
    $xml = false,
    $json = false;

	/**
	 * 初始化Tangram\MODEL\RouteCollection类
	 * 主要是设置路由表缓存的相关设置
	 * 
	 * @access public
	 * @static
	 * @return bool
	**/
    public static function initialize(){
        self::$staticFileStorage = new Storage([
			'path'		=>	DPATH_RMAP,
			'filetype'	=>	'json'
		], Storage::JSN, true, true);
		self::$staticFileStorage->setNameSpace(str_replace(':', '\\', HOST).'/r');
		return true;
	}
	
	/**
	 * 清空当前主机名下路由表缓存
	 * 
	 * @access public
	 * @static
	 * @return bool
	**/
	public static function emptyCache(){
		self::$staticFileStorage->clean();
		return true;
	}

	/**
	 * 初始化RDB链接
	 * 
	 * @access private
	 * @static
	 * @return bool
	**/
    private static function initRDBConnection(){
        self::$querier = new DBQ;
		self::$querier->using(DB_REG.'appdirs');
		return true;
	}
	

	/**  
	 * 统一资源索引器构造函数，仅一次有效
     * 将构造函数保护起来以保证其实例的单一性
	 * 
	 * @access public
	 * @param int $mapid
	 * @return 构造函数无返回值
	**/ 
	public function __construct($mapid, $defhost, $superhost){
		// 调试模式下，将关闭缓存功能
		if(_USE_DEBUG_MODE_===0){
            $this->storage = self::$staticFileStorage->take('map_'.$mapid);
        }else{
			$this->storage = [];
            $this->collectFromDatabase($mapid, $defhost, $superhost);
		}
	}

	/**  
	 * 更新路由表
	 * 
	 * @access public
	 * @param int $mapid 		路由表id
	 * @param string $defhost	默认主机名
	 * @param string $superhost	上级域名
	 * @return 构造函数无返回值
	**/ 
	public function collectFromDatabase($mapid, $defhost, $superhost){
		// 链接数据库
		self::$querier != NULL or self::initRDBConnection();

		// 设置搜索条件
		self::$querier->where('MAP_ID', $mapid)->where('SK_STATE', [1, 2]);
		if($defhost===HOST){
			if($superhost){
				self::$querier->where('DOMAIN', ['<DEF>', '<ANY>', HOST, '<SUB>'.$superhost])->select();
			}else{
				self::$querier->where('DOMAIN', ['<DEF>', '<ANY>', HOST])->select();
			}
		}else{
			if($superhost){
				self::$querier->where('DOMAIN', ['<ANY>', HOST, '<SUB>'.$superhost])->select();
			}else{
				self::$querier->where('DOMAIN', ['<ANY>', HOST])->select();
			}
		}

		// 筛选排序
		$result = self::$querier->orderby('DIR_NAME', true)->select();

		// 遍历数据
		if($pdos = $result->getIterator()){
            while($row = $pdos->fetch(\PDO::FETCH_ASSOC)){
				$dirname = preg_replace('/(^\/|\/$)/', '', preg_replace('/[\\\\\/]+/', '/', $row['DIR_NAME']));
				$depth = count(explode('/', $dirname));
				$dirname = '/' . $dirname;
				if($row['SK_STATE']===2){
					$this->append([
						'HANDLER'	=>	$row['HANDLER'],
						'DIRNAME'	=>	$dirname,
						'STATE'		=>	2
					]);
				}else{
					$this->append([
						'HANDLER'	=>	$row['HANDLER'],
						'DIRNAME'	=>	$dirname,
						'DEPTH'		=>	$depth,
						'ROUTE'		=>	$row['ROUTE'],
						'DEFAULTS'	=>	($defaults = json_decode($row['DEFAULTS'], true)) ? $defaults : [],
						'STATE'		=>	1
					]);
				}
			}
		}
		if(_DEFAULT_APP_){
			$this->append([
				'HANDLER'	=>	_DEFAULT_APP_,
				'DIRNAME'	=>	'',
				'STATE'		=>	3
			]);
		}
		
		if(_USE_DEBUG_MODE_){
			self::$staticFileStorage->clean();
		}else{
			// 缓存检索到的数据
        	self::$staticFileStorage->store('map_'.$mapid, $this->storage);
		}
		return $this;
	}    
	
	/**  
	 * 匹配路由
	 * 
	 * @access public
	 * @param string $pathname	当前路径
	 * @return array
	**/ 
	public function match($pathname){
		$pathname = $pathname . '/';
		foreach($this->storage as $item){
			$dirname = $item['DIRNAME'] . '/';
		    if(stripos($pathname, $dirname)===0){
                return $item;
            }
		}
		return [
			'STATE'	=>	0
		];
	}

	public function append($value){
		$this->storage[] = $value;
        return $this;
	}
	
	public function item($index){
		if(isset($this->storage[$index])){
			return $this->storage[$index];
		}
        return NULL;
    }

    public function count(){
        return count($this->storage);
    }

    public function getArrayCopy(){
        return $this->storage;
    }

    public function getIterator() {
        return new \ArrayIterator($this->storage);  
    }

    public function offsetExists($index){
        return isset($this->storage[$index]);
    }
    
    public function offsetSet($index, $value){
        return false;
    }
    
    public function offsetGet($index){
		return $this->item($index);
    }
    
    public function offsetUnset($index){
        return false;
    }

    public function json_encode(){
        if($this->json===false){
            $this->json = json_encode($this->storage);
        }
        return $this->json;
    }

    public function xml_encode($root_tag = 'root', $item_tag = 'row', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml===false){
            $dom = new \DomDocument($version,  $encoding);
            $xml = '<routes>';
            $xml .= self::xmlEncode($this->storage, 'route');
    		$xml .= '</routes>';
    		$dom->loadXml($xml);
            $this->xml = $dom->saveXML();
        }
        return $this->xml;
    }
}
