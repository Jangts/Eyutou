<?php
namespace AF\Storages;
use Storage;

/**
 * Application Data Storage
 * 应用数据存储仓
 * 争对子应用专门拓展的数据存储对象
 * 保证每一款子应用独享的磁盘目录RUNPATH_CA下的一个文件夹
**/
final class AppCache extends Storage {
    protected
	$appid,
	$isArray,
    $encodedMode = 1;

	public function __construct($appid = 'Common', $isArray = true, $encodedMode = Storage::JSN, $extn = '.ni'){
		$this->appid  = strtoupper($appid);
        $this->cachePath = RUNPATH_CA.$appid.'/';
        $this->isArray = ($isArray != false);
		$this->encodedMode = $encodedMode;
		$this->extn = $extn;
	}

    public function whose(){
		return $this->appid;
	}
}
