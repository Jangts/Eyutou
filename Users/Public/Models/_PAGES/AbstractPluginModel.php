<?php
namespace PM\_PAGES;

// 引入相关命名空间，以简化书写
use Tangram\MODEL\ObjectModel;
use App;

class AbstractPluginModel extends ObjectModel{
    protected static $fp;

    protected static function dbfilename(){
        return AP_CURR.'/Plugins/plugins.json';
    }

    final private static function readPluginsJSON(){
        $fileanme = static::dbfilename();
        $fp = fopen($fileanme, 'r+');
        if (flock($fp,LOCK_EX)){
            return json_decode(fread($fp, filesize($fileanme)), true);
        }
        return NULL;
    }

    final private static function rewirtePluginsJSON($data){
        $fileanme = static::dbfilename();
        $fp = fopen($fileanme, 'w');
        if (flock($fp,LOCK_EX)){
            fwrite($fp, self::jsonToJson(json_encode($data, JSON_UNESCAPED_UNICODE), false));
            flock($fp,LOCK_UN);
            fclose($fp);
            return true;
        }
        return false;
    }
    
    final public static function getALL(){
        $plugins = self::readPluginsJSON();
        $obj = [];
        foreach($plugins as $appalias=>$attributes){
            $obj[] = new self($appalias, $attributes);
        }
        return $obj;
    }

    final public static function byGUID($appalias = 'New Plugin'){
        $plugins = self::readPluginsJSON();
        if(isset($plugins[$appalias])){
            return new self($appalias, $plugins[$appalias]);
        }
        return new self($appalias);
    }

    protected $appalias;

    final private function __construct($appalias, $attributes = []){
        $this->__put($appalias, $attributes);
    }

    protected function __put($appalias, $attributes){
        $this->appalias = $appalias;
        if(empty($attributes['pluginname'])){
            $attributes['pluginname'] = ucwords($appalias).' For Pages';
        }
        if(empty($attributes['appid'])){
            $attributes['appid'] = 0;
        }
        if(empty($attributes['desc'])){
            $attributes['desc'] = '';
        }
        $this->modelProperties = [
            'appalias'      =>  $appalias,
            '__attributes'  =>  $attributes
        ];
        return $this;
    }

    public function put($options){
        return $this->__put($this->appalias, json_decode(stripslashes($options), true));
    }

    public function getAppName(){
        if($this->modelProperties['__attributes']['appid']){
            $app = new App($this->modelProperties['__attributes']['appid']);
            if($app->Name){
                return $app->Name;
            }
            return '未知应用';
        }
        return '独立插件';
    }

    public function getOptionsText(){
        if(is_array($this->modelProperties['__attributes'])){
            $json = json_encode($this->modelProperties['__attributes'], JSON_UNESCAPED_UNICODE);
        }elseif($attributes = json_decode($this->modelProperties['__attributes'], true)){
            $json = $this->modelProperties['__attributes'];
            $this->modelProperties['__attributes'] = $attributes;
        }else{
            $json = '{}';
            $this->modelProperties['__attributes'] = [];
        }
        return self::jsonToJson($json, false);
    }

    public function save(){
        $plugins = self::readPluginsJSON();
        if(is_array($this->modelProperties['__attributes'])){
            $plugins[$this->appalias] = $this->modelProperties['__attributes'];
        }elseif($attributes = json_decode($this->modelProperties['__attributes'], true)){
            $plugins[$this->appalias] = $attributes;
        }else{
            $plugins[$this->appalias] = [];
        }
        return self::rewirtePluginsJSON($plugins);
    }
}