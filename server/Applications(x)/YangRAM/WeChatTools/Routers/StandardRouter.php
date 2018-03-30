<?php
namespace WX\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Default' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				]
			]
		]
	];
}