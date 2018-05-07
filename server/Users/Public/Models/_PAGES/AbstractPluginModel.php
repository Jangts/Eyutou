<?php
namespace PM\_PAGES;

// 引入相关命名空间，以简化书写
// use Tangram\MODEL\ObjectModel;
use App;
use AF\Models\BaseModel;
use AF\Models\FileBasedModel;


class AbstractPluginModel extends FileBasedModel {
    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/) : string {
        $dir = dirname(static::getDirnameOfModel());
        return $dir.'/Plugins/'.$guid.'/plugprops.json';
    }

    protected static function getFilenames(array $requires = []) : array {
        $dir = dirname(static::getDirnameOfModel());
        // var_dump($dir);
        // exit;
        $fileanmes = [];

        // 带索引文件的列表，方便排序
        $fileanme = $dir.'/Plugins/plugins.json';
        $array = json_decode(file_get_contents($fileanme), true);
        
        foreach($array as $guid){
            $fileanmes[] = $dir.'/Plugins/'.$guid.'/plugprops.json';
        }
        return $fileanmes;

        // 直接按匹配模式遍历
        foreach (glob($dir.'/Plugins/[a-z-0-9]/plugprops.json') as $filename) {
            $fileanmes[] = $fileanme;
        }
        return $fileanmes;
    }
    
    protected static function buildModelProperties($filename, $content) : array {
        if($properties = json_decode($content, true)){
            if(empty($properties['appid'])){
                $properties['appid'] = 0;
            }
            if(empty($properties['desc'])){
                $properties['desc'] = '';
            }
            return $properties;
        }
        var_dump($content, $properties);
        return [];
    }

    protected static function buildFileContent($content, $properties) : string {
        return json_encode($properties, JSON_UNESCAPED_UNICODE);
    }

    protected
    $filename = '',
    $content = '',
    $modelProperties = [
        'pluginname'    =>  'New Plugin For Pages',
        'appid'         =>  0,
        'desc'          =>  'Unknow plugin.'
    ];


    // protected static $fp;

    // protected static function dbfilename(){
    //     return $dir.'/Plugins/plugins.json';
    // }
    // final private static function readPluginsJSON(){
    //     $fileanme = static::dbfilename();
    //     $fp = fopen($fileanme, 'r+');
    //     if (flock($fp,LOCK_EX)){
    //         return json_decode(fread($fp, filesize($fileanme)), true);
    //     }
    //     return NULL;
    // }

    // final private static function rewirtePluginsJSON($data){
    //     $fileanme = static::dbfilename();
    //     $fp = fopen($fileanme, 'w');
    //     if (flock($fp,LOCK_EX)){
    //         fwrite($fp, self::jsonToJson(json_encode($data, JSON_UNESCAPED_UNICODE), false));
    //         flock($fp,LOCK_UN);
    //         fclose($fp);
    //         return true;
    //     }
    //     return false;
    // }
    
    final public static function getALL($type = NULL): array {
        $objs = static::query();
        // $plugins = self::readPluginsJSON();
        // $obj = [];
        // foreach($plugins as $appalias=>$properties){
        //     $obj[] = new self($appalias, $properties);
        // }

        // var_dump($objs);
        // exit;
        return $objs;
    }

    // final public static function byGUID($appalias = 'New Plugin'){
    //     $plugins = self::readPluginsJSON();
    //     if(isset($plugins[$appalias])){
    //         return new self($appalias, $plugins[$appalias]);
    //     }
    //     return new self($appalias);
    // }

    // final private function __construct($appalias, $properties = []){
    //     $this->__put($appalias, $properties);
    // }

    // protected function __put($appalias, $properties){
    //     $this->appalias = $appalias;
    //     if(empty($properties['pluginname'])){
    //         $properties['pluginname'] = ucwords($appalias).' For Pages';
    //     }
    //     if(empty($properties['appid'])){
    //         $properties['appid'] = 0;
    //     }
    //     if(empty($properties['desc'])){
    //         $properties['desc'] = '';
    //     }
    //     $this->modelProperties = [
    //         'appalias'      =>  $appalias,
    //         '__attributes'  =>  $properties
    //     ];
    //     return $this;
    // }

    // public function put($options){
    //     return $this->__put($this->appalias, json_decode(stripslashes($options), true));
    // }

    public function getAppName(){
        if($this->modelProperties['appid']){
            $app = new App($this->modelProperties['appid']);
            if($app->Name){
                return $app->Name;
            }
            return '未知应用';
        }
        return '独立插件';
    }

    public function getOptionsText(){
        // if(is_array($this->modelProperties)){
        //     $json = json_encode($this->modelProperties, JSON_UNESCAPED_UNICODE);
        // }elseif($properties = json_decode($this->modelProperties, true)){
        //     $json = $this->modelProperties;
        //     $this->modelProperties = $properties;
        // }else{
        //     $json = '{}';
        //     $this->modelProperties = [];
        // }
        return BaseModel::jsonToJson($this->content, false);
    }

    // public function save(){
    //     $plugins = self::readPluginsJSON();
    //     if(is_array($this->modelProperties)){
    //         $plugins[$this->appalias] = $this->modelProperties;
    //     }elseif($properties = json_decode($this->modelProperties, true)){
    //         $plugins[$this->appalias] = $properties;
    //     }else{
    //         $plugins[$this->appalias] = [];
    //     }
    //     return self::rewirtePluginsJSON($plugins);
    // }
}