<?php
namespace Eyutou\Admin\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_MainViewModel extends \PM\_STUDIO\BaseMainViewModel {
    public function analysis($admininfo){
		$this->assign('uid', $admininfo['uid']);
		$this->assign('adminname', $admininfo['nickname']);
		if(empty($admininfo['avatar'])){
			$this->assign('avatar', '/applications/uploads/files/ca28525a8b386236136.jpg?sizes=60');
		}else{
			$this->assign('avatar', $admininfo['avatar'].'?sizes=60');
		}
		$this->assign('menugroups', json_decode(file_get_contents(__DIR__.'/providers/leftmenu_base.json'), true));
		$this->template = 'index.html';
		return $this;
	}
}