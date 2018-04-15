<?php
namespace Pages\Designer\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Templates'	=>	[
			'methods'	=>	[
				'create'	=>	[
					'minArgsLength'	=>	0
				],
				'update'	=>	[
					'minArgsLength'	=>	1
				],
				'delete'	=>	[
					'minArgsLength'	=>	1
				]
			]
		]
	];
}