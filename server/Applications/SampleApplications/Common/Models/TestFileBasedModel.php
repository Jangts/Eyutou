<?php
namespace App\Models;

// 引入相关命名空间，以简化书写
use AF\Models\FileBasedModel;

class TestFileBasedModel extends FileBasedModel {
    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/) : string {
        return __DIR__.'/providers/appprops.json';
    }

    protected static function getFilenames(array $requires = []) : array {
        return [__DIR__.'/providers/appprops.json'];
    }
    
    protected static function buildModelProperties($filename, $content) : array {
        if($properties = json_decode($content, true)){
            return $properties;
        }
        return [];
    }

    protected static function buildFileContent($content, $properties) : string {
        return json_encode($properties);
    }
}