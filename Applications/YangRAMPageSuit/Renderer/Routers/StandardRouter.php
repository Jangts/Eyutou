<?php
namespace Pages\Main\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Options' => [
			'methods'	=>	[
				'update'		=>	[
					'minArgsLength'	=>	0
				]
			]
		]
	];
}