<?php
namespace OIC;

use Status;
use Request;
use Passport;
use App;
use Controller;

abstract class BaseOICtrller extends Controller {
    protected
    $request = NULL,
    $app = NULL,
    $passport = NULL;

    public function __construct(App $app, Request $request){
		$visa = new OperatorVISACtrller($app, $request);
		$sp = (string) $visa->myStatus();
		if($sp==='Runholder'){
			$this->request = $request;
        	$this->app = $app;
        	$this->passport = Passport::instance();
		}else{
            new Status(1411.0, '', 'Current Status [ '.$sp.' ]',true);
        }
	}

    protected function modified($filename) {
		$lastModified = filemtime($filename);
		if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])){
			if (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) < $lastModified) {
				return true;
			}
			return false;
		}
		if (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE'])){
			if (strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) > $lastModified) {
				return true;
			}
			return false;
		}
		return true;
	}
}
