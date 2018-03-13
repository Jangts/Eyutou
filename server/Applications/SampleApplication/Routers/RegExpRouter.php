<?php
namespace App\Routers;

use Request;
use App;

class RegExpRouter extends \AF\Routers\RegExpRouter {
    protected $patterns = [
        '/<w>/<0>/columns/<0>/<*>' => [
            'controller'    =>  'RegExpController',
            'matchkeys'     =>  ['method', 'resourceid', 'column', 'others']
        ]
    ];
}