<?php
namespace Mall\Goods\Routers;

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