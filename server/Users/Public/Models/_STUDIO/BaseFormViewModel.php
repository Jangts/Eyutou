<?php
namespace PM\_STUDIO;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

use Lib\models\DocumentElementModel;

abstract class BaseFormViewModel extends BaseAdminViewModel {
	use traits\form;
}