<?php
namespace Cloud\Files\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use PM\_CLOUD\FolderModel;

class FilesController extends \AF\Controllers\BaseResourcesController {
	use \Cloud\Files\Controllers\traits\authorities;

	protected static
	$fullmodel = 'PM\_CLOUD\FileModel',
	$metamodel = 'PM\_CLOUD\FileMetaModel',
	$srcmodel  = 'PM\_CLOUD\FileSourceModel';

	protected static function getFolderID($options){
		if(empty($options['folder'])){
			$options['folder'] = 5;
		}
		switch($options['folder']){
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			if(empty($options['appid'])){
				$options['appid'] = CACAI;
			}
			if($folder = FolderModel::postIfNotExists($options['appid'], 'F', ['parent' => 4])){
				$id = $folder['id'];
				if(!empty($options['path'])){
					$array = explode('/', $options['path']);
					foreach($array as $name){
						if($folder = FolderModel::postIfNotExists($name, 'F', ['parent' => $id])){
							$id = $folder['id'];
						}else{
							new Status(400, true);
						}
					}
				}
			}else{
				new Status(400, true);
			}
			break;

			case '5':
			$id = 5;
			break;

			default:
			$folder = FolderModel::byGUID($options['folder']);
			if($folder&&$folder->id>5&&$folder->type==='F'){
				$id = $folder->id;
			}else{
				new Status(400, true);
			}
		}
		return $id;
	}

    public
    $successed = [],
	$failed = [];
	
	protected function checkFileModification($filename) {
        if(is_file($filename)){
            $lastModified = filemtime($filename);
            if($this->checkResourceModification($lastModified)){
                return $filename;
            }
            return false;
        }
        return $filename;
	}

    protected function returnUploadsData($options, $is2ndPass = false){
		if(isset($options['returndetails'])){
			foreach($this->successed as $name=>$successed){
				foreach($successed as $i=>$file){
					$data= [
						'host'		=>	HOST,
						'src'		=>	PUBL_PATH.$file["LOCATION"],
						'url'		=>	__aurl__.'uploads/files/'.$file["ID"].'.'.$file['FILE_EXTN'],
						'name'		=>	$file['FILE_NAME'],
						'type'		=>	$file['MIME'],
						'size'		=>	$file['FILE_SIZE'],
						'time'		=>	$file["SK_MTIME"]
					];
					switch($file['FILE_TYPE']){
						case 'image':
						$data["dimen"] = $file["IMAGE_SIZE"];
						$data["width"] = $file["WIDTH"];
						$data["height"] = $file["HEIGHT"];
						break;	
						case 'video':
						$data["width"] = $file["WIDTH"];
						$data["height"] = $file["HEIGHT"];
						case 'audio':
						$data["DURATION"] = $file["DURATION"];
						break;
					}
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
		list($id, $extn) = static::$metamodel::getSplitFileNameArray($id);
		if($id&&$file = static::$fullmodel::byGUID($id)){
			$filename = PUBL_PATH.$file->LOCATION;
			if(is_file($filename)){
				$CONFIG = $this->app->xProps['Config'];
				$localResourceMtime = filemtime($filename);
				$this->__cache($localResourceMtime, $CONFIG['fileCacheExpires'],  'public');
				if($file->FILE_TYPE==='image'){
					if($CONFIG['imageSecurityChain']&&isset($_SERVER['HTTP_REFERER'])){
						$whiteList = $CONFIG['imageWhiteList'];
						$preg = "/^(http:)?\/\/(".join('|', $whiteList).")?(\/.*)?$/";
						if(!preg_match($preg, $_SERVER['HTTP_REFERER'])){
							$file = static::$fullmodel::byFolderNameName(8, $GLOBALS['NEWIDEA']->LANGUAGE.'.jpg');
							if(!$file){
								$file = static::$fullmodel::byFolderNameName(8, 'en.jpg');
							}
						}
					}
					if(isset($options['sizes'])){
						return $file->resizeImageAndTransfer($options['sizes']);
					}
				}
				if(0){
					// 如果限速
					return $file->transfer();
				}
				header("Location: ".PUBL_URL.$file->LOCATION);
				exit;
			}else{
				$file->destroy();
			}
		}
		new Status(404, true);
	}

    public function post($id = NULL, array $options = []){
        ignore_user_abort(true);
		set_time_limit(0);
		if(empty($_FILES)){
			$SRC_ID = $this->request->INPUTS->src_id;
			if($SRC_ID){
				$this->postFileMeta($SRC_ID, $options);
			}
		}else{
			foreach($_FILES as $name=>$file){
				if(is_array($file['name'])){
					$this->successed[$name] = [];
					$this->failed[$name] = [];
					$this->postFiles($name, $file, $options);
				}else{
					$this->successed[$name] = [];
					$this->failed[$name] = [];
					$this->postFile($name, $file, $options);
				}
			}
		}
		return $this->returnUploadsData($options);
	}
	
	protected function postFileMeta($SRC_ID, $options){
		$files = static::$metamodel::getFilesBySrouceID($SRC_ID, 1);
		if($files&&count($files)){
			$source = static::$srcmodel::byGUID($SRC_ID);
			$meta = $files[0]->getCopy();
			$meta->FOLDER = static::getFolderID($options);
			$meta->FILE_NAME = $post['filename'];
			$meta->ID = substr(substr($source->HASH, 8, 16).intval(BOOTTIME).uniqid(), 0, 44);
			$meta->SK_IS_RECYCLED = 0;
			if($meta->save()){
				$this->successed['__secondpass'][] = array($meta->getArrayCopy(), $source->getArrayCopy());
			}else{
				$this->failed['__secondpass'][] = [
					'filename'  =>  $post['filename'],
					'error'     =>  0
				];
			}
		}else{
			# 不存在源
		}
	}

    protected function postFiles($name, $file, $options){
        if($count = count($file['name'])){
            if(isset($file["error"])){
				if(empty($options["durations"])){
					$durations = array_pad([], $count, 0);
				}else{
					$durations = array_pad(preg_split('/\s*,\s*/', $options["durations"]), $count, 0);
				}
                foreach($file['name'] as $i=>$filename){
                    if($file["error"][$i] > 0){
                        $this->failed[$name][] = [
                            'filename'  =>  $filename,
                            'error'     =>  $file["error"][$i]
                        ];
                    }else{
                        // $post = $this->request->INPUTS->toArray();
						list($basename, $extn, $type) = static::$metamodel::getSplitFileNameArray($filename, $file["type"][$i]);
                        $srcInput = static::$srcmodel::completeInput([
                            'MIME'              =>  $file["type"][$i],
							'DURATION' 	        =>	$durations[$i],
							'tmp_name'          =>  $file["tmp_name"][$i],
							'blob'				=>  isset($file["blob"]) ? $file["blob"][$i] : '',
                        ], $extn, $type);

                        $metaInput = [
                            'FOLDER'        	=>  static::getFolderID($options),
                            'FILE_NAME'     	=>  $filename,
                            'FILE_TYPE'     	=>  $type,
                            'FILE_SIZE'     	=>  $file["size"][$i],
                            'FILE_EXTN'        	=>  $extn
                        ];
                        if($obj = static::$fullmodel::postByMateinfoAndSource($metaInput, $srcInput)){
							$this->successed[$name][] = $obj->getArrayCopy();
						}else{
							$this->failed[$name][] = [
								'filename'  =>  $filename,
								'error'     =>  0
							];
						}
                    }
                }
            }else{
				foreach($file['name'] as $i=>$filename){
					$this->failed[$name][] = [
						'filename'  =>  $filename,
						'error'     =>  '-1'
					];
				}
            }
		}else{
			new Status(1401, 'File Upload Error', 'File Not Found', true);
		}
	}
    
    protected function postFile($name, $file, $options){
		if(count($file['name'])){
            if(isset($file["error"])){
                if($file["error"] > 0){
					$this->failed[$name][] = [
						'filename'  => 	$file['name'],
						'error'     =>  $file["error"]
					];
				}else{
					// $post = $this->request->INPUTS->toArray();
					list($basename, $extn, $type) = static::$metamodel::getSplitFileNameArray($file['name'], $file["type"]);
					$srcInput = static::$srcmodel::completeInput([
						'MIME'              =>  $file["type"],
						'DURATION' 	        =>	isset($options["duration"]) ? $options["duration"] : 0,
						'tmp_name'          =>  $file["tmp_name"],
						'blob'				=>  isset($file["blob"]) ? $file["blob"] : '',
					], $extn, $type);
					$metaInput = [
						'FOLDER'        	=>  static::getFolderID($options),
						'FILE_NAME'     	=>  $file['name'],
						'FILE_TYPE'     	=>  $type,
						'FILE_SIZE'     	=>  $file["size"],
						'FILE_EXTN'        	=>  $extn
					];
					if($obj = static::$fullmodel::postByMateinfoAndSource($metaInput, $srcInput)){
						$this->successed[$name][] = $obj->getArrayCopy();
					}else{
						$this->failed[$name][] = [
							'filename'  =>  $file['name'],
							'error'     =>  0
						];
					}
				}
            }else{
				$this->failed[$name][] = [
					'filename'  =>  $file['name'],
					'error'     =>  '-1'
				];
            }
		}else{
			new Status(1401, 'File Upload Error', 'File Not Found', true);
		}
	}

	public function put($id, array $options = []){
		if(($ID = $this->request->INPUTS->id)&&($meta = static::$metamodel::byGUID($ID))){
			ignore_user_abort(true);
			set_time_limit(0);
			if(empty($_FILES)){
				if($meta->put($_POST)->save()){
					$this->successed['__update'][] = $$meta->getExtendedModel()->getArrayCopy();
				}else{
					$this->failed['__update'][] = [
						'filename'  =>  $file['name'],
						'error'     =>  '-1'
					];
				}
			}else{
				$files = static::$metamodel::getFilesBySrouceID($SRC_ID, 1);
				foreach($_FILES as $name=>$file){
					if(is_array($file['name'])){
						# 更新一次只能传递一个文件
						# 所以只支持单文件
					}else{
						$this->successed[$name] = [];
						$this->failed[$name] = [];
						$this->putFile($name, $meta, $file, $options);
					}
				}
			}
		}else{
			$this->failed['__update'][] = [
				'error'     =>  404
			];
		}
		return $this->returnUploadsData($options);
	}

	protected function putFile($name, $meta, $file, $options){
		if(count($file['name'])){
            if(isset($file["error"])){
                if($file["error"] > 0){
					$this->failed[$name][] = [
						'filename'  => 	$file['name'],
						'error'     =>  $file["error"]
					];
				}else{
					list($basename, $extn, $type) = static::$metamodel::getSplitFileNameArray($file['name'], $file["type"]);
					if(($type===$meta->FILE_TYPE)&&($extn===$meta->FILE_EXTN)){
						if($source = static::$srcmodel::postIfNotExists( static::$srcmodel::completeInput([
							'MIME'              =>  $file["type"],
							'DURATION' 	        =>	isset($options["duration"]) ? $options["duration"] : 0,
							'tmp_name'          =>  $file["tmp_name"],
							'blob'				=>  isset($file["blob"]) ? $file["blob"] : '',
						], $extn, $type))){
							// 不支持同时更改源和名称与文件夹等信息
							if($meta->put( [
								'FILE_SIZE'     	=>  $file["size"],
								'SRC_ID'			=>	$source->SID
							])->save()){

							}
						}
					}else{
						$this->failed[$name][] = [
							'filename'  =>  $file['name'],
							'error'     =>  -2
						];
					}
				}
            }else{
				$this->failed[$name][] = [
					'filename'  =>  $file['name'],
					'error'     =>  '-1'
				];
            }
		}else{
			new Status(1403, 'File Update Error', 'File Not Found', true);
		}
	}
	
	public function delete($id, array $options = []){
        if(empty($id)||($item = static::$metamodel::byGUID($id))==NULL){
            Response::instance(1440, Response::JSON)->send(json_encode([
                'code'      =>  404,
                'staus'     =>  'Not Found',
                'msg'       =>  'File you want to delete is not found.',
                'url'       =>  $this->request->URI->src
            ]));
        }
        if($item->recycle()){
			\Controller::doneResponese([], 1204, 'Remove Successed', false);
		}
        \Controller::doneResponese([], 1404, 'Remove Faild', false);
	}

	/**
     * 文件类型检测
     * @return bool
     */
    protected function checkType($extn){
        return in_array('.'.$extn, $this->config["allowFiles"]);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    protected function  checkSize($fileSize){
        return $fileSize <= ($this->config["maxSize"]);
    }
}
