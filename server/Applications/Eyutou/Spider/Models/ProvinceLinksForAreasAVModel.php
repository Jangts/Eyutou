<?php
namespace Eyutou\Spider\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class ProvinceLinksForAreasAVModel extends \PM\_STUDIO\BaseIndexAVModel {
    public function analysis($admininfo){
		// var_dump($_GET);
        // exit;
        $provinces = self::__loadData('provs', __DIR__);
        if(empty($_GET['selected'])||empty($provinces[$_GET['selected']])){
            $this->assign('selected', '420000');
        }else{
            $this->assign('selected', $_GET['selected']);
        }
        $this->assign('provinces', $provinces);
		$this->template = 'province-links-for-areas.html';
		return $this;
	}
}