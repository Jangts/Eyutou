<?php
namespace PM\_STUDIO;

use Status;
use Tangram\CTRLR\ResourceIndexer;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

abstract class BaseAdminViewModel extends \PM\_STUDIO\AbstractViewModel {
	public static function updateTemplateCache($templates=NULL){
		return \Storage::clearPath(DPATH.'BACKSTAGE/templates/');
	}

	protected  $app, $request;

	public function getFilenames($template, $is_include = false){
		if($is_include==false){
			$this->assign("__host__", '//'.HOST.'/');
			$this->assign("__dir__", COMMON_ADMIN_VIEW_URL.$this->theme.'/');
			$this->assign("__lib__", SIS_URL."static/");
			$this->assign("__api__", '//'._STD_API_);
		}
		return [COMMON_ADMIN_VIEW_PATH.$this->theme."/".$template, COMMON_ADMIN_VIEW_PATH_TPL.hash('md4', $this->theme."/".$template).".php"];
	}

	public $vars = [];

	final public function init($app =NULL, $request = NULL, $theme = 'default'){
		$this->app = $app;
		$this->request = $request;
		$this->theme = $theme;
		$this->vars =  $this->initVars();
		define('COMMON_ADMIN_VIEW_URL', __BURL__.$app->DIR.'Views/_STUDIO/');
		define('COMMON_ADMIN_VIEW_PATH', $app->Path.'Views/_STUDIO/');
		define('COMMON_ADMIN_VIEW_PATH_TPL', DPATH.'BACKSTAGE/templates/'.$app->ID.'/');
		return $this;
	}

    public function initVars(){
		return [];
    }	

	public function analysis($admininfo){
		$this->template = 'index.html';
		return $this;
	}
	
	public function render(){
		$this->display($this->template);
    }
}