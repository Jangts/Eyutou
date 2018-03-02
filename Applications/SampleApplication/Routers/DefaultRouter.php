<?php
namespace App\Routers;

class DefaultRouter extends \AF\Routers\SLRouterWithAlias {
    protected $controllers = [
		'slrctrlr'	=>	[
			'classname'	=>	'defaultController',
			'methods'	=>	[
				'default'		=>	[
					'methodname'	=>  'main',
					'minArgsLength'	=>	0
				]
			]
		]
	];
}