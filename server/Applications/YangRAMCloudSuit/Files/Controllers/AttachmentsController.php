<?php
namespace Cloud\Files\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use PM\_CLOUD\FolderModel;
use PM\_CLOUD\AttachmentModel;
use PM\_CLOUD\AttachmentMetaModel;

class AttachmentsController extends FilesController {
    protected static
	$fullmodel = 'PM\_CLOUD\AttachmentModel',
	$metamodel = 'PM\_CLOUD\AttachmentMetaModel',
	$srcmodel  = 'PM\_CLOUD\AttachmentSourceModel';

    public function checkReadAuthority(array $options = []) : bool {
        if(isset($options['attachments'])){
			# 检查下载权限
            return false;
        }
        new Status(404, true);
    }

	public function checkCreateAuthority(array $options = []) : bool {
        return $this->checkAdminAuthority($options);
    }

	protected static function getFolderID($options){
		if(empty($options['folder'])){
            if(empty($options['appid'])||isset($options['folder'])){
				$id = 0;
            }else{
                if($folder = FolderModel::postIfNotExists($options['appid'], 'A', ['parent' => 0])){
    				$id = $folder['id'];
	    			if(!empty($options['path'])){
		    			$array = explode('/', $options['path']);
			    		foreach($array as $name){
					    	if($folder = FolderModel::postIfNotExists($name, 'A', ['parent' => $id])){
				    			$id = $folder['id'];
						    }else{
                                new Status(400, true);
					    	}
				    	}
			    	}
		    	}else{
	    			new Status(400, true);
    			}
            }
		}else{
            $folder = FolderModel::byGUID($options['folder']);
			if($folder&&$folder->id>5&&$folder->type==='A'){
				$id = $folder->id;
			}else{
				new Status(400, true);
			}
        }
		return $id;
    }

    protected function returnUploadsData($options, bool $is2ndPass = false){
		if(isset($options['returndetails'])){
			foreach($this->successed as $name=>$successed){
				foreach($successed as $i=>$file){
					$data= [
						'host'		=>	HOST,
						'url'		=>	__aurl__.'uploads/attachments/'.$file["ID"].'.'.$file['FILE_EXTN'],
						'name'		=>	$file['FILE_NAME']
					];
					$this->successed[$name][$i] = $data;
				}
			}
			switch($_GET['returndetails']){
				case 'xml':
				self::doneResponese([
					'successed'	=>	$this->successed,
					'failed'	=>	$this->failed
				], 200, $is2ndPass ? 'SECONDPASS' : 'UPLOADED', true);
				break;
				case 'json':
				default:
				self::doneResponese([
						'successed'	=>	$this->successed,
						'failed'	=>	$this->failed
				], 200, $is2ndPass ? 'SECONDPASS' : 'COMPLECTED');
			}
		}
		if($is2ndPass){
			exit('SECONDPASS');
		}
		$sCount = 0;
		$fCount = 0;
		foreach($this->successed as $successed){
			$sCount += count($successed);
		}
		foreach($this->failed as $failed){
			$fCount += count($failed);
		}
		exit('UPLOADED: '.$sCount.' successed, '.$fCount.' failed.');
	}
    
    public function get($id, array $options = []){
		$this->checkAuthority('R', $options) or Status::cast('No permissions to read resources.', 1411.2);
		list($id, $extn) = AttachmentMetaModel::getSplitFileNameArray($id);
		if($id&&$file = AttachmentModel::byGUID($id)){
			$filename = PUBL_PATH.$file->LOCATION;
			if(is_file($filename)){
				$CONFIG = $this->app->xProps['Config'];
				$localResourceMtime = filemtime($filename);
				$this->__cache($localResourceMtime, $CONFIG['fileCacheExpires'],  'public');
				return $file->transfer();
			}else{
				$file->destroy();
			}
		}
		new Status(404, true);
    }
}