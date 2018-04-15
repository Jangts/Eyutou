<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

use IDEA;
use Status;
use Storage;
use Request;
use App;
use Tangram\MODEL\JSONHttpRequest;

/**
 * Uniform Application IPCInterface
 * 统一应用消息收发器
 * 子应用间交互时消息收发验核的接口
**/
abstract class BaseIPCInterface {
    protected static
    $staticFileStorage;

    private static function collectTrashyToken(){
        if(abs(rand(0, _SESIION_DIVISOR_ * 2) - _SESIION_DIVISOR_)<=_SESIION_PROBAB_){
            $messages = glob(RUNPATH_CA.'[0-Z]*/message/*');
            $count = count($messages);
            foreach($messages as $message){
                $time = filemtime($message);
                if(time() - $time >= _WORKER_BUILD_TIMEOUT_){
                    unlink($message);
                }
            }
        }
    }

    private static function send($url, $sData, $timeout, $token){
		$reader = new JSONHttpRequest([
            'Cookie'    =>  [
                'private_session_id' => $token
            ]
        ], $timeout);
        return $reader->read($url, $sData)->responseData;
    }
    
    protected
    $presets = [],
    $url,
    $sData;

    private function buildRequestToken($appid, $formdata){
        self::collectTrashyToken();
        self::$staticFileStorage->setNameSpace('private_formdata_json/');
        $token = hash('md4', $GLOBALS['NEWIDEA']->AI.'->'.$appid.'_'.microtime().uniqid());
        while(self::$staticFileStorage->take($token)){
            $token = hash('md4', $GLOBALS['NEWIDEA']->AI.'->'.$appid.'_'.microtime().uniqid());
        }
        if(is_array($formdata)){
            self::$staticFileStorage->store($token, json_encode($formdata));
        }else{
            self::$staticFileStorage->store($token, json_encode([
                '__from'    =>  $GLOBALS['NEWIDEA']->AI,
                '__to'      =>  $appid,
                '__time'    =>  DATETIME
            ]));
        }
        return $token;
    }

    final public function __construct(App $app, Request $request, array $ipcOptions = []){
        self::$staticFileStorage = new Storage([
            'path'			=>	RUNPATH_CA.$app->APPID.'/ipc/',
			'filetype'		=>	'ipcmsg',
			'expiration'	=>	0
        ]);
        $this->analysis($app, $request, $ipcOptions);
    }

    final protected function analysis(App $app, Request $request, array $ipcOptions) : array {
        if(empty($ipcOptions['preset'])||empty($this->presets[$ipcOptions['preset']])){
            Status::cast('cannot find ipc preset');
        }
        
        $options = $this->presets[$ipcOptions['preset']];
        if(empty($options['argc'])){
            $options['argc'] = 0;
        }
        
        if(empty($ipcOptions['args'])){
            $ipcOptions['args'] = [];
        }

        $argc = count($ipcOptions['args']);

        if($argc<$options['argc']){
            Status::cast('FewParameters');
        }

        $this->url = _STD_API_.':ipc/'.$app->APPID.'?c='.$options['controller'].'&m='.$options['methodname'];
        if($argc){
            $this->url .= '&arg='.join('/', $ipcOptions['args']);
        }

        if(empty($ipcOptions['sData'])){
            $ipcOptions['sData'] = NULL;
        }

        if(empty($ipcOptions['formdata'])){
            $ipcOptions['formdata'] = NULL;
        }
        
        if(empty($ipcOptions['timeout'])){
            $ipcOptions['timeout'] = 30;
        }

        $this->communicate($app->APPID, $ipcOptions['sData'], $ipcOptions['formdata'], $ipcOptions['timeout']);
    }

    final protected function communicate($appid, $sData, $formdata, $timeout){
        $token = $this->buildRequestToken($appid, $formdata);
        $msg = self::send($this->url, $sData, $timeout, $token);
        // die;
        self::$staticFileStorage->store($token, false);
        if(isset($msg['type'])&&$msg['type']==='IPC_MSG'){
            return $this->checkMessage($msg, $reader);
        }
        return false;
    }

    final protected function checkMessage(array $msg, RemoteDataReader $reader){
        if(isset($msg['msg'])){
            if(isset($msg['key'])){
                if(self::$staticFileStorage->setNameSpace('sData/')->read($msg['key'])){
                    $this->result = [
                        'msg'   =>  $msg['msg'],
                        'data'  =>  self::$staticFileStorage->read($msg['key'])
                    ];
                    self::$staticFileStorage->store($msg['key'], false);
                }
            }else{
                $this->result = $msg['msg'];
            }
        }elseif(isset($msg['key'])){
            if(self::$staticFileStorage->setNameSpace('sData/')->check($msg['key'])){
                $this->result = self::$staticFileStorage->read($msg['key']);
                self::$staticFileStorage->store($msg['key'], false);
            }
        }else{
            $this->result = $reader->getArrayCopy();
        }
        return true;
    }
}

