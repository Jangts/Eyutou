<?php
namespace Eyutou\Spider\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_AreasViewModel extends \PM\_STUDIO\BaseMainViewModel {
    public function analysis($admininfo){
		$provinces = self::__loadData('provs', __DIR__);
        if(empty($_GET['province'])||empty($provinces[$_GET['province']])){
            $this->assign('province', '420000');
        }else{
            $this->assign('province', $_GET['province']);
		}
		$this->template = 'areas.html';
		return $this;
	}
}