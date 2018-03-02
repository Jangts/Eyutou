<?php
namespace AF\FileStorages;
use Storage;

/**
 * Application User Data Storage
 * 应用用户数据存储仓
 * 争对子应用的用户专门拓展的数据存储对象
 * 保证每一款子应用独享的磁盘目录USR_PATH下的一组文件夹
**/
class UsrFiles extends Storage {
    protected
	$appid = AI_CURR,
	$cachePath,
	$encodedMode = 1,
	$isArray = true;

	public function __construct($username = 'Public'){
		$this->cachePath  = USR_PATH.$username.'/_'.AI_CURR.'/';
	}
}
