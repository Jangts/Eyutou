<?php
namespace Admin\Backstage\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_DashBoardViewModel extends \PM\_2\BaseAdminViewModel {
    public function analysis($admininfo){
		
		$this->template = 'home.html';
		return $this;
	}
}