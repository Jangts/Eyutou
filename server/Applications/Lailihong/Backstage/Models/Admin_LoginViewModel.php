<?php
namespace Lailihong\Backstage\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_LoginViewModel extends \PM\_STUDIO\BaseMainViewModel {
    public function analysis($admininfo){
		if(count($this->request->ARI->patharr)){
			$this->assign('hash', $this->request->URI->src);
            $this->template = 'reload.html';
        }else{
			$this->template = 'login.html';
		}
		return $this;
	}
}