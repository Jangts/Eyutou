<?php
namespace Cloud\Tables\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Default' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'MVCRows' => [
			'methods'	=>	[
				'getall'		=>	[
					'minArgsLength'	=>	0
				],
				'getrow'		=>	[
					'minArgsLength'	=>	1
				],
				'getrows'		=>	[
					'minArgsLength'	=>	0
				]
			]
		]
	];
}