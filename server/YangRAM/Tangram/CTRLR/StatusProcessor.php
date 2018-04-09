<?php
// 状态码处理器专用命名空间，命名空间Tangram的子空间
namespace Tangram\CTRLR;

// 引入相关命名空间，以简化书写
use Exception;
use Response;
use Request;

/**
 * @class Tangram\CTRLR\StatusProcessor
 * Status Code Processor
 * 状态码处理器
 * 类Exception的子类
 * 处理包括HTTP Status和YangRAM Status在内的各种状态的抛出和记录
 * 
 * @final
 * @author     Jangts
 * @version    5.0.0
**/
final class StatusProcessor extends Exception {
	/**
	 * 定义一些别名常量
	 * 
	 * 处理方式
	 * @const int AUTO		根据请求报头自动选择最佳格式
	 * @const int XML		以XML格式抛出状态信息
	 * @const int JSON		以JSON格式抛出状态信息
	 * @const int TXT		以带有制表符的文本格式抛出状态信息
	 * @const int PAGE		以网页形式抛出状态信息
	 * @const int XLOG		以XML格式抛出状态信息，并以带有制表符的文本格式记录运行日志
	 * @const int JLOG		以JSON格式抛出状态信息，并以带有制表符的文本格式记录运行日志
	 * @const int TLOG		以带有制表符的文本格式抛出状态信息，并以带有制表符的文本格式记录运行日志
	 * @const int LOG		仅以带有制表符的文本格式记录运行日志，不向用户发出响应
	 * 
	 * 网页模板，仅当以网页形式抛出状态信息该值有效
	 * @const int TPL					通用模板，不带特殊感情色彩蓝色模板，表情为瘪嘴
	 * @const int TPL_400				谢客模板（代码以4开头），用户发起了不被允许的请求，有轻微警告意味的黄色模板，表情为瘪嘴并伴随眼泪
	 * @const int TPL_404				缺省模板（代码404专用），资源不存在或已删除，略带歉意的灰色模板，表情为瘪嘴
	 * @const int TPL_1400				错误模板（代码以7开头，1111除外），系统发生意外，无法正常运行，略带惊讶和轻微不满意味的橙色模板，表情为中年绅士的瘪嘴
	 * @const int TPL_1401				主应用错误模板（适用于代码701至706），YangRAM系统组建发生错误，带有严重警告意味的红色模板，表情为瘪嘴
	 * @const int TPL_1111				通用模板的别名
	 * @const int TPL_1422				子应用错误模板（适用于代码708至796的状态），带有警告意味的洋红色模板，表情为瘪嘴
	 * @const int TPL_1501				高级别错误模板（适用代码797，并兼代码500至699），主机或运行环境错误，略感无奈的深灰色模板，表情为瘪嘴并伴随眼泪
	 * @const int TPL_GREEN_CHANNEL		缺省模板（代码200专用），有效的访问，默认首页专用
	 * @const int TPL_YELLOW 			谢客模板的别名
	 * @const int TPL_ASHY_CHANNEL		404缺省模板的别名
	 * @const int TPL_NACARAT			错误模板的别名
	 * @const int TPL_RED				主应用错误模板的别名
	 * @const int TPL_AZURE				通用模板的别名
	 * @const int TPL_MAGENTA			子应用错误的别名
	 * @const int TPL_SERVER_ERRORS		高级别错误的别名
	**/
	const
	AUTO		= 0,
	XML			= 1,
    JSON		= 2,
	TXT			= 3,
	PAGE		= 4,
	XLOG		= 5,
    JLOG		= 6,
	TLOG		= 7,
	PLOG		= 8,
	LOG			= 9;

	// 状态码表
	public static $codes = NULL;

	/**
	 * 初始化状态码表
	 * 
	 * @access public
	 * @static
	 * @return null
	**/
	public static function init(){
		if(self::$codes===NULL){
			if(is_file(CPATH.'CTRLR/stt_codes/provider.php')){
				self::$codes = include CPATH.'CTRLR/stt_codes/provider.php';
			}else{
				exit('Cannot Found Status Code Map');
			}
		}
	}

	/**
	 * 通用静态快捷方法
	 * 
	 * @access public
	 * @param string|int $code numeric
	 * @return null
	**/
	public static function send($code = 1111){
		new self($code, '', '', true);
	}

	/**
	 * 403专用静态快捷方法
	 * 
	 * @access public
	 * @return null
	**/
	public static function forbidden(){
		new self(403, true);
	}

	/**
	 * 404专用静态快捷方法
	 * 
	 * @access public
	 * @return null
	**/
	public static function notFound(){
		new self(404, true);
	}

	/**
	 * 404专用静态快捷方法
	 * 
	 * @access public
	 * @return null
	**/
	public static function cast($text, $code = 1111, $msg = ''){
		new self($code, $msg, $text, true);
	}

	/**  
	 * 标注化请求对象构造函数
	 * 将构造函数私有化以保证其实例的单一性
	 * 
	 * @access private
	 * @param string|int|null 	$code 		输入numeric时为状态码，输入一般字符串时为状态名称，输入布尔值时为响应指定
	 * @param string 			$msg 	输入字符串时为状态名称，输入布尔值时为响应指定
	 * @param string 			$message	输入字符串时为状态正文，输入布尔值时为响应指定
	 * @param bool				$respond	响应指定，默认为不响应
	 * @param bool				$log		写入日志指定，默认为记录
	 * @return 构造函数无返回值
    **/ 
	public function __construct($code, $msg = '', $message = '', $respond = false, $log = false){
		// 初始化默认属性
		$this->code = '1111';
		$this->intc = 1111;
		$this->alias = 'UNKNOW_STATUS';
		$this->msg = '(none)';

		// 如果传入的$code值为数或数字
		if(is_numeric($code)){
			// 则修改实例的状态码相关属性
			$this->code = (string)$code;
			$this->intc = intval($code);
			$this->alias = 'TANGRAMNI_STATUS_'.$code;

			// 如果传入的$msg为字符串格式
			if(is_string($msg)){
				// 但是其值为空白，则使用状态码表中的默认值，否则使用该值
				if(empty($msg)){
					$this->status = isset(self::$codes[$this->intc])
											? self::$codes[$this->intc]
											: self::$codes[1111];
				}else{
					$this->status = $msg;
				}

				// 如果传入的$message为字符串格式，且其值不为空，则修改$this->msg；
				// 如果为布尔值，则用其覆盖参数$respond的值，并用$respond的原值覆盖参数$log
				if(is_string($message)){
					empty($message) or ($this->msg = $message);
				}elseif(is_bool($message)){
					$log = $respond;
					$respond = $message;
				}
			}else{

				// 使用状态码表中的默认值
				$this->status = isset(self::$codes[$this->intc])
										? self::$codes[$this->intc]
										: self::$codes[1111];

				// 如果传入的$msg布尔值，则用其覆盖参数$respond的值，并用$respond的原值覆盖参数$log
				if(is_bool($msg)){
					$log = $message;
					$respond = $msg;
				}
			}
		}elseif(is_string($code)){
			$this->status = $code;
			if(is_string($msg)){
				empty($msg) or ($this->msg = $msg);
			}elseif(is_bool($msg)){
				$log = $message;
				$respond = $msg;
			}
		}elseif(is_bool($code)){
			$log = $msg;
			$respond = $code;
		}
		if($respond===true){
			if($log===true){
				$this->log();
			}
			$this->respond();
		}
	}

	/**
	 * 追加状态正文
	 * 
	 * @access public
	 * @param string $log 追加值
	 * @return null
	**/
	public function write($log){
		if(!empty($log)){
			if($this->msg==='(none)'){
				// 重置
				$this->msg = $log;
			}else{
				// 追加
				$this->msg .= "<br/>".$log;
			}
		}
	}

	/**
	 * 记录当前状态
	 * 
	 * @access public
	 * @return null
	**/
	public function log(){
		if($this->code>=300){
			// 如果状态码大于等于300，则被认为是错误日志，将记录在'errors/'文件夹
			$path = RUNPATH_LOG . 'errors/' . (defined('APPID') ? APPID : 'TANGRAM') . '/' . date('Ym') . '/';
			$text = $this->lotxt();
		}else{
			// 否则被认为是普通日志，将记录在'notes/status/' 文件夹
			$path = RUNPATH_LOG . 'notes/status/' . (defined('APPID') ? APPID : 'TANGRAM') . '/' . date('Ym') . '/';
			$text = $this->notxt();
		}

		$filename = $path.date('Ymd');
		// 检查文件夹是否存在，如果不存在，创建之
		if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
		$file = @fopen($filename, 'a') or new StatusProcessor(1411.7, 'Permission Denied', 'Unable to write run log! The current log file may be read-only.', true);
		fwrite($file, $text);
		fclose($file);
	}

	/**
	 * 生成错误文本
	 * 
	 * @access private
	 * @return string
	**/
	private function lotxt(){
		$text  = ">>>>>>\t$this->alias\t@\t" . date('Y-m-d H:i:s') . "\r\n";
		$text .= "\tMSG\t\t$this->status\r\n";
		$text .= "\tDESC\t\t$this->msg\r\n";
		$text .= "\tURL\t\t" . HOST . $_SERVER["REQUEST_URI"] . "\r\n";
		$text .= "\tFILE\t\t$this->file in line $this->line\r\n";
		$text .= "\tTRACE";

		// 获取跟踪信息，并便利之
		$tracedata = $this->getTrace();
		foreach($tracedata as $n=>$p){
			if(empty($p['file'])){
				$p['file'] = $this->file;
			}
			if($n){
				$text .= "\t\t#";
			}else{
				$text .= "\t#";
			}
			if(isset($p['class'])){
				// 类的静态方法或实例的方法
				if(isset($p['line'])){
					// 普通调用
					$text .= $n . "\t" . $p['class'] . $p['type'] . $p['function'] . '() on ' . $p['file']. ' (line ' . $p['line'] . ')' . "\r\n";
				}else{
					// 服务器软件调用
					$text .= $n . "\t" . $p['class'] . $p['type'] . $p['function'] . '() on ' . $p['file'] . ' (called by " . SRVR . ")' . "\r\n";
				}
			}elseif(isset($p['function'])){
				// 普通函数
				if(isset($p['line'])){
					// 普通调用
					$text .= $n . "\t" . $p['function'] . '() on ' . $p['file'] . ' (line ' . $p['line'] . ')' . "\r\n";
				}else{
					// 服务器软件调用
					$text .= $n . "\t" . $p['function'] . '() on ' . $p['file'] . ' (called by " . SRVR . ")' . "\r\n";
				}
			}else{
				// 表达式
				$text .= $n . "\t" . $p['file'] . ' (line ' . $p['line'] . ')' . "\r\n";
			}
		}
		$ip = Request::instance()->IP;
		$text .= "\tUSER\t\t\tfrom $ip\r\n";
		$text .= "\r\n";
		return $text;
	}

	/**
	 * 生成日志文本
	 * 
	 * @access private
	 * @return string
	**/
	private function notxt(){
		$text  = date('Y-m-d H:i:s');
		$text .= "\t MSG\t" . $this->status;
		$text .= "\tDESC\t" . $this->msg;
		$text .= "\t URL\t" . HTTP . $_SERVER["REQUEST_URI"];
		$text .= "\tFILE\t" . $this->file;
		$text .= "\tLINE\t" . $this->line;
		$text .= "\t IP \t" . Request::instance()->IP;
		return $text.PHP_EOL;
	}

	/**
	 * 响应当前状态
	 * 
	 * @access public
	 * @param int $type 响应类型代号
	 * @param string $template 响应网页模板（仅当响应类型为网页时有效）
	 * @return null
	**/
	public function respond($type = self::AUTO, $template = NULL){
		if($type>4){
			$this->log();
		}
		if(defined('_CLI_MODE_')){
			echo $this->lotxt();
			exit;
		}
		$response = Response::instance($this->code);

		// 缓存之前的输出，并设置响应头
		if(defined('_USE_DEBUG_MODE_')&&_USE_DEBUG_MODE_){
			$this->pre = ob_get_clean();
		}else{
			$this->pre = 'No Previous Output Be Cached.';
		}
		$response->setHeader('NI-Response-Text', $this->status, true);

		// 判断响应格式
		switch ($type) {
			case 1:
			case 5:
			$response->MIME = Response::XML;
			$data = $this->getData();
			return $response->send($this->xml_encode($data));			

			case 3:
			case 7:
			$response->MIME = Response::TXT;
			return $response->send('#'.$this->code.' YangRAM Status "' . $this->status . '" in ' . $this->file . ' on line ' . $this->line);

			case 4:
			case 8:
			$response->MIME = Response::HTML;
			$response->sendHeaders();
			return $this->render($template);

			case 2:
			case 6:
			$response->MIME = Response::JSON;
			$data = $this->getData(true);
			return $response->send(json_encode($data));

			// 未指定格式时，根据请求头判断
			default:
			// 如果支持html，则使用网页格式
			// 如果支持xml，则使用XML格式
			// 否则使用JSON格式
			if(isset($_SERVER['HTTP_ACCEPT'])){
				if(strpos($_SERVER['HTTP_ACCEPT'], 'html')){
					$response->MIME = Response::HTML;
					$response->sendHeaders();
					return $this->render($template);
				}
				if(strpos($_SERVER['HTTP_ACCEPT'], 'xml')){
					$response->MIME = Response::XML;
					$data = $this->getData();
					return $response->send($this->xml_encode($data));
				}
			}
			$response->MIME = Response::JSON;
			$data = $this->getData(true);
			return $response->send(json_encode($data));
		}
	}

	/**
	 * 生成XML格式的方法
	 * 
	 * @access private
	 * @return string
	**/
	private function xml_encode($data){
        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('status');
		foreach($data as $key => $value){
			$xml->writeElement($key, $value);
		}
        $xml->endElement();
        return $xml->outputMemory(true);
	}
	
	/**
	 * 判断语言的方法
	 * 
	 * @access private
	 * @param string $lang
	 * @param string $pattern
	 * @param string $default
	 * @return string
	**/
	public static function langExists($lang, $pattern, $default = _LANG_){
		$filename = str_replace('{{L}}', $lang, $pattern);
		if(is_file($filename)){
			return $filename;
		}
		$la = substr($lang, 0, 2);
		$filenames = glob(str_replace('{{L}}', $la.'-*', $pattern));
		if(isset($filenames[0])){
			return $filenames[0];
		}
		$filename = str_replace('{{L}}', _LANG_, $pattern);
		if(is_file($filename)){
			return $filename;
		}
		return false;
	}

	/**
	 * 渲染网页的方法
	 * 
	 * @access private
	 * @param string $template
	 * @return null
	**/
	private function render($template){
		$lang 		= 	$GLOBALS['NEWIDEA']->LANGUAGE;
		$code		=	$this->code;
		$intc		=	$this->intc;
		$alias 		= 	$this->alias;
		$pre		=	$this->pre;
		$message	=	$this->status;
		
		
		if($filename = self::langExists($lang, LPATH.'{{L}}/status/'.$code.'.php')){
			include $filename;
		}elseif($filename = self::langExists($lang, LPATH.'{{L}}/status/'.$intc.'.php')){
			include $filename;
		}else{
			if(isset(self::$codes[$intc])){
				$title = self::$codes[$intc];
			}else{
				if($filename = self::langExists($lang, LPATH.'{{L}}/status/1111.php')){
					include $filename;
				}else{
					$title = 'Undefined Status Message';
				}
			}
		}

		if(defined('_USE_DEBUG_MODE_')&&_USE_DEBUG_MODE_){
			if($message===$title){
				$message = '';
			}else{
				$message .= '<br />';
			}
			if($this->msg!=='(none)'){
				$message .= $this->msg;
			}
			$place = "Casted by $this->file on line $this->line";
			$tracedata = $this->getTrace();
			foreach($tracedata as $n=>$p){
				if(empty($p['file'])){
					$p['file'] = $this->file;
				}
				$place .= "<p><span class=\"number\">#";
				if(isset($p['class'])){
					// 类的静态方法或实例的方法
					if(isset($p['line'])){
						// 普通调用
						$place .= $n . "</span>" . $p['class'] . '::' . $p['function'] . '() on ' . str_replace($_SERVER['DOCUMENT_ROOT'], '…', $p['file']) . " (line " . $p['line'] . ")</p>";
					}else{
						// 服务器软件调用
						$place .= $n . "</span>" . $p['class'] . '::' . $p['function'] . '() on ' . str_replace($_SERVER['DOCUMENT_ROOT'], '…', $p['file']) . " (called by " . SRVR . ")</p>";
					}
				}elseif(isset($p['function'])){
					// 普通函数
					if(isset($p['line'])){
						// 普通调用
						$place .= $n . "</span>" . $p['function'] . '() on ' . str_replace($_SERVER['DOCUMENT_ROOT'], '…', $p['file']) . " (line " . $p['line'] . ")</p>";
					}else{
						// 服务器软件调用
						$place .= $n . "</span>" . $p['function'] . '() on ' . str_replace($_SERVER['DOCUMENT_ROOT'], '…', $p['file']) . " (called by " . SRVR . ")</p>";
					}
				}else{
					// 表达式
					$place .= $n . "</span>" . str_replace($_SERVER['DOCUMENT_ROOT'], '…', $p['file']) . " (line " . $p['line'] . ")</p>";
				}
			}
		}else{
			$place = "";
		}

		if(!is_string($template)){
			$template = $this->getTemplate();
		}
		$title = $code . ' ' . $title;
		if(is_file($template)){
			include $template;
		}else{
			Response::renderStatus($title, $alias, $code, $pre, $message, $place, $lang);
		}
		exit;
	}

	/**
	 * 获取模板的方法
	 * 
	 * @access private
	 * @return string
	**/
	private function getTemplate(){
		$code = $this->intc;
		switch($code){
			case '200':case '1200':
			return Response::TPL_GREEN_CHANNEL;

			case '404':
			return Response::TPL_404;

			case '1400':
			return Response::TPL_1400;

			default:
			if($code<400) return Response::TPL_GREEN_CHANNEL;

			if($code<500) return Response::TPL_400;

			if($code<1000) return Response::TPL_1501;

			if($code<1400) return Response::TPL;

			if($code<1415) return Response::TPL_1401;

			if($code>1499) return Response::TPL_PHP_ERRORS;

			return Response::TPL_1422;
		}
	}

	/**
	 * 获取状态数据的方法
	 * 
	 * @access public
	 * @param bool $type 是否追踪，仅调试模式有效
	 * @return array
	**/
	public function getData($getTrace = false){
		$data = [
			'code'	=>	$this->code,
			'status'=>	$this->status,
			'msg'	=>	$this->msg,
			'pre'	=>	$this->pre,
			'url'   =>  $_SERVER['REQUEST_URI']
		];
		if(defined('_USE_DEBUG_MODE_')&&_USE_DEBUG_MODE_){
			$data['position'] = $this->file." on line ".$this->line;
			if($getTrace){
				$data['trace'] = $this->getTrace();
			}
		}
		return $data;
	}
}