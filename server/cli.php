<?php
define('_TESTER_', '/.TEST.PHP/');
define('_CLI_MODE_', php_sapi_name());
if(preg_match("/cli/i", _CLI_MODE_)){
    if($_SERVER['argc']>1){
        // 写入一些服务器上才有的参数
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
        $_SERVER['SERVER_SOFTWARE'] =  $_SERVER['TERM_PROGRAM'] . '/' . $_SERVER['TERM_PROGRAM_VERSION'];
        $_SERVER['HTTP_HOST'] = $_SERVER['REMOTE_HOST'] = $_SERVER['REMOTE_ADDR'] = 'localhost';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SCRIPT_FILENAME'] = __FILE__;
        $_SERVER['PHP_SELF'] = '/'.$_SERVER['argv'][1];

        // 准备三个空的数组
        $_COOKIE = $_GET = $_POST = [];
        if($_SERVER['argc'] > 2){
            if(in_array(strtoupper($_SERVER['argv'][2]), ['GET','CONNECT','COPY','DELETE','HEAD','LINK','OPTIONS','POST','PUT','PATCH','TRACE','UNLINK','UPDATE','WRAPPED'])){
                $_SERVER['REQUEST_METHOD'] = strtoupper($_SERVER['argv'][2]);
                if(in_array($input, ['POST','PUT', 'UPDATE', 'PATCH', 'DELETE'])){
                    fwrite(STDOUT,"Please specify a HTTP method (press the enter key means use 'get'):\r\n");
                    $input = trim(fgets(STDIN));
                    if(preg_match('/^load\s+(.+)$/', $input, $matches)){
                        // 如果输入为load fullpath格式，则读取该json文件并数组化
                        if(is_file($matches[1])){
                            $json = file_get_contents($matches[1]);
                            $_POST = json_decode($json, true);
                        }
                    }elseif(preg_match('/^\s*\{[\s\S]+\}\s*$/', $input)){
                        // 如果输入为use {"name":value}格式，则直接截取后数组化
                        if($array = json_decode($input, true)&&is_array($array)){
                            $_POST = $array;
                        }
                    }
                }
                $s = 3;
                $d = 1;
            }else{
                $_SERVER['REQUEST_METHOD'] = 'GET';
                $s = $d = 2;
            }
            for($i = $s; $i<$_SERVER['argc']; $i+=2){
                if($i+1===$_SERVER['argc']){
                    $_GET[$_SERVER['argv'][$i]] = '';
                    if($i === $s){
                        $_SERVER['QUERY_STRING'] .= $_SERVER['argv'][$i];
                    }else{
                        $_SERVER['QUERY_STRING'] .= '&'.$_SERVER['argv'][$i];
                    }
                }else{
                    $_GET[$_SERVER['argv'][$i]] = $_SERVER['argv'][$i+1];
                    if($i === $s){
                        $_SERVER['QUERY_STRING'] .= $_SERVER['argv'][$i] .'='. $_SERVER['argv'][$i+1];
                    }else{
                        $_SERVER['QUERY_STRING'] .= '&'.$_SERVER['argv'][$i] .'='. $_SERVER['argv'][$i+1];
                    }
                }
            }
            $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }else{
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $_SERVER['QUERY_STRING'] = '';
            $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
        }
        $_SERVER['PHP_SELF'] = '/main.php'.$_SERVER['PHP_SELF'];

        include 'main.php';
    }else{
        echo "Welcome to access YangRAM by using CLI mode!\r\nIf you want to visit the homepage, you must type in the filename of the homepage. such as 'php cli.php index.html'.";
        exit;
    }
}else{
    // 设置输出格式
    header('Content-Type: text/plain;charset=UTF-8');
    echo "This file can only be open in cli mode.";
    exit;
}
