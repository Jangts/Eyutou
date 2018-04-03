<?php
namespace PM\_CLOUD;

use Storage;

/**
 * General Resource Object Model
 * 一般文档信息模型
 * 此模型的是其他资源模型的基类
**/
class FileSourceModel extends BaseCloudItemModel {
    protected static
    $fileStoragePath = DPATH.'CLOUDS/'.'sources/',
    $staticQuerier,
    $staticMemorizeStorage = [],
    $staticFileStorage,
    $tablenameAlias = 'sources_public',
	$defaultPorpertyValues = [
        'SID'               =>  0,
        'HASH'              =>  '',
        'LOCATION'          =>  '',
        'MIME'              =>  '',
        'IMAGE_SIZE'        =>  '',
        'WIDTH'             =>  0,
        'HEIGHT'            =>  0,
        'DURATION'          =>  0
    ];

    protected static function moveUploadedFile($type, $input, $extn){
        switch($type){
			case 'image':
			case 'audio':
            case 'video':
            $dir = ucfirst($type).'s/'.date("Y-m-d").'/';
            break;

            default:
            $dir = 'Docs/'.date("Y-m-d").'/';
        }
        
		if (!is_dir(PUBL_PATH.$dir)) {
			mkdir(PUBL_PATH.$dir);
        }
        
        if($input["tmp_name"]){
            $hash = md5_file($input["tmp_name"]);
            $path = $dir.$hash.'.'.$extn;
            move_uploaded_file($input["tmp_name"], PUBL_PATH.$path);
        }else{
            $hash = md5($input["blob"]);
            $path = $dir.$hash.'.'.$extn;
            file_put_contents(PUBL_PATH.$path, $input["blob"]);
        }
		return [$path, $hash];
    }
    
    public static function completeInput($input, $extn, $type){
        list($path, $hash) = static::moveUploadedFile($type, $input, $extn);
        switch($type){
            case 'image':
            $size = getimagesize(PUBL_PATH.$path);
            return array_merge($input, [
                'LOCATION' 		=>	 $path,
                'HASH'          =>   $hash,
                'IMAGE_SIZE'    =>	 $size[3],
                'WIDTH'         =>	 $size[0],
                'HEIGHT'        =>	 $size[1]
            ]);

            default:
            return array_merge($input, [
                'LOCATION' 	=>	 $path,
                'HASH'          =>   $hash
            ]);
        }
    }

    /**
     * 检查源是否存在
     * 并排差是否有被引用，如果有则返回SID
     * 如果没有，则删除源文件
     */
    public static function checkSourceExisted($SID){
        $querier = static::initQuerier();
        $result = $querier->where('SID', $SID)->take(1)->select('SID, LOCATION');
        if($result&&$row = $result->item()){
            if($count = FileMetaModel::getCountOfSrouce($SID)){
                return [$SID, $count];
            }else{
                if(is_file(PUBL_PATH.$row['LOCATION'])){
                    \unlink(PUBL_PATH.$row['LOCATION']);
                }
                $querier->where('HASH', $hash)->delete();
            }
		}
		return false;
    }

    /**
     * 检查哈希值是否存在
     * 并排差是否有被引用，如果有则返回SID
     * 如果没有，则删除源文件
     * 主要用于秒传时检查文件源是否已存在
     */
    public static function checkHashExisted($hash){
        $querier = static::initQuerier();
        $result = $querier->where('HASH', $hash)->take(1)->select('SID, LOCATION');
        if($result&&$row = $result->item()){
            if($count = FileMetaModel::getCountOfSrouce($row['SID'])){
                return [$row['SID'], $count];
            }else{
                if(is_file(PUBL_PATH.$row['LOCATION'])){
                    \unlink(PUBL_PATH.$row['LOCATION']);
                }
                $querier->where('HASH', $hash)->delete();
            }
		}
		return false;
    }

    public static function byGUID($SID){
        static::init();
        $obj = new static;
        if(static::$staticFileStorage&&($cache = static::$staticFileStorage->take($SID))){
            return $obj->__put($cache, true);
        }else{
            $result = static::$staticQuerier->requires()->where('SID', $SID)->take(1)->select();
            if($result&&$row = $result->item()){
                if(static::$staticFileStorage){
                    static::$staticFileStorage->store($row['SID'], $row);
                }
                return $obj->__put($row, true);
    		}
        }
        return false;
    }

    public static function byHASH($HASH){
        static::init();
        $obj = new static;
		$result = static::$staticQuerier->requires()->where('HASH', $HASH)->take(1)->select();
		if($result&&$row = $result->item()){
            if(static::$staticFileStorage){
                static::$staticFileStorage->store($row['SID'], $row);
            }
            return $obj->__put($row, true);
        }
        return false;
    }

    /**
     * 创建新的文件源
     */
    public static function post(array $input){
        $obj = new static;
        if(isset($input['SID'])){
            unset($input['SID']);
        }
        $obj->__put($input);
        return $obj->__insert();
    }

    public static function postIfNotExists(array $input){
        if(empty($input['SID'])){
            if(empty($input['HASH'])){
                return false;
            }else{
                if($obj = static::byHASH($input['HASH'])){
                    return $obj;
                }
                return static::post($input);
            }
        }
        return false;
    }

    public $error_msg;

     /**
     * 只接受传入数组
     */
    protected function __construct(){
		static::init();
        $this->modelProperties = [];
    }
    
    /**
     * 初始化实例数据
     */
    protected function __put(array $input, $isSaved = false){
        foreach(static::$defaultPorpertyValues as $key=>$val){
            if(isset($input[$key])){
                $this->modelProperties[$key] = $input[$key];
            }else{
                $this->modelProperties[$key] = $val;
            }
        }
        if($isSaved){
            $this->savedProperties = $this->modelProperties;
        }
        return $this;
    }

    protected function __insert(){
        $querier = static::$staticQuerier;
        unset($this->modelProperties['SID']);
        if(!$querier->insert($this->modelProperties)){
            return false;
        }
        $this->modelProperties['SID'] = $querier->lastInsertId('SID');
        $this->savedProperties = $this->modelProperties;
        return $this;
    }
    
    protected function __update(){
        $querier = static::$staticQuerier;
        if(empty($this->modelProperties['SID'])){
            return false;
        }
        unset($this->modelProperties['SID']);
        $diff = static::array_diff($this->savedProperties, $this->modelProperties, static::DIFF_SIMPLE);
        $update = $diff['__M__'];

        $this->modelProperties['SID'] = $this->savedProperties['SID'];
        if(count($update)==0){
            return $this;
        }
        if($querier->requires()->where('SID', $this->savedProperties['SID'])->update($update)!==false){
            $this->savedProperties = $this->modelProperties;
        }else{
            return false;
        }
        if(static::$staticFileStorage){
            static::$staticFileStorage->store($this->modelProperties['SID']);
        }
        return $this;
    }
    
    public function destroy(){
        $SID = $this->modelProperties['SID'];
        $resources = FileMetaModel::getFilesBySrouceID($SID);
        if(count($resources)){
            $this->error_msg = 'STILL_IN_USE';
            return false;
        }
        if(static::$staticQuerier->requires()->where('SID', $SID)->delete()!==false){
            \unlink(PUBL_PATH.$this->modelProperties['LOCATION']);
            $this->__afterDelete();
            return true;
        }
        $this->error_msg = 'SQL_ERROR';
        return false;
    }
    
    public function clearRelativeCache(){
        if(static::$staticFileStorage){
            static::$staticFileStorage->store($this->__guid);
        }
        return true;
    }
}