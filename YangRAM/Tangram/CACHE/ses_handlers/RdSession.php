<?php
namespace Tangram\CACHE\ses_handlers;
use Status;

/**
 * Redis Session
 * 文档存储SESSION解决方案
**/
final class RdSession implements _interface {
    private static
    $instance = NULL,
    $savePath = RUNPATH_SES;

	public static function instance(){
		if(RdSession::$instance===NULL){
			RdSession::$instance = new self;
		}
        return RdSession::$instance;
    }
    
    private
    $coon;
    
	private function __construct(){
        # 链接Redis数据库
    }

    public function open($savePath, $sessionName) {
        
		return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        
        return '';
    }

    public function write($id, $data) {
        
        return file_put_contents(self::$savePath."/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id) {
        
        return false;
    }

    public function gc($maxlifetime){
        
        return true;
    }

	public function __clone(){
		throw new \Exception('Cannot Copy Object!');
	}
}
