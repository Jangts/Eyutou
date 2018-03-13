<?php
namespace Lib\models;

/**
 * @class Lib\models\PageListModel
 */
Class PageListModel implements \DataModel {
    use \Tangram\MODEL\traits\magic;
	use \Tangram\MODEL\traits\arraylike;
	
	private static $config = [
		'CURRENT_PAGE'		=>	1,
		'ANCHOR_NUMBER'		=>	9,
		'ITEM_COUNT'		=>	0,
		'PRE_PAGE_ITEM'		=>	10
	];
	
	public static function config($key, $val=NULL){
		if(is_string($key)&&isset(self::$config[$key])&&$val){
			self::$config[$key]=$val;
		}
		if(is_array($key)){
			foreach($key as $index=>$val){
				self::config($index, $val);
			}
		}
	}

	public static function staticGetCopy($config =[]){
		self::config($config);
		return new PageListModel(self::$config["CURRENT_PAGE"], self::$config["ANCHOR_NUMBER"], ceil(self::$config["ITEM_COUNT"]/self::$config["PRE_PAGE_ITEM_NUMBER"]));
	}

	protected
	$modelProperties = [
		'currentPage' 		=>	1,
		'maxAnchorNumber'	=>	9,
		'pageNumber'		=>	1,
		'data'				=> [
			'c'	=>	1,
			'f'	=>	1,
			'p'	=>	1,
			'n'	=>	1,
			'l'	=>	1,
			's'	=>	1,
			'e'	=>	1,
		],
		'length'			=>	1
	];
	
	public function __construct($currentPage = NULL, $maxAnchorNumber = NULL, $pageNumber = NULL) {
		$opts = 0;
		if(is_numeric($currentPage)){
			$this->modelProperties['currentPage'] = $currentPage > 0 ? intval($currentPage) : 1;
			$opts++;
		}
		if(is_numeric($maxAnchorNumber)){
			$this->modelProperties['maxAnchorNumber'] = intval($maxAnchorNumber);
			$opts++;
		}
		if(is_numeric($pageNumber)){
			$this->modelProperties['pageNumber'] = intval($pageNumber);
			$opts++;
		}
		if($opts){
			$this->__put();
		}
	}

	protected function __put(){
		$data = [];
		$length = 0;

		$data["c"] = $this->currentPage;
		$data["f"] = 1;
		$data["p"] = $this->currentPage > 1 ? $this->currentPage - 1 : 1;
		$data["n"] = $this->currentPage < $this->pageNumber ? $this->currentPage + 1 : $this->pageNumber;
		$data["l"] = $this->pageNumber;
		$data["s"] = $this->currentPage > (ceil($this->maxAnchorNumber / 2) - 1) ? $this->currentPage - ceil($this->maxAnchorNumber / 2) + 1 : 1;
		$data["e"] = $this->pageNumber - $this->currentPage > floor($this->maxAnchorNumber / 2) ? $this->currentPage + floor($this->maxAnchorNumber / 2) : $this->pageNumber;
		
		for ($n = $data["s"]; $n <= $data["e"]; $n++) {
			$data[] = $n;
			$length++;
		}
		$this->modelProperties['data'] = $data;
		$this->modelProperties['length'] = $length;
	}

	final public function get($name){
        if(isset($this->modelProperties[$name])){
            return $this->modelProperties[$name];
		}
		if(isset($this->modelProperties['data'][$name])){
            return $this->modelProperties['data'][$name];
        }
        return NULL;
    }

	final public function has($name){
        return false;
    }

    public function uns($name){
        return $this;
    }

	public function render(
		$gotoPreviousAnchorname = 'Previous',
		$gotoNextAnchorname = 'Next',
		$gotoFirstAnchorname = NULL,
		$gotoLastAnchorname = NULL,
		$listTagClassname = 'page-list-item',
		$currentTagClassname = 'curr',
		$useOnclickAttr = true
	){
		echo $this->str(
			$gotoPreviousAnchorname,
			$gotoNextAnchorname,
			$gotoFirstAnchorname,
			$gotoLastAnchorname,
			$listTagClassname,
			$currentTagClassname,
			$useOnclickAttr
		);
	}

	final public function set($name, $value){
        if(array_key_exists($name, $this->modelProperties)){
            $this->modelProperties[$name] = $value;
		}
		$this->__put();
        return $value;
    }

	public function setPageNumberByCount($totalItemNumber, $prePageItemNumber = 7) {
		$prePageItemNumber = $prePageItemNumber or 7;
		$this->pageNumber = ceil($totalItemNumber / $prePageItemNumber);
		$this->__put();
	}

	public function str(
		$gotoPreviousAnchorname = 'Previous',
		$gotoNextAnchorname = 'Next',
		$gotoFirstAnchorname = NULL,
		$gotoLastAnchorname = NULL,
		$listTagClassname = 'page-list-item',
		$currentTagClassname = 'curr',
		$useOnclickAttr = true
	){
		$data = $this->modelProperties['data'];
		$length = $this->modelProperties['length'];
		$html = '';
		if($useOnclickAttr){
			if($length > 0){
				if($gotoFirstAnchorname){
					$html .= '<li class="'.$listTagClassname.'" onclick="window.location.href=\'?page='.$data["f"].'\'">'.$gotoFirstAnchorname.'</li>';
				}
				if($this->currentPage>$data["f"]){
					$html .= '<li class="'.$listTagClassname.'" onclick="window.location.href=\'?page='.$data["p"].'\'">'.$gotoPreviousAnchorname.'</li>';
				}
				for ($n = 0; $n < $length; $n++) {
					if ($data[$n] == $this->currentPage) {
						$html .= '<li class="'.$listTagClassname.' '.$currentTagClassname.'">'.$data[$n].'</li>';
					}else{
						$html .= '<li class="'.$listTagClassname.'" onclick="window.location.href=\'?page='.$data[$n].'\'">'.$data[$n].'</li>';
					}
				}
				if($this->currentPage<$data["l"]){
					$html .= '<li class="'.$listTagClassname.'" onclick="window.location.href=\'?page='.$data["n"].'\'">'.$gotoNextAnchorname.'</li>';
				}
				if($gotoLastAnchorname){
					$html .= '<li class="'.$listTagClassname.'" onclick="window.location.href=\'?page='.$data["l"].'\'">'.$gotoLastAnchorname.'</li>';
				}
			}
		}else{
			if($length > 0){
				if($gotoFirstAnchorname){
					$html .= '<li class="'.$listTagClassname.'"><a href="?page='.$data["f"].'">'.$gotoFirstAnchorname.'</a></li>';
				}
				if($this->currentPage>$data["f"]){
					$html .= '<li class="'.$listTagClassname.'"><a href="?page='.$data["p"].'">'.$gotoPreviousAnchorname.'</a></li>';
				}
				for ($n = 0; $n < $length; $n++) {
					if ($data[$n] == $this->currentPage) {
						$html .= '<li class="'.$listTagClassname.' '.$currentTagClassname.'"><a href="javascript:;">'.$data[$n].'</a></li>';
					}else{
						$html .= '<li class="'.$listTagClassname.'"><a href="?page='.$data[$n].'">'.$data[$n].'</a></li>';
					}
				}
				if($this->currentPage<$data["l"]){
					$html .= '<li class="'.$listTagClassname.'"><a href="?page='.$data["n"].'">'.$gotoNextAnchorname.'</a></li>';
				}
				if($gotoLastAnchorname){
					$html .= '<li class="'.$listTagClassname.'"><a href="?page='.$data["l"].'">'.$gotoLastAnchorname.'</a></li>';
				}
			}
		}
		return $html;
	}
}