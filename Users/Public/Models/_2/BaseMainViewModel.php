<?php
namespace PM\_2;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

abstract class BaseMainViewModel extends BaseAdminViewModel {
	function buildTop(){}

	function buildSide(){}

	function buildMain(){}
}