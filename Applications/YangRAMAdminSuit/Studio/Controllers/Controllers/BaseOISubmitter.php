<?php
namespace OIC;

use Status;
use Request;
use Passport;
use App;
use Controller;

abstract class BaseOISubmitter extends \AF\Controllers\BaseSubmitController {
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
}
