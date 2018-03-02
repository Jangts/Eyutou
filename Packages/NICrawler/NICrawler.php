<?php
define('NICrawler_PATH', dirname(__FILE__));
function __nicrawler_autoload($className) {
    if (substr($className, 0, 10) === 'NICrawler_') {
        require_once NICrawler_PATH . str_replace('dom_drivers_', 'dom_drivers/', preg_replace('/^NICrawler_/', '/', $className)) . '.php';
    }
}
spl_autoload_register('__nicrawler_autoload');

/**
 * @class NICrawler
 * @desc Crawler Object
 * @desc 爬虫对象的默认类
 * 
 * @final
 * @author Jangts
 * @version 1.0.0
**/
abstract class NICrawler {
    const
    // 修改类型
    MODIFY_TASKS_SET = 1,
    MODIFY_TASKS_DEL = 2,
    MODIFY_TASKS_ADD = 3,
    
    // 类型常量
    INT_TYPE = 1,
    STR_TYPE = 2,
    ARR_TYPE = 3,

    // error codes
    // 错误代号
    RES_SUCCESS = 0,
    RES_INVALID_FIELD = 1000,
    RES_FIELD_NOT_SET = 1001;

    protected static
    // 错误信息
    $errcode2Errmsg = [
        self::RES_SUCCESS       => 'Success',    
        self::RES_INVALID_FIELD => 'Invalid field in array',
        self::RES_FIELD_NOT_SET => 'Accessing a non-set field'
    ],
    // 参数类型
    $taskFieldTypes = [
        'start_page' => self::STR_TYPE, 
        'link_rules' => self::ARR_TYPE, 
        'max_depth'  => self::INT_TYPE, 
        'max_pages'  => self::INT_TYPE
    ],
    $encodePatternArray = ['.', '(', ')', '[', ']', '{', '}', '^', '+', '?'],
    $encodePatternString = null,
    $task_patterns = [];

    private static function replacePattern($str, $encodePattern){

        $str = preg_replace('/(\/|\\\)+/', '\\/', $str);
        $str = preg_replace($encodePattern, '\\\$1', $str);

		$str = str_replace('<*>', '(\S*)', $str);
		$str = str_replace('<a>', '([A-z]+)', $str);
		$str = str_replace('<0>', '([0-9]+)', $str);
		$str = str_replace('<a9>', '([A-z0-9]+)', $str);
        $str = str_replace('<w>', '([A-z0-9-_]+)', $str);
        $str = str_replace('<0f>', '([\x00-\xff]+)', $str);
        $str = str_replace('<u>', '([^\\\\\/\r\n]+)', $str);
		return '/^(http|https)\:\\/\\/'.$str.'\\/*$/i';
    }

    public static function generatePatterns($task_name, $patterns){
        if(isset(self::$task_patterns[$task_name])){
            return self::$task_patterns[$task_name];
        }
        if(!self::$encodePatternString){
            self::$encodePatternString = '/(\\' . implode('|\\', self::$encodePatternArray) . ')/';
        }
        
        $pregPatterns = [];
        foreach($patterns as $pattern){
            $pregPatterns[] = self::replacePattern($pattern, self::$encodePatternString);
        }
        // var_dump($pregPatterns);
        return self::$task_patterns[$task_name] = $pregPatterns;
    }


    /**
     * 交换索引
     * 
     * @param int $a 索引一
     * @param int $b 索引二
     */
    protected static function swap(&$a, &$b) {
        $_tmp = $a;
        $a = $b;
        $b = $_tmp;
    }

    /**
     * 获取错误信息
     */
    public static function getErrmsg($errcode) {
        return self::$errcode2Errmsg[$errcode] . "\n";
    }

    public static function formatRes($data, $errcode, $errmsg = null) {
        if ($errmsg === null) {
            $errmsg = NICrawler::getErrmsg($errcode);
        }
        return array('errcode' => $errcode, 'errmsg' => $errmsg, 'res' => $data);
    }

    protected
    // NICrawler_Page页面实例
    // $page,
    $postFetchHooks = [],
    $preFetchHooks  = [],
    // $extraInfo = [],
    $fetchTasks = [],
    $readed = [],
    $hash = [],
    $additionalUrls = [],
    //合法url scheme的字典树
    $schemeTrieTree = [],
    // 参数默认值
    $taskFieldDefaults = [
        // 最大爬取深度
        'max_depth' => 20,
        // 最大爬取页面数，-1为不限制
        'max_pages' => -1
    ];

    protected function readed($url){
        $hash = md5($url);
        if(isset($this->readed[$hash])){
            return ++$this->readed[$hash];
        }
        $this->readed[$hash] = 1;
        return false;
    }

    /**
     * 修改爬取规则
     * set fetch rules.
     * 
     * @param array $inputs
     *      $optionType === MODIFY_TASKS_SET|MODIFY_TASKS_ADD, $inputs参见addFetchTasks的入参$inputs
     * @param int $optionType
     *      $optionType === MODIFY_TASKS_DEL, $inputs参见delFetchTasks的入参$inputs
     * @return object
     */
    protected function &modifyFetchTasks($inputs = [], $optionType) {
        $invalidTasks = [];
        if ($optionType === self::MODIFY_TASKS_SET || $optionType === self::MODIFY_TASKS_ADD) {
            if ($optionType === self::MODIFY_TASKS_SET) {
                $this->fetchTasks = [];
            }
            foreach ($inputs as $task_name => $task_rules) {
                $this->correctTaskParam($task_rules);
                if ($this->isTaskValid($task_rules)) {
                    $this->fetchTasks[$task_name] = $task_rules;
                } else {
                    $invalidTasks[] = $task_name;
                }
            }
        } else if ($optionType === self::MODIFY_TASKS_DEL) {
            foreach ($inputs as $task_name) {
                unset($this->fetchTasks[$task_name]);
            }
        } else {
            NICrawler_Log::warning("Unknown options for fetch tasks [{$optionType}]");
        }


        if (!empty($invalidTasks)) {
            NICrawler_Log::notice('Invalid tasks:' . implode(',', $invalidTasks));
        }
        return $this;
    }

    /**
     * 矫正任务参数
     */
    protected function correctTaskParam(&$task_rules) {
        if (!isset($task_rules['max_depth']) || ($this->taskFieldDefaults['max_depth'] !== -1 && $this->taskFieldDefaults['max_depth'] < $task_rules['max_depth'])) {
            $task_rules['max_depth'] = $this->taskFieldDefaults['max_depth'];
        }

        if (!isset($task_rules['max_pages']) || ($this->taskFieldDefaults['max_pages'] !== -1 && $this->taskFieldDefaults['max_pages'] < $task_rules['max_pages'])) {
            $task_rules['max_pages'] = $this->taskFieldDefaults['max_pages'];
        }
    }

    /**
     * 检查任务有效性
     * check if a rule is valid
     * 
     * @access protected
     * @param array $rule 单条爬虫规则
     * @return bool
     */
    protected function isTaskValid($rule) {
        foreach (self::$taskFieldTypes as $field => $type) {
            if (!isset($rule[$field]) || ($type === self::ARR_TYPE && !is_array($rule[$field]))) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * 爬虫构造函数
     * 
     * @access public
     * @param array $initOptions 初始化配置项数组
     */
    public function __construct($initOptions = []) {
        if (isset($initOptions['task_defaults'])) {
            $this->taskFieldDefaults = $initOptions['task_defaults'];
        }
        if (empty($initOptions['url_schemes'])) {
            $initOptions['url_schemes'] = ["http", "https", "ftp"];
        }
        $this->schemeTrieTree = new NICrawler_TrieTree($initOptions['url_schemes']);
    }

    /**
     * 设置爬虫的爬取规则
     * set fetch tasks.
     * 
     * @access public
     * @param array array $inputs:
     *         array <任务名1> :
     *             string 'start_page',     //爬虫的起始页面
     *             array  'link_rules':     //爬虫跟踪的超链接需要满足的正则表达式，依次检查规则，匹配其中任何一条即可
     *                 string 0,            //正则表达式1
     *                 string 1,            //正则表达式2
     *                 ...
     *                 string n-1,          //正则表达式n
     *             int    'max_depth',      //爬虫最大的跟踪深度，目前限制最大值不超过20
     *             int    'max_pages',      //最多爬取的页面数，默认指定为-1，表示没有限制
     *         array <任务名2> :
     *             ...
     *             ...
     *         ...
     *         array <任务名n-1>:
     *             ...
     *             ...
     * @return object
     */
    public function &setFetchTasks($inputs = []) {
        return $this->modifyFetchTasks($inputs, self::MODIFY_TASKS_SET);
    }

    /**
     * 新增爬取规则
     * add by what rules the crawler should fetch the pages
     * if a task has already been in tasks queue, new rules will
     * cover the old ones.
     * 
     * @access public
     * @param array $inputs 参见setFetchTasks的入参$inputs
     * @return object
     */
    public function &addFetchTasks($inputs = []) {
        return $this->modifyFetchTasks($inputs, self::MODIFY_TASKS_ADD);
    }


    /**
     * 删除一条已有的爬取规则
     * delete fetch rules according to task names
     * 
     * @param array $inputs :
     *         mixed 0 :
     *             任务名
     *         mixed 1 :
     *             任务名
     *         ... ...
     * @return object
     */
    public function &delFetchTasks($inputs = []) {
        return $this->modifyFetchTasks($inputs, self::MODIFY_TASKS_DEL);
    }

    public function getFetchTaskByName($task_name) {
        return $this->fetchTasks[$taskName];
    }

    /**
     * 查看已有的爬取规则
     */
    public function getFetchTasks() {
        return $this->fetchTasks;
    }

    /**
     * 运行爬虫
     * 
     * @access public
     * @param string use_dom_driver 指定要使用的DOM Driver
     * @param array $page_conf Page调用setConf时的输入参数，可选
     * @return object
     */
    public function &run($page_conf = [], $use_dom_driver = 'BuiltInDOM') {
        if (empty($this->fetchTasks)) {
            NICrawler_Log::warning("No fetch tasks.");
            return $this;
        }
        $this->buildPage($page_conf, $use_dom_driver);
        foreach ($this->fetchTasks as $task_name => $task_rules) {
            $this->fetch($task_name, $task_rules);
        }
        return $this;
    }

    protected function buildPage($page_conf, $use_dom_driver){
        $page = new NICrawler_Page($page_conf, $use_dom_driver);
        if (!empty($page_conf)) {
            if(isset($page_conf['url'])) {
                unset($page_conf['url']);
            }
            $page->setConf($page_conf);
        }
        $this->page = $page;
    }

    protected function fetch($task_name, $task_rules){
        $page = $this->page;
        //检查是否需要设置curl配置
        if (!empty($task_rules['page_conf'])) {
            $page->setConf($task_rules['page_conf']);
        }
        
        $depth   = 0;
        $pageNum = 0;
        $indices = [0, 1];
        $tasks = [
            [$task_rules['start_page']],
            [],
        ];

        //匹配超链接
        $patterns = self::generatePatterns($task_name, $task_rules['link_rules']);

        // NICrawler_Log::notice('第1步开始: ');
        // var_dump($tasks);

        // 当存在待处理任务，
        // 且不限制深度，或者限制深度，但深度小于设置最大允许深度
        // 且不限制页面数量，或限制页面数量，但是页面数量小于设置的最大允许页面数
        while (!empty($tasks[$indices[0]]) && ($task_rules['max_depth'] === -1 || $depth < $task_rules['max_depth']) && ($task_rules['max_pages'] === -1 || $pageNum < $task_rules['max_pages'])) {
            // 深度加1
            $depth += 1;

            // 重置出栈任务（url）索引
            $popIndex = $indices[0];
            // 重置入栈任务（url）索引
            $pushIndex = $indices[1];

            // 清空入栈任务（url），以存放检测出的新链接
            $tasks[$pushIndex] = [];

            // 遍历入栈任务（url）
            foreach ($tasks[$popIndex] as $url) {
                // 如果页面数量达到，则调出循环
                if (!($task_rules['max_pages'] === -1 || $pageNum < $task_rules['max_pages'])) {
                    break;
                }

                if($i = $this->readed($url)){
                    // NICrawler_Log::warning('Url [' . $url . '] has been fetched '.$i.' times.');
                }else{
                    // 页面对象读取
                    $page->setUrl($url);
                    $page->read();

                    //获取所有的超链接
                    $links  = $page->getHyperLinks();

                    //解析当前URL的各个组成部分，以应对超链接中存在站内链接的情况，如"/entry"等形式的URL
                    $urlComponents = parse_url($page->getUrl());

                    // NICrawler_Log::notice('访问['.$url.']('.$urlComponents['scheme'].')，获取到 '.count($links).' 条连接，其中：');
                    // var_dump($links);

                    foreach ($patterns as $pattern) {
                        foreach ($links as $link) {
                            if($real_link = $this->matchLink($urlComponents, $pattern, $link)){
                               $tasks[$pushIndex][] = $real_link;
                            }
                        }
                    }

                    //由用户实现handlePage函数
                    $page->setExtraInfo(['task_name' => $task_name]);
                    $this->handlePage($page);
                    $pageNum += 1;
                }
            }

            if (!empty($this->additionalUrls)) {
                $tasks[$pushIndex] = array_merge($tasks[$pushIndex], $this->additionalUrls); 
                $this->additionalUrls = [];
            }
            
            self::swap($indices[0], $indices[1]);
            // NICrawler_Log::notice('第'.$depth.'步结束: ');
            // var_dump($tasks);
        }
        return $this;
    }

    protected function matchLink($urlComponents, $pattern, $link){
        if (!$this->getHash($link)) {
            //拼出实际的URL
            $real_link = $link;
            
            //不使用strpos，防止扫描整个字符串
            //这里只需要扫描前6个字符即可
            $colon_pos = false;

            if(strpos($link, '//')===0){
                $real_link = $urlComponents['scheme']. ':'. $link;
            }
            if(strlen($real_link)>5){
                for ($i = 0; $i <= 5; ++$i) {
                    if ($real_link[$i] === ':') {
                        $colon_pos = $i;
                        break;
                    }
                }
            }
            
            if ($colon_pos === false || !$this->schemeTrieTree->has(substr($real_link, 0, $colon_pos))) {
                //将站内地址转换为完整地址
                $real_link = $urlComponents['scheme']
                        . "://"
                        . $urlComponents['host']
                        . (isset($urlComponents['port'])
                            && strlen($urlComponents['port']) != 0 ?
                                ":{$urlComponents['port']}" :
                                "")
                        . ($link[0] == '/' ?
                            $link : "/$link");
            }

            if(!$this->getHash($real_link)&&preg_match($pattern, $real_link) === 1){
                // 缓存哈希值
                $this->setHash($link, true);
                $this->setHash($real_link, true);
                // NICrawler_Log::notice('URL ['.$link.'('.$urlComponents['host'].')--->'.$real_link.'] 验收合格.');
                return $real_link;
            }
        }
        return false;
    }

    /**
     * 获取URL的哈希值
     */
    public function getHash($rawKey) {
        $rawKey = strval($rawKey);
        $key = md5($rawKey);
        if (isset($this->hash[$key])) {
            return $this->hash[$key];
        }
        return null;
    }
    
    /**
     * 设置URL的哈希值
     */
    public function setHash($rawKey, $value) {
        $rawKey = strval($rawKey);
        $key = md5($rawKey);
        $this->hash[$key] = $value;
    }

    public function setHashIfNotExists($rawKey, $value) {
        $rawKey = strval($rawKey);
        $key = md5($rawKey);

        $bolExist = true;
        if (empty($this->hash[$key])) {
            $this->hash[$key] = $value;
            $bolExist = false;
        }

        return $bolExist;
    }

    public function clearHash() {
        $this->hash = [];
    }

    public function addAdditionalUrls($urls) {
        if (!is_array($urls)) {
            $urls = [$urls];
        }

        $intAddedNum = 0;
        foreach ($urls as $url) {
            $url = strval($url);

            if ($this->setHashIfNotExists($url, true) === false) {
                $this->additionalUrls[] = $url;
                ++$intAddedNum;
            }
        }

        return $intAddedNum;
    }

    //对于每次爬取到的页面，进行的操作，这个方法需要使用者自己实现
    abstract public function handlePage($page);

    abstract public function setExtraInfo($inputs = []);

    abstract public function getExtraInfo($inputs = []);
}