<?php
namespace AF\Controllers;
use Request;
use Response;
use App;

/**
 * @class AF\Controllers\BaseController
 * Resources Controller Base Class
 * 资源控制器基类
 * 控制器的基类，提供了控制器的基本属性和方法
 * 
 * @abstract
 * @author 		Jangts
 * @version    	5.0.0
**/
abstract class BaseController {
	use traits\testmethods;

	/**
	 * 标准返回方法
	 * 
	 * @access protected
	 * @param array $data
	 * @param int $code
	 * @param string $msg
	 * @param bool $useXML 是否使用XML格式，默认为JSON格式
	 * @return string
	**/
	public static function doneResponese(array $data, $code = 200, $msg = 'done', $useXML = false){
        $response = [
            'code'      =>  $code,
            'msg'       =>  $msg,
            'url'       =>  Request::instance()->URI->src,
            'data'      =>  $data
		];
		// if(empty($data)){
		// 	unset($response['data']);
		// }
        if($useXML){
            Response::instance(200, Response::XML)->send(ResponseModel::arrayToXml($response));
        }else{
            Response::instance(200, Response::JSON)->send(json_encode($response));
        }
	}

	/**
	 * 错误返回方法
	 * 
	 * @access protected
	 * @param int $code
	 * @param string $msg
	 * @param bool $useXML 是否使用XML格式，默认为JSON格式
	 * @return string
	**/
	public static function failResponese($code = 200, $msg = 'error', $useXML = false){
        $response = [
            'code'      =>  $code,
            'msg'       =>  $msg,
            'url'       =>  Request::instance()->URI->src
		];
        if($useXML){
            Response::instance($code, Response::XML)->send(ResponseModel::arrayToXml($response));
        }else{
            Response::instance($code, Response::JSON)->send(json_encode($response));
        }
	}
	

	protected
    $request = NULL,
    $app = NULL,
	$passport = NULL,
	$__testBeginTime;

    public function __construct(App $app, Request $request){
        $this->request = $request;
		$this->app = $app;
		$this->__init_cli_test();
	}

	final public function exit(){
		exit('Thank You!');
	}
}
