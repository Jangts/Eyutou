<?php
namespace Eyutou\Spider\Models;

use Status;
use DBQ;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class Admin_PlansOfProvinceViewModel extends \PM\_STUDIO\BaseMainViewModel {
    public function analysis($admininfo){
        $patharr = $this->request->ARI->patharr;
        $provinces = self::__loadData('provs', __DIR__);
        if(empty($patharr[3])||empty($provinces[$patharr[3]])){
            $province_code = '420000';
        }else{
            $province_code = $patharr[3];
        }
        $province_name = $provinces[$province_code];

        // $querier = new DBQ(1);
        // $result = $querier->using('ni_eyutouspider_areas')->where('code', $province_code)->select();
        AreaModel::__correctTablePrefix($this->app);
        $province_info = AreaModel::byGUID($province_code);
        if($province_info){
            $publishers = PublisherModel::getPubishersByArea($province_code);

            $this->assign('province',   $province_info->areaname);
            $this->assign('count',    count($publishers));
            $this->assign('publishers',   $publishers);

            $this->template = 'plans-of-province.html';
        }else{
            exit('尚没有录入该省信息');
        }
        
		return $this;
	}
}