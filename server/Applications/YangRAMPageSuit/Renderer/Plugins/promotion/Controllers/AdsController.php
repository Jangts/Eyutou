<?php
namespace Pages\Main\Plugins\promotion\Controllers;

// 引入相关命名空间，以简化书写
use Pages\Main\Plugins\promotion\Models\AdvertisementModel;

class AdsController extends \Controller {
    public function read($id, array $options, $appid){
        AdvertisementModel::__correctTablePrefix(new \App($appid));
        if($id&&$ads = AdvertisementModel::byGUID($id)){
            return $ads;
        }
        return AdvertisementModel::create();
    }
}