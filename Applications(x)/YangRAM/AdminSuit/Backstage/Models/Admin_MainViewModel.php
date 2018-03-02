<?php
namespace Admin\Backstage\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_MainViewModel extends \PM\_2\BaseMainViewModel {
    public function analysis($admininfo){
		$this->assign('uid', $admininfo['UID']);
		$this->assign('adminname', $admininfo['OPERATORNAME']);
		if(empty($admininfo['AVATAR'])){
			$this->assign('avatar', '/applications/uploads/files/ca28525a8b386236136.jpg?sizes=60');
		}else{
			$this->assign('avatar', $admininfo['AVATAR'].'?sizes=60');
		}
		$this->template = 'index.html';
		return $this;
	}
}