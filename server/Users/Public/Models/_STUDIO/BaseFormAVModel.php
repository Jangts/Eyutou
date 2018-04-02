<?php
namespace PM\_STUDIO;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

use Lib\models\DocumentElementModel;

abstract class BaseFormAVModel extends BaseAdminViewModel {
	use traits\form;
	public static $__avmtabs = [];
}