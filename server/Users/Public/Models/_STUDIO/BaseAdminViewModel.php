<?php
namespace PM\_STUDIO;

use Status;
use Tangram\CTRLR\ResourceIndexer;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

abstract class BaseAdminViewModel extends \AF\Models\BaseViewModel {
	public static function updateTemplateCache($templates=NULL){
		return \Storage::clearPath(DPATH.CACAI.'/templates/');
	}

	final protected static function setStaticProterties(array $vars){
		foreach ($vars as $key => $value) {
			static::$$key = $value;
		}
	}

	public static function loadStaticProperties(){
		// new Status(1414, '', 'method "loadStaticProperties()" must be rewrite.', true);
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
		$this->vars =  $this->initialize();
		define('COMMON_ADMIN_VIEW_URL', __BURL__.$app->DIR.'Views/_'.CACAI.'/');
		define('COMMON_ADMIN_VIEW_PATH', $app->Path.'Views/_'.CACAI.'/');
		define('COMMON_ADMIN_VIEW_PATH_TPL', DPATH.CACAI.'/templates/'.$app->ID.'/');
		return $this;
	}

    public function initialize(){
		static::loadStaticProperties();
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