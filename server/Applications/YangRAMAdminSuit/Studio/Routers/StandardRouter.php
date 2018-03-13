<?php
namespace Admin\Studio\Routers;

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
				]
			]
		]
	];
}