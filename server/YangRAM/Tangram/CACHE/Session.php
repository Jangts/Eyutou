<?php
namespace Tangram\CACHE;

use DBQ;
use Request;

/**
 * @class Tangram\CACHE\Session
 * Session Caching & Reading Unit
 * 会话存取单元，用于记录与读取用户会话的静态类
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class Session {
    private static
    // 会话ID
    $session_id,
    // 缓存处理对象，将被提交给服务器软件，由其直接控制
    $handler = NULL;

    /**
	 * 初始化会话存取单元
	 * 
	 * @access public
     * @static
	 * @return string|null $session_id 会话ID，默认为空，即新建会话
	 * @return bool
	**/
    public static function init($session_id=NULL){
        if(self::$handler===NULL){
            self::setOptions();
            self::getHandler();
            session_set_save_handler(
			    [self::$handler, 'open'],
		        [self::$handler, 'close'],
		        [self::$handler, 'read'],
		        [self::$handler, 'write'],
		        [self::$handler, 'destroy'],
			    [self::$handler, 'gc']
    	    );
		    register_shutdown_function('session_write_close');
            session_name(_SESSION_NAME_);
            if($session_id){
                session_id($session_id);
            }
		    session_start();
            self::$session_id = session_id();
        }
        return true;
    }
    
    /**
	 * 设置会话配置项
	 * 
	 * @access private
     * @static
	 * @return bool
	**/
    private static function setOptions(){
        ini_set('session.auto_start', 0);
		ini_set('session.session.gc_probability', 0);
        ini_set('session.use_trans_sid', 0);

    	ini_set('session.use_cookie', 1);
    	ini_set('session.cookie_path', '/');
	    ini_set('session.hash_bits_per_character', 5);
	    if(_SESIION_CROSS_&&_SESIION_DOMAIN_){
			ini_set('session.cookie_domain', _SESIION_DOMAIN_);
		}
	    ini_set('session.gc_probability', _SESIION_PROBAB_);
	    ini_set('session.gc_divisor', _SESIION_DIVISOR_);
        ini_set('session.gc_maxlifetime', _SESIION_EXPIRY_);
        return true;
    }

    /**
	 * 获取会话存取的底层对象
	 * 
	 * @access private
     * @static
	 * @return object
	**/
    private static function getHandler(){
        include('ses_handlers/_interface.php');
		if(_SESSION_ON_DB_){
            include('ses_handlers/DbSession.php');
		    return self::$handler = ses_handlers\DbSession::instance();
	    }else {
		    include('ses_handlers/FsSession.php');
			return self::$handler = ses_handlers\FsSession::instance();
		}
    }

    /**
	 * 获取会话ID
	 * 
	 * @access public
     * @static
	 * @return string
	**/
    public static function id(){
        return self::$session_id;
    }

    /**
	 * 自主写入会话信息
	 * 
	 * @access public
     * @static
     * @param string $session_id 会话ID
     * @param string $data 写入的信息
	 * @return string
	**/
    public static function set($session_id, $data){
        self::$handler->write($session_id, $data);
    }

    /**
	 * 读取会话信息
	 * 
	 * @access public
     * @static
     * @param string $session_id 会话ID
	 * @return string
	**/
    public static function get($session_id){
        self::$handler->read($session_id);
    }

    /**
	 * 删除会话信息
	 * 
	 * @access public
     * @static
     * @param string $session_id 会话ID
	 * @return bool
	**/
    public static function del($session_id){
        return self::$handler->destroy($session_id);
    }
}
