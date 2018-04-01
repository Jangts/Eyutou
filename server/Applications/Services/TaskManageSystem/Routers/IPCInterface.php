<?php
namespace Services\Task\Routers;

// 引入相关命名空间，以简化书写
use Status;
use PM\_CLOUD\TRGroupModel;

class IPCInterface extends \AF\Routers\BaseIPCInterface {
	protected $presets = [
		'master'	=>	[
			'controller'	=>	'master',
			'methodname'	=>	'main'
		]
	];
}