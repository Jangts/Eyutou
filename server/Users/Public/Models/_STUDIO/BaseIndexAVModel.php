<?php
namespace PM\_STUDIO;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

abstract class BaseIndexAVModel extends BaseAdminViewModel {
	function buildTop(){}

	function buildSide(){}

	function buildMain(){}
}