<?php
namespace Pages\Designer\Models;

// 引入相关命名空间，以简化书写
use AF\Models\FileBasedModel;

class TemplatesModel extends FileBasedModel {
    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/){
        return __DIR__.'/providers/appprops.json';
    }

    protected static function getFilenames(array $requires = []){
        return [__DIR__.'/providers/appprops.json'];
    }
    
    protected static function buildModelProperties($content){
        if($properties = json_decode($content, true)){
            return $properties;
        }
        return [];
    }

    protected static function buildFileContent($content, $properties){
        return json_decode($properties);
    }
}