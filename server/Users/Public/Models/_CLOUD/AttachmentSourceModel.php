<?php
namespace PM\_CLOUD;

use Storage;

/**
 * General Resource Object Model
 * 一般文档信息模型
 * 此模型的是其他资源模型的基类
**/
class AttachmentSourceModel extends FileSourceModel {
    protected static
    $fileStoragePath = false,
    $staticQuerier,
    $staticMemorizeStorage = [],
    $staticFileStorage,
    $tablenameAlias = 'sources_protected';

    protected static function moveUploadedFile($type, $input, $extn){
        $dir = '.attachments/'.date("Y-m-d").'/';
        
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
    

    /**
     * 检查源是否存在
     * 并排差是否有被引用，如果有则返回SID
     * 如果没有，则删除源文件
     */
    public static function checkSourceExisted($SID){
        $querier = self::initQuerier();
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
        $querier = self::initQuerier();
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
                if($obj = self::byHASH($input['HASH'])){
                    return $obj;
                }
                return self::post($input);
            }
        }
        return false;
    }
    
    public function destroy() : bool {
        $SID = $this->modelProperties['SID'];
        $resources = FileMetaModel::getFilesBySrouceID($SID);
        if(count($resources)){
            $this->error_msg = 'STILL_IN_USE';
            return false;
        }
        if(self::$staticQuerier->requires()->where('SID', $SID)->delete()!==false){
            \unlink(PUBL_PATH.$this->modelProperties['LOCATION']);
            $this->__afterDelete();
            return true;
        }
        $this->error_msg = 'SQL_ERROR';
        return false;
    }
}