<?php
namespace Lailihong\Backstage\Models;

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
		$this->assign('menugroups', self::__loadData('leftmenu_base', __DIR__));
		$this->template = 'index.html';
		return $this;
	}
}