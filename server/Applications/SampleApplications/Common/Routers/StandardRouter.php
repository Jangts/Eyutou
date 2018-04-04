<?php
namespace App\Routers;

class StandardRouter extends \AF\Routers\StandardRouter {
    protected $controllers = [
		'Default' => [
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'PHPTest'	=>	[
			'methods'	=>	[
				'strlen'		=>	[
					'minArgsLength'	=>	0
				],
				'destruct'	=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'CrawlerTest'	=>	[
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'DataTest'	=>	[
			'methods'	=>	[
				'arrayobj'	=>	[
					'minArgsLength'	=>	0
				],
				'listobj'	=>	[
					'minArgsLength'	=>	0
				],
				'dataobj'	=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'ModelsTest'	=>	[
			'methods'	=>	[
				'restraint'		=>	[
					'minArgsLength'	=>	0
				],
				'xtdattrs'	=>	[
					'minArgsLength'	=>	0
				],
				'useraccount'	=>	[
					'minArgsLength'	=>	0
				],
				'usergroup'	=>	[
					'minArgsLength'	=>	0
				],
				'passport'	=> [
					'minArgsLength'	=>	0
				],
				'localdict'	=>	[
					'minArgsLength'	=>	0
				],
				'formview'	=>	[
					'minArgsLength'	=>	0
				],
				'listview'	=>	[
					'minArgsLength'	=>	0
				],
				'tableview'	=>	[
					'minArgsLength'	=>	0
				],
				'filebased'	=>	[
					'minArgsLength'	=>	0
				],
				'filescollection'	=>	[
					'minArgsLength'	=>	0
				]
			]
		]
	];
}