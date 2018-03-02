<?php
namespace Cloud\Files\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use PM\_CLOUD\FolderModel;
use PM\_CLOUD\FileModel;
use PM\_CLOUD\FileMetaModel;
use PM\_CLOUD\FileSourceModel;

class FilesController extends \AF\Controllers\BaseResourcesController {
	private static function getFolderID($options){
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
				$options['appid'] = AI_CURR;
			}
			if($folder = FolderModel::postIfNotExists(4, $options['appid'])){
				$id = $folder['id'];
				if(!empty($options['path'])){
					$array = explode('/', $options['path']);
					foreach($array as $name){
						if($folder = FolderModel::postIfNotExists($id, $name)){
							$id = $folder['id'];
						}else{
							new Stutus(400);
						}
					}
				}
			}else{
				new Stutus(400);
			}
			break;

			case '5':
			$id = 5;

			default:
			$folder = FolderModel::byGUID($options['folder']);
			if($folder&&$folder->id>5&&$folder->type==='file'){
				$id = $folder->id;
			}else{
				$id = 5;
			}
			return $id;
		}
	}

    public
    $successed = [],
    $failed = [];

    private function returnUploadsData($options, $is2ndPass = false){
		if(isset($options['returndetails'])){
			foreach($this->successed as $name=>$successed){
				foreach($successed as $i=>$file){
					$data= array(
						'host'		=>	HOST,
						'url'		=>	__aurl__.'uploads/files/'.$file["ID"].'.'.$file['SUFFIX'],
						'name'		=>	$file['FILE_NAME'],
						'type'		=>	$file['MIME'],
						'size'		=>	$file['FILE_SIZE'],
						'time'		=>	$file["SK_MTIME"]
					);
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
		list($id, $suffix) = FileMetaModel::getSplitFileNameArray($id);
		if($id&&$file = FileModel::byGUID($id)){
			if(is_file(PUBL_PATH.$file->LOCATION)){
				if(isset($options['sizes'])&&$file->FILE_TYPE==='image'){
					return $file->resizeImageAndTransfer($options['sizes']);
				}
				return $file->transfer();
			}else{
				$file->destroy();
			}
		}
		new Status(1440, true);
	}

    public function post($id = NULL, array $options = []){
        ignore_user_abort(true);
		set_time_limit(0);
		if(empty($_FILES)){
			$SRC_ID = $this->request->FORM->src_id;
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
	
	public function postFileMeta($SRC_ID, $options){
		$files = FileMetaModel::getFilesBySrouceID($SRC_ID, 1);
		if($files&&count($files)){
			$source = FileSourceModel::byGUID($SRC_ID);
			$meta = $files[0]->getCopy();
			$meta->FOLDER = self::getFolderID($options);
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

    public function postFiles($name, $file, $options){
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
                        // $post = $this->request->FORM->toArray();
						list($basename, $suffix, $type) = FileMetaModel::getSplitFileNameArray($filename, $file["type"][$i]);
                        $srcInput = FileSourceModel::completeInput([
                            'MIME'              =>  $file["type"][$i],
							'DURATION' 	        =>	$durations[$i],
							'tmp_name'          =>  $file["tmp_name"][$i],
							'blob'				=>  isset($file["blob"]) ? $file["blob"][$i] : '',
                        ], $suffix, $type);

                        $metaInput = [
                            'FOLDER'        	=>  self::getFolderID($options),
                            'FILE_NAME'     	=>  $filename,
                            'FILE_TYPE'     	=>  $type,
                            'FILE_SIZE'     	=>  $file["size"][$i],
                            'SUFFIX'        	=>  $suffix
                        ];
                        if($obj = FileModel::postByMateinfoAndSource($metaInput, $srcInput)){
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
    
    public function postFile($name, $file, $options){
		if(count($file['name'])){
            if(isset($file["error"])){
                if($file["error"] > 0){
					$this->failed[$name][] = [
						'filename'  => 	$file['name'],
						'error'     =>  $file["error"]
					];
				}else{
					// $post = $this->request->FORM->toArray();
					list($basename, $suffix, $type) = FileMetaModel::getSplitFileNameArray($file['name'], $file["type"]);
					$srcInput = FileSourceModel::completeInput([
						'MIME'              =>  $file["type"],
						'DURATION' 	        =>	isset($options["duration"]) ? $options["duration"] : 0,
						'tmp_name'          =>  $file["tmp_name"],
						'blob'				=>  isset($file["blob"]) ? $file["blob"] : '',
					], $suffix, $type);
					$metaInput = [
						'FOLDER'        	=>  self::getFolderID($options),
						'FILE_NAME'     	=>  $file['name'],
						'FILE_TYPE'     	=>  $type,
						'FILE_SIZE'     	=>  $file["size"],
						'SUFFIX'        	=>  $suffix
					];
					if($obj = FileModel::postByMateinfoAndSource($metaInput, $srcInput)){
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
		if(($ID = $this->request->FORM->id)&&($meta = FileMetaModel::byGUID($ID))){
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
				$files = FileMetaModel::getFilesBySrouceID($SRC_ID, 1);
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

	public function putFile($name, $meta, $file, $options){
		if(count($file['name'])){
            if(isset($file["error"])){
                if($file["error"] > 0){
					$this->failed[$name][] = [
						'filename'  => 	$file['name'],
						'error'     =>  $file["error"]
					];
				}else{
					list($basename, $suffix, $type) = FileMetaModel::getSplitFileNameArray($file['name'], $file["type"]);
					if(($type===$meta->FILE_TYPE)&&($suffix===$meta->SUFFIX)){
						if($source = FileSourceModel::postIfNotExists( FileSourceModel::completeInput([
							'MIME'              =>  $file["type"],
							'DURATION' 	        =>	isset($options["duration"]) ? $options["duration"] : 0,
							'tmp_name'          =>  $file["tmp_name"],
							'blob'				=>  isset($file["blob"]) ? $file["blob"] : '',
						], $suffix, $type))){
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
			new Status(1405, 'File Update Error', 'File Not Found', true);
		}
	}
	
	public function delete($id, array $options = []){
        if(empty($id)||($item = FileMetaModel::byGUID($id))==NULL){
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
}