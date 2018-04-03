<?php
namespace Pages\Ads\Models;

use Request;

abstract class AdsTypeAVModel extends \PM\_STUDIO\BaseFormAVModel {
	public function initialize(){
		return [
			'formname'	=>	'编辑广告信息'
		];
    }
    
    public function analysis($admininfo){
        $basedir = $this->request->ARI->dirname.'/'.$this->app->id.'/ads/ads/';
        
		if(isset($this->request->ARI->patharr[3])&&is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			$guid = $this->request->ARI->patharr[3];
			AdvertisementModel::__correctTablePrefix($this->app);
			$ad = AdvertisementModel::byGUID($guid);
			
			if(!$ad){
				$this->assign('href', $basedir);
				
				$this->template = '404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'移除广告',
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	$basedir.'/'.$guid,
				'href'	=>	$basedir
			];
		}else{
			Header("HTTP/1.1 301 Moved Permanently");
			Header("Location: ".$basedir.'0');
			exit;
		}

		$this->assign('form', self::buildForm($ad->getArrayCopy(), $method));
		if(isset($_GET['page'])){
            $qs = static::buildQueryString($_GET['page']);
        }else{
            $qs = static::buildQueryString();
		}
		$this->assign('buttons', [
			[
				'name' 	=>	'重置表单',
				'order'	=>	'reset',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	''
			],
			[
				'name' 	=>	'返回列表',
				'order'	=>	'anchor',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	$basedir.$qs
			],
			$button2,
			[
				'name' 	=>	'投放',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	$basedir.'/'.$guid,
				'href'	=>	$basedir.$qs
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}