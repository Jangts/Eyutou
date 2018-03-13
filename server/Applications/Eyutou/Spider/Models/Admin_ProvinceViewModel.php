<?php
namespace Eyutou\Spider\Models;

use Status;
use DBQ;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_ProvinceViewModel extends \PM\_STUDIO\BaseMainViewModel {
    public function analysis($admininfo){
        $patharr = $this->request->ARI->patharr;
        $provinces = self::__loadData('provs', __DIR__);
        if(empty($patharr[2])||empty($provinces[$patharr[2]])){
            $province_code = '420000';
        }else{
            $province_code = $patharr[2];
        }
        $province_name = $provinces[$province_code];

        $querier = new DBQ(1);
        $result = $querier->using('ni_eyutouspider_areas')->where('code', $province_code)->select();
        AreaModel::__correctTablePrefix($this->app);
        $province_info = AreaModel::byGUID($province_code);
        $cities = AreaModel::query([
            'belong_to' =>  $province_code,
            'grade'     =>  2
        ]);

        $like = str_replace('0000', '%', $province_code);
        $counties = AreaModel::query("`grade` = 3 AND `code` LIKE '$like'");

        var_dump($province_code, $province_name, $querier, $result->getArrayCopy(), $province_info, $cities, $counties);
        exit;
        
        $provinces = self::__loadData('provs', __DIR__);
        if(empty($_GET['selected'])||empty($provinces[$_GET['selected']])){
            $this->assign('selected', '420000');
        }else{
            $this->assign('selected', $_GET['selected']);
        }
        $this->assign('provinces', $provinces);
		$this->template = 'cities.html';
		return $this;
	}
}