<?php
/**
 * @class NICrawler Page
 * @desc Crawler Page
 * 
 * @final
 * @author Jangts
 * @version 1.0.0
**/
final class NICrawler_Page {
   protected static $field2CurlOpt = [
        /* bool */
        'include_header' => CURLOPT_HEADER,
        'exclude_body'   => CURLOPT_NOBODY,
        'is_post'        => CURLOPT_POST,
        'is_verbose'     => CURLOPT_VERBOSE,
        'return_transfer'=> CURLOPT_RETURNTRANSFER,

        /* int */
        'buffer_size'       => CURLOPT_BUFFERSIZE,
        'connect_timeout'   => CURLOPT_CONNECTTIMEOUT,
        'connect_timeout_ms' => CURLOPT_CONNECTTIMEOUT_MS,
        'dns_cache_timeout' => CURLOPT_DNS_CACHE_TIMEOUT,
        'max_reDIR'        => CURLOPT_MAXREDIRS,
        'port'              => CURLOPT_PORT,
        'timeout'           => CURLOPT_TIMEOUT,
        'timeout_ms'        => CURLOPT_TIMEOUT_MS,

        /* string */
        'cookie'            => CURLOPT_COOKIE,
        'cookie_file'       => CURLOPT_COOKIEFILE,
        'cookie_jar'        => CURLOPT_COOKIEJAR,
        'post_fields'       => CURLOPT_POSTFIELDS,
        'url'               => CURLOPT_URL,
        'user_agent'        => CURLOPT_USERAGENT,
        'user_pwd'          => CURLOPT_USERPWD,

        /* array */
        'http_header'       => CURLOPT_HTTPHEADER,

        /* stream resource */
        'file'              => CURLOPT_FILE,

        /* function or a Closure */
        'write_function'    => CURLOPT_WRITEFUNCTION,

        /* https */
        'ssl_verifypeer'    => CURLOPT_SSL_VERIFYPEER,
    ];

    protected
    $use_dom_driver,
    $curl,
    $dom,
    $content,
    $conf    = [],
    $extraInfo = [],
    $curlHandleCloseable = FALSE,
    
    $defaultConf = [
        'connect_timeout'   =>  10,
        'max_reDIR'         =>  10,
        'return_transfer'   =>  1,   //need this
        'timeout'           =>  15,
        'url'               =>  null,
        'user_agent'        =>  'firefox',
        'ssl_verifypeer'    =>  false,
    ];

    protected function setCURLOptions($conf = []) {
        $curlOpts = [];
        foreach ($conf as $k => $v) {
            if (isset(self::$field2CurlOpt[$k])) {
                $curlOpts[self::$field2CurlOpt[$k]] = $v;
            } else {
                //currently only curl options can be set
                $curlOpts[$k] = $v;
            }
        }
        return curl_setopt_array($this->curl, $curlOpts);
    }

    protected function setCURLOption($field, $value) {
        if (isset(self::$field2CurlOpt[$field])) {
            return curl_setopt($this->curl, self::$field2CurlOpt[$field], $value);
        } else {
            //currently only curl options can be set
            return curl_setopt($this->curl, $field, $value);
        }
    }

    /**
     * 构造函数
     * 空函数
     * 
     * @access public
     * @param bool  $curl : whether to clear default options not set in $conf
     * @param array $conf configurations
     */
    public function __construct($conf = [], $use_dom_driver = 'BuiltInDOM', $curl = null) {
        $this->use_dom_driver = $use_dom_driver;
        $this->curl = $curl;
        if (empty($this->curl)) {
            $this->curl = curl_init();
            $this->curlHandleCloseable = TRUE;
        }
        $this->setConf($conf, TRUE);
    }

    /**
     * 设置配置项
     * 返回先前的设置项
     * 
     * @param array $conf 新配置项数组
     * @param bool  $clear_previous_conf 是否重置所有配置项
     * @return array
     */
    public function setConf($conf = [], $clear_previous_conf = FALSE) {
        // 备份当前设置项
        $previous_conf = $this->conf;

        // 如果$clear_previous_conf为真，则重置所有配置
        if ($clear_previous_conf === TRUE) {
            $this->conf = $this->defaultConf;
        }

        // 遍历并录值
        foreach ($conf as $k => $v) {
            $this->conf[$k] = $v;
        }

        $resultsult = $this->setCURLOptions($this->conf);

        // 如果设置失败，回滚CURL设置
        if (!$resultsult) {
            $this->conf = $previous_conf;
            $this->setCURLOptions($this->conf);
            return false;
        }

        return $previous_conf;
    }

    /**
     * 设置CURL的属性
     * 
     * @access public
     * @param string $key specified field
     * @param int $value
     * @return mixed
     */
    public function setConfField($field, $value) {
        $this->conf[$field] = $value;
        return $this->setCURLOption($field, $value);
    }

    /**
     * 设置CURL文件头
     * 
     * @access public
     * @param array $headerList
     * @return object
     */
    public function &setHeaders($headerList) {
        $this->setConf(array(
            "http_header" => $headerList
        ));
        return $this;
    }

    /**
     * 获取当前实例的配置
     * 
     * @access public
     * @return array
     */
    public function getConf() {
        return $this->conf;
    }

    /**
     * 获取CURL的属性
     * 
     * @access public
     * @param string $key specified field
     * @return mixed
     */
    public function getConfField($key) {
        if (isset($this->conf[$key])) {
            return NICrawler::formatRes($this->conf[$key], NICrawler::RES_SUCCESS);
        } else {
            return NICrawler::formatRes(null, NICrawler::RES_FIELD_NOT_SET);
        }
    }

    public function setExtraInfo($input) {
        foreach ($input as $key => $val) {
            $this->extraInfo[$key] = $val;
        }
    } 

    public function getExtraInfo($input) {
        $output = [];
        foreach ($input as $req_key) {
            $output[$req_key] = $this->extraInfo[$req_key];
        }
        return $output;
    }

    /**
     * 指定URL
     * 返回上一个URL
     * 
     * @access public
     * @param string $url : the URL
     * @return string
     */
    public function setUrl($url) {
        $previous_url = $this->conf['url'];
        $this->setConfField('url', $url);
        return $previous_url;
    }

    /**
     * 读取资源
     * 
     * @access public
     * @return string|bool
     */
    public function read() {
        $this->content = curl_exec($this->curl);
        
        if ($this->content !== FALSE) {
            $matches = [];
            preg_match('#charset="?([a-zA-Z0-9-\._]+)"?#', $this->content, $matches);
            
            // 如果没有设置字符编码格式，则转码为UTF8
            if (!empty($matches[1])) {
                $this->content = mb_convert_encoding($this->content, 'UTF-8', $matches[1]);
            }
            

            $classname = 'NICrawler_dom_drivers_'.$this->use_dom_driver;

            $this->dom = new $classname();
            
            if ($this->dom === null) {
                NICrawler_Log::warning('$this->dom is NULL!');
                return null;
            }

            @$this->dom->loadHTML($this->content);
        }
        return $this->content;
    }

    /**
     * 获取URL
     * 
     * @access public
     * @return string
     */
    public function getUrl() {
        $ret = $this->getConfField('url');
        return strval($ret['res']);
    }

    /**
     * 获取页面内容
     * 
     * @access public
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * 选取页面元素
     * 
     * @access public
     * @param string $pattern
     * @param int $index
     * @param object $contextnode
     *
     * @return object|null|bool
     */
    public function select($pattern, $index = null, $contextnode = null) {
        if ($this->dom === null) {
            NICrawler_Log::warning('$this->dom is NULL!');
            return [];
        }

        $results = $this->dom->select($pattern, $index, $contextnode);
        return $results;
    }

    /**
     * 按元素ID选取页面元素
     * 
     * @access public
     * @param string $pattern
     * @param int $index
     * @param object $contextnode
     *
     * @return object|null|bool
     */
    public function selId($id) {
        if ($this->dom === null) {
            NICrawler_Log::warning('$this->dom is NULL!');
            return null;
        }

        return $this->dom->getElementById($id);
    }

    /**
     * 按元素标签名选取页面元素
     * 
     * @access public
     * @param string $tag specifed elements' tag name 
     * @return object|null
     */
    public function selTagName($tag) {
        if ($this->dom === null) {
            NICrawler_Log::warning('$this->dom is NULL!');
            return null;
        }
        return $this->dom->getElementsByTagName($tag);
    }   
    
    /**
     * 取出$content中的所有<a>标签的内容，以数组形式返回
     * 
     * @access public
     * @return array
     */
    public function getHyperLinks() {
        if ($this->dom === null) {
            NICrawler_Log::warning('$this->dom is NULL!');
            return [];
        }
        return $this->dom->getHyperLinks();
    }

    /**
     * 获取元素属性
     * 
     * @access public
     * @param object $node
     * @param string $attr
     * @return array
     */
    public function getAttribute($node, $attr){
        if ($this->dom === null) {
            NICrawler_Log::warning('$this->dom is NULL!');
            return null;
        }
        return $this->dom->getAttribute($node, $attr);
    }

    /**
     * 获取元素内容
     * 
     * @access public
     * @param object $node
     * @param string $attr
     * @return array
     */
    public function getNodeText($node){
        if ($this->dom === null) {
            NICrawler_Log::warning('$this->dom is NULL!');
            return null;
        }
        return $this->dom->getNodeText($node);
    }

    /**
     * 析构函数
     * 关闭CURL
     * 
     * @access public
     */
    public function __destruct() {
        if ($this->curlHandleCloseable) {
            curl_close($this->curl);
        }
    }
}