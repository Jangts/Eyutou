<?php
namespace Eyutou\Spider\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Queue'	=>	[
			'methods'	=>	[
				'main'	=>	[
					'minArgsLength'	=>	1
				]
			]
		]
	];
}