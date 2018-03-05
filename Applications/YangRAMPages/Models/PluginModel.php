<?php
namespace Pages\Models;

// 引入相关命名空间，以简化书写
use PM\_PAGES\AbstractPluginModel;

class PluginModel extends AbstractPluginModel{
    protected static function dbfilename(){
        return dirname(dirname(__FILE__)).'/Plugins/plugins.json';
    }
}