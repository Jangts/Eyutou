<?php
namespace Pages\Main\Models;

// 引入相关命名空间，以简化书写
use Tangram\ClassLoader;
use Status;
use Tangram\MODEL\ObjectModel;
use Request;
use Response;
use App;

use PM\_PAGES\ColumnModel;

use Packages\NIML;

use Pages\Main\Models\PageModel;
use Pages\Main\Models\MenuModel;
use Pages\Main\Models\PluginModel;

class FrontPageViewModel extends \AF\Models\BaseViewModel {
    protected
    $theme = 'default',
    $_pages = [];

    public function getFilenames($template, $is_include = false){
		if($is_include==false){
			$this->assign("__host", '//'.HOST);
			$this->assign("__theme", __BURL__.CACAR."/Views/_".CACAI."/".$this->theme."/");
			$this->assign("__static", SIS_URL."static/");
		}
		return [$this->sourcedir."_".CACAI."/".$this->theme."/nimls/".$template, $this->compileddir.'niml/_'.CACAI."/".hash('md4', $this->theme."/".$template).".php"];
    }
    
    /**
     * 自定义方法
     */
    public function showPageProp($pagealisas, $prop, $length = 0, $start = 0){
        if(isset($_pages[$pagealisas])){
            $page = $_pages[$pagealisas];
        }elseif($page = PageModel::getPageByAlias($pagealisas, 0)){
            $_pages[$pagealisas] = $page;
        }
        if($page&&$page->$prop){
            if($length==0){
                $length = NULL;
            }
            echo mb_substr($page->$prop, $start, $length);
        }
    }

    public function menu($id = 0){
        $menu = new MenuModel($id);
        return $menu->getArrayCopy();
    }

    public function checkcurrent($guid, ColumnModel $column, $true = 'current', $false = '', $selfonly = false){
        if(is_array($guid)&&isset($guid['type'])&&isset($guid['value'])){
            $guid = $guid['type'] . '_' . $guid['value'];
        }
        if(!is_string($guid)){
            echo $false;
        }else{
            if($column->match($guid, true, false, $selfonly)){
                echo $true;
            }else{
                echo $false;
            }
        }
    }

    public function readPluginResources($pluginalias, $restype = '', $options = ''){
        return $this->readPluginResource($pluginalias, NULL, $restype, $options);
    }

    public function readPluginResource($pluginalias, $guid, $restype = '', $options = ''){
        if($puglin = PluginModel::byGUID($pluginalias)){
            if($restype){
                $classname = \AF\Routers\BaseRouter::correctClassName($restype).'Controller';
                $filename = dirname(dirname(__FILE__)).'/Plugins/'.$pluginalias.'/Controllers/'.$classname;
    		    ClassLoader::execute($filename);
                $controller = '\Pages\Main\Plugins\\'.$pluginalias.'\Controllers\\'. $classname;
                if(method_exists($controller, 'read')){
                    parse_str($options, $options);
                    $class = new $controller($GLOBALS['APPLICATION'], $GLOBALS['REQUEST']);
				    return call_user_func_array([$class, 'read'], [$guid, $options, $puglin->appid]);
			    }
            }
        }
        if($guid){
            return ObjectModel::enclose([
                'guid'      =>  $guid,
                'restype'   =>  $restype
            ]);
        }
        return [];
    }

    public function echoPluginResources($pluginalias, $restype = '', $options = ''){
        echo $this->readPluginResource($pluginalias, NULL, $restype, $options);
    }

    public function echoPluginResource($pluginalias, $guid, $restype = '', $options = ''){
        echo $this->readPluginResource($pluginalias, $guid, $restype, $options);
    }

    public function render(){
		$this->display($this->template);
    }
}