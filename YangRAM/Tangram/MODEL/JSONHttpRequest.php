<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

use IDEA;
use Status;

/**
 * @class Tangram\MODEL\JSONHttpRequest
 * URL Remote Data Reader
 * URL远程数据读取器，用来
 * 读取远程服务器上的数据，并自动封装为Tangram\MODEL\Commom对象以供使用
**/
final class JSONHttpRequest implements interfaces\model {
    use traits\magic;
    use traits\arraylike;

    const
    UA_MOZ_IPC = 'Mozilla/5.0 (Tangram NI '.IDEA::VERSION.'.'.IDEA::DEVID.') YangRAM/20180101 Chrome/64.0.3282.167 Safari/13604.5.6',
    UA_MOZ_WIN = 'Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1',
    UA_MOZ_MAC = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1',
    UA_MOZ_ADR = 'Mozilla/5.0 (Linux; U; Android 2.3.7; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';

    public static function buildQueryString($data) {
        $querystring = '';
        if (is_array($data)) {
    		foreach ($data as $key => $val) {
    			if (is_array($val)) {
    				foreach ($val as $val2) {
    					$querystring .= urlencode($key).'='.urlencode($val2).'&';
    				}
    			} else {
    				$querystring .= urlencode($key).'='.urlencode($val).'&';
    			}
    		}
    		$querystring = substr($querystring, 0, -1);
    	} elseif(is_string($data)) {
    	    $querystring = $data;
    	}
    	return $querystring;
    }

    private static function buildURI($url, $data = NULL) {
        $request        =   parse_url($url);
        $scheme         =   empty($request['scheme'])       ?   (_OVER_SSL_ ? 'https://' : 'http://')  :   $request['scheme'] . '://';
        $host           =   empty($request['host'])         ?   HOST  :   $request['host'];
        $port           =   empty($request['port'])         ?   ''  :   ':' . $request['port'];
        $user           =   empty($request['user'])         ?   ''  :   $request['user'];
        $pass           =   empty($request['pass'])         ?   ''  :   ':' . $request['pass'] ;
        $pass           =   ($user || $pass)                ?   "$pass@"                        :   '';
        $path           =   empty($request['path'])         ?   ''  :   $request['path'];
        $query          =   empty($request['query'])        ?   self::buildQueryString($data)   :   $request['query'] . '&' . self::buildQueryString($data);
        $query          =   empty($query)                   ?   ''  :   '?' . $query;
        $fragment       =   empty($request['fragment'])     ?   ''  :   '#' . $request['fragment'];
        $request['url'] =   trim("$scheme$user$pass$host$port$path$query$fragment");
        return $request;
    }

    private static function buildErrorMessage($errno, $errstr){
        switch($errno) {
			case -3:
			$errormsg = 'Socket creation failed (-3)';
            break;
			case -4:
			$errormsg = 'DNS lookup failure (-4)';
            break;
			case -5:
			$errormsg = 'Connection refused or timed out (-5)';
            break;
			default:
			$errormsg = 'Connection failed ('.$errno.')';
            break;
		}
        return json_encode([
            'status'    =>  'cURL Error',
            'msg'       =>  $errormsg.' '.$errstr
        ]);
    }
    
    protected
    $timeout,
    $modelProperties = [
        'headers'           =>  [],
        'request'           =>  [],
        'responseText'      =>  '',
        'responseData'      =>  []
    ];

    public function __construct(array $headers = [], $timeout = 30) {
        $this->modelProperties['headers'] = $headers;
        $this->modelProperties['headers']['User-Agent'] = JSONHttpRequest::UA_MOZ_IPC;
        $this->timeout = $timeout;
    }

    public function read($url, $data = NULL){
        $this->modelProperties['request'] = self::buildURI($url, $data);
        if(extension_loaded('CURL')){
            $response = $this->openCURL($this->modelProperties['request']['url']);
        }else{
            @ini_set('allow_url_fopen', '1');
            if(function_exists('get_headers')){
                $response = $this->readContents($this->modelProperties['request']['url']);
            }else{
                $this->modelProperties['responseData'] = [
                    'status'    =>  'unKnow Error',
                    'msg'       =>  'Your I4s Not Support JSONHttpRequest.'
                ];
                $this->modelProperties['responseText'] = json_encode($this->modelProperties['responseData']);
                return $this;
            }
        }
        if(is_string($response)&&($responseData = json_decode($response, true))){
            $this->modelProperties['responseText'] = $response;
            $this->modelProperties['responseData'] = $responseData;
        }else{
            $this->modelProperties['responseData'] = [
                'status'    =>  'unKnow Error',
                'msg'       =>  'Your I4s Not Support JSONHttpRequest.'
            ];
            $this->modelProperties['responseText'] = json_encode($this->modelProperties['responseData']);
        }
        return $this;
    }

    private function buildHeaders(){
        $headers = [];
        if(isset($this->modelProperties['headers']['Cookie'])){
            if(is_array($this->modelProperties['headers']['Cookie'])){
                $cookies = [];
                foreach ($this->modelProperties['headers']['Cookie'] as $name => $value) {
                    $cookies[] = sprintf('%s=%s', $name, $value);
                }
                $this->modelProperties['headers']['Cookie'] = implode('; ', $cookies);
            }
            if(!is_string($this->modelProperties['headers']['Cookie'])){
                unset($this->modelProperties['headers']['Cookie']);
            }
        }
        foreach ($this->modelProperties['headers'] as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        }
        return $headers;
    }

    private function openCURL($url){
        $ch = curl_init();
        $headers = $this->buildHeaders();
        // var_dump($headers);
        // exit;
        // $cookies = $this->buildCookies();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT,           $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER,        $headers);
        // curl_setopt($ch, CURLOPT_COOKIE,            'private_session_id=8652c034a93e008c449cefb9a0585dde');  
        curl_setopt($ch, CURLOPT_URL,               $url);
        if($response = @curl_exec($ch)){
                        curl_close($ch);
            return $response;
        }else{
            $errno  =   curl_errno($ch);
            $errstr =   curl_error($ch);
                        curl_close($ch);
            return self::buildErrorMessage($errno, $errstr);
        }
    }

    private function readContents($url){
        $headers = $this->buildHeaders();
        $opts = [
            'http' => [
                'method'    =>"GET",
                'header'    => join("\r\n", $headers),
                'timeout'   => $this->timeout
            ]
        ];
        $context = stream_context_create($opts);
        if($contents = @file_get_contents($url, false, $context)){
            return $contents;
        }
        return json_encode([
            'status'    =>  'fOPEN Error',
            'headers'   =>  @get_headers($url, 1)
        ]);
    }

    final public function get($name){
        if(isset($this->modelProperties[$name])){
            return $this->modelProperties[$name];
        }
        return NULL;
    }

    final public function set($name, $value){
        return $this;
    }

    final public function has($name){
        if(array_key_exists($name, $this->modelProperties)){
            return true;
        }
        return false;
    }

    public function uns($name){
        return $this;
    }

    public function str(){
        return $this->modelProperties['responseText'];
    }
}