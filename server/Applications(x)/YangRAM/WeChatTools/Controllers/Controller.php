<?php
namespace WX\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

class Controller extends \Controller {
    public function main($arg1, $arg2){
		$_SERVER['REQUEST'] = $_REQUEST;
		// file_put_contents(__DIR__.'/~tmps/'.BOOTTIME.'.json', json_encode($_SERVER));
		define("TOKEN", "YangRAMWeChatTools");
        if(isset($_GET["signature"])&&isset($_GET["timestamp"])&&isset($_GET["nonce"])&&isset($_GET["echostr"])){
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
		    $nonce = $_GET["nonce"];
			$echoStr = $_GET["echostr"];
		
		    $tmpArr = [TOKEN, $timestamp, $nonce];
		
		    sort($tmpArr, SORT_STRING);
		    $tmpStr = implode($tmpArr);
			$tmpStr = sha1($tmpStr);		
            if($signature === $tmpStr){
			    echo $echoStr;
		    }else{
			    echo "Error";
		    }
        }else{
			$postObj = simplexml_load_string(file_get_contents('php://input'), 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$time = time();
			$textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag></xml>";
			$msgType = "text";
			$contentStr = "Welcome to wechat world!";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
    }
}