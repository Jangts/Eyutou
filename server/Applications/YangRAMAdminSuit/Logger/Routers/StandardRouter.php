<?php
namespace Admin\Logger\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Default' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'VISA' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				],
				'login'		=>	[
					'minArgsLength'	=>	0
				],
				'logout'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'DragVerification' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				],
				'make'		=>	[
					'minArgsLength'	=>	0
				],
				'check'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
	];
}