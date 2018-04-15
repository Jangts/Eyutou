<?php
namespace Pages\Designer\Models;

// 引入相关命名空间，以简化书写
use AF\Models\FileBasedModel;

class TemplateModel extends FileBasedModel {
    final public static function getFilenamesInDir($path, $fileExtention = '', bool $containSubDir = false){
        $filenames = [];
        if($containSubDir){
            foreach (glob($path.'*/') as $filename) {
                if(is_dir($filename)){
                    $filenames = array_merge($filenames, self::getFilenamesInDir($filename, $fileExtention, $containSubDir));
                }
            }
        }
        foreach (glob($path.'*'.$fileExtention) as $filename) {
            if(is_file($filename)){
                $filenames[] = $filename;
            }
        }
        return $filenames;
    }

    protected static function getFilename($guid = NULL/*单例数据文件不需要GUID*/) : string {
        $path = dirname(dirname(static::getDirnameOfModel())).'/Renderer/Views/_PAGES/';
        return $path.base64_decode($guid);
    }

    protected static function getFilenames(array $requires = []) : array {
        $path = dirname(dirname(static::getDirnameOfModel())).'/Renderer/Views/_PAGES/';
        if(isset($requires['theme'])){
            $theme = $requires['theme'];
        }else{
            $theme = 'default';
        }
        if(isset($requires['type'])){
            switch ($requires['type']) {
                case 'css':
                    $dir = 'stylesheets';
                    $ext = '.css';
                    break;

                case 'js':
                    $dir = 'scripts';
                    $ext = '.js';
                    break;
                
                default:
                    $dir = 'nimls';
                    $ext = '.niml';
                    break;
            }
        }else{
            $dir = 'nimls';
            $ext = '.niml';
        }
        return self::getFilenamesInDir($path.$theme.'/'.$dir.'/', $ext, true);
    }
    
    protected static function buildModelProperties($filename, $content) : array {
        $search = dirname(dirname(static::getDirnameOfModel())).'/Renderer/Views/_PAGES/';
        $basename = str_replace($search, '', $filename);
        return [
            'guid'      =>  base64_encode($basename),
            'basename'  =>  $basename
        ];
    }

    protected static function buildFileContent($content, $properties) : string {
        return $content;
    }
}