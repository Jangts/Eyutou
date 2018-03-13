<?php
namespace Lib\data;

use Tangram\MODEL\ObjectModel;
/**
 *
 */
class DirFiles extends ObjectModel {
	private $path;

	protected $readonly = true;

	public function __construct($path){
		$this->path = str_replace('//', '/', $path.'/');
		$this->__build($this->path);
	}

	private function __build($path){
		$files = glob($path.'*');
		foreach($files as $file){
			if (!is_dir($file)) {
				$index = str_replace($this->path, '', $file);
				$this->modelProperties[$index] = md5_file($file);
	        } else {
	            $this->__build($file.'/');
	        }
	    }
	}

	public function compare($path){
		if(is_array($path)){
			$diff = $this->diff($path);
			var_dump($diff);
		}
		if(is_string($path)&&is_dir($path)){
			$files = new self($path);
			$diff = $this->diff($files->getArrayCopy());
			var_dump($diff);
		}
		if(is_a($path, 'Lib\etc\Dir')){
			$diff = $this->diff($path->getArrayCopy());
			var_dump($diff);
		}
		return false;
	}
}
