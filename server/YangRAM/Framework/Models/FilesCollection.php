<?php
namespace AF\Models;

use Status;
use Storage;
use Tangram\MODEL\ObjectModel;

/**
 * @class AF\Models\FilesCollection
 * File Based Model
 * 基于单一文件的数据模型
 * 
 * @abstract
 * @author     Jangts
 * @version    5.0.0
**/
class FilesCollection  implements \Collection {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

    
}