<?php
namespace Cloud\Files\Controllers;

// 引入相关命名空间，以简化书写
use PM\_CLOUD\FileModel;
use PM\_CLOUD\FileMetaModel;
use PM\_CLOUD\FileSourceModel;

class SourcesController extends \AF\Controllers\BaseResourcesController {
    use \Cloud\Files\Controllers\traits\authorities;
    
    public function get($id, array $options = []){
        if($id){
            if(is_numeric($id)){
                list($srcid, $filecount) = FileSourceModel::checkSourceExisted($id);
            }else{
                list($srcid, $filecount) = FileSourceModel::checkHashExisted($id);
            }
            if($srcid){
                echo json_encode(array(
                    'code'		=>	'OK',
                    'srcid'		=>	$srcid,
                    'count'	    =>	$filecount
                ));
            }
            else{
                echo json_encode(array(
                    'code'		=>	'NON',
                    'srcid'		=>	0,
                    'count'	    =>	0
                ));
            }
        }else{
            # 按照标准，应该是回列表
            # 但是source是不可能暴露列表的
        }
    }
}