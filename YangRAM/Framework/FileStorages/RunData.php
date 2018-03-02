<?php
namespace AF\FileStorages;
use Storage;

/**
 * Application Temporary Data Storage
 * 应用临时数据存储仓
 * 争对应用专门拓展的另一个数据存储对象
 * 主要用来缓存应用运行时的临时数据
 * 因其应用数据存放与应用的安装目录下，所以应用升级或重装时数据会被抹掉
**/
class RunData extends Storage {
    protected
	$isArray,
	$pathname = '.tmp',
	$cachePath,
	$encodedMode = 1;

	public function __construct($pathname = 'Data', $isArray = true){
		$this->pathname = $pathname;
        $this->cachePath = DPATH.AI_CURR.'/'.$pathname.'/';
        $this->isArray = ($isArray != false);
	}
}
