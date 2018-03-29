<?php
namespace OIC;

use Status;
use Response;
use Storage;
use Lib\compilers\OperationScript;
use Lib\compilers\JSMin;
use Lib\compilers\OIStyleSheets;
use Lib\compilers\Scssc;
use AF\Storages\AppCache;

use AF\Models\LocalizedDictMode;

abstract class OISourceFilesResponser_BaseClass extends BaseOICtrller {
    protected
	$posterimage = 'poster.jpg',
	$main_os_file = 'Sources/main',
    $os_outdir = '.tmp/os/',
	$main_oiss_file = 'common';

	private function send($code, $type = Response::TXT) {
		if($type === Response::JS){
			if(isset($this->request->INPUTS->check)){
				ResponseModel::instance(200, 'text/yangram.project-file')->send('');
			}
        	// $counter = new Counter(DB_REG.'apps', 0);
			// $counter->setFields('app_count', 'app_id')->point($this->app->APPID)->add();
		}

        if($code){
            $response = Response::instance(200, $type);
        }else{
            $response = Response::instance(304, $type);
        }
		
		$response->setHeader('Cache-Control', 'public')
            ->setHeader('Cache-Control', 'max-age=3153600000')
            ->setHeader('Expires', preg_replace('/.{5}$/', 'GMT', gmdate('r', intval(time() + 3153600000))))
            ->setHeader('Last-Modified', gmdate("D, d M Y H:i:s", time()).' GMT')
            ->send($code);
	}

	final public function returnSplashScreen(){
		$splashScreen = $this->getSplashScreen();
		if($this->checkResourceModification($splashScreen[0])){
			return $this->send($splashScreen[1], Response::TXT);
		}else{
			return $this->send('', Response::TXT);
		}
	}

	private function getSplashScreen(){
		$appid = CACAI;
		$storge = new AppCache($appid, false, Storage::STR, '.xml');
		if($body = $storge->take('SplashScreen')){
			return [$storge->time('SplashScreen'), $body];
		}else{
			$body = '<info>';
			$body .= '<applang>'.$this->app->Lang.'</applang>';
			$body .= '<atitle>'.$this->app->Name.'</atitle>';
			$body .= '</info>';
			$body .= '<view loading>';
			if($appid!='SETTINGS'&&$this->posterimage&&file_exists(CACAP.'Sources/'.$this->posterimage)){
				$body .= '<weclome left><appname>'.$this->app->Name.'</appname><appvrsn>'.$this->app->Version.'</appvrsn><spinner class="app-launching-spinner"></spinner></weclome>';
				$body .= '<poster right><img width="640" height="360" src="'.HTTP_PID.CACAR.'Sources/'.$this->posterimage.'" /></poster>';
			}else{
				if($appid=='SETTINGS'){
					$body .= '<weclome><appname>'.$this->app->Name.'</appname><appvrsn>'.$this->app->Version.'</appvrsn>';
					$body .= '<spinner class="cp-launching-spinner"> <el class="cls-bounce1"></el> <el class="cls-bounce2"></el><el class="cls-bounce3"></el></spinner></weclome>';
				}else{
					$body .= '<weclome center><appname>'.$this->app->Name.'</appname><appvrsn>'.$this->app->Version.'</appvrsn><spinner class="app-launching-spinner"></spinner></weclome>';
				}
			}
			$body .= '</view>';
			$storge->store('SplashScreen', $body);
			return [time(), $body];
		}
	}

	final public function returnMainOS(){
        global $RUNTIME;
        $osfile = CACAP.$this->main_os_file.'.os';
        $outfile = CACAP.$this->os_outdir.$RUNTIME->LANGUAGE.'.js';
        $minfile = CACAP.$this->os_outdir.$RUNTIME->LANGUAGE.'.min.js';
        if(is_file($minfile)){
            if($this->checkFileModification($minfile)){
                if(_USE_DEBUG_MODE_){
                    return $this->send(file_get_contents($outfile), Response::JS);
                }else{
                    return $this->send(file_get_contents($minfile), Response::JS);
		        }
            }else{
                return $this->send('', Response::JS);
            }
        }elseif(is_file($osfile)){
			$complier = new OperationScript(CACAP);
			$code = $complier->complie($osfile, $outfile, $minfile, $this->checklang($RUNTIME->LANGUAGE));
            return $this->send($code, Response::JS);
        }
        new Status(404, true);
	}

	protected function checklang($lang){
		$lang_check_result = LocalizedDictMode::checkLang($this->app->Path.'Locales/{{lang}}.json', false, $lang);
		if($lang_check_result){
			return file_get_contents($lang_check_result[1]);
		}
        return false;
    }

    final public function returnStyleSheets($basename = ''){
		if($basename){
			$filename = CACAP.'Sources/'.$basename.'.css';
			return $this->getStyleSheets($filename, false);
		}
		$filename = CACAP.'Sources/'.$this->main_oiss_file.'.css';
		return $this->getStyleSheets($filename, true);	
    }

	private function getStyleSheets($filename, $is_main){
        if(is_file($filename)){
            if($this->checkFileModification($filename)){
				$complier = new OIStyleSheets(CACAI, HTTP_PID.CACAR,
					HTTP_HOST.APP_PID.$this->app->Author.'/'.$this->app->xProps['Suitspace'].'/');
				$code = $complier->cssFilter(file_get_contents($filename), $is_main);
                return $this->send($code, Response::CSS);
            }else{
                return $this->send('', Response::CSS);
            }
		}elseif(is_file($filename.'x')){
			$source = file_get_contents($filename.'x');
			$scssc = new Scssc();
			file_put_contents($filename, $scssc->compile($source));
			return $this->getStyleSheets($filename, $is_main);
		}else{
            return $this->send('/* yangram css document**/', Response::CSS);
        }
    }

	final public function clear(){
        if(is_dir(CACAP.$this->os_outdir)){
            Storage::clearPath(CACAP.$this->os_outdir);
        }
        exit('{"msg":"cleared"}');
    }
}
