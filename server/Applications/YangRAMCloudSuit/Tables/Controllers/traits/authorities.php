<?php
namespace Cloud\Tables\Controllers\traits;

use Passport;
use PM\_CLOUD\TableMetaModel;
use PM\_CLOUD\TRGroupModel;
use PM\_CLOUD\TableRowMetaModel;
use PM\_CLOUD\TableAuthorityModel;

trait authorities {
    public static function getTablenameByOptions(array $options){
        if(isset($options['tablename'])){
            return $options['tablename'];
        }
        if(isset($options['tables'])){
            return $options['tables'];
        }
        if(isset($options['id'])&&$row = TableRowMetaModel::byGUID($options['id'])){
            return $row->TABLENAME;
        }
        if(isset($options['rows'])&&$row = TableRowMetaModel::byGUID($options['rows'])){
            return $row->TABLENAME;
        }
        if(isset($options['folder'])&&$folder = TRGroupModel::byGUID($options['folder'])&&$folder->tablename){
            return $folder->tablename;
        }
        if(isset($options['folders'])&&$folder = TRGroupModel::byGUID($options['folders'])&&$folder->tablename){
            return $folder->tablename;
        }
        return 'articles';
    }

    public function checkAuthority($type, array $options = []){
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        switch($type){
            case 'C':
            # 需要检查投递限制设置，以及当前用户的投递情况

            case 'R':
            case 'U':
            case 'D':
            case 'A':
            return TableAuthorityModel::can($type, ['tablename'=>$tablename]);

            case 'P':
            return $this->checkReviewAuthority($options) || $this->checkAdminAuthority($options);

            
            return $this->checkAdminAuthority($options);
        }
        return false;
    }

    public function checkAdminAuthority(array $options = []) : bool {
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        return TableAuthorityModel::can('A', ['tablename'=>$tablename]);
    }

    public function checkReadAuthority(array $options = []) : bool {
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        return TableAuthorityModel::can('R', ['tablename'=>$tablename]);
    }

	public function checkCreateAuthority(array $options = []) : bool {
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        return TableAuthorityModel::can('C', ['tablename'=>$tablename]);
    }

    public function checkUpdateAuthority(array $options = []) : bool {
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        return TableAuthorityModel::can('U', ['tablename'=>$tablename]);
    }

    public function checkDeleteAuthority(array $options = []) : bool {
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        return TableAuthorityModel::can('D', ['tablename'=>$tablename]);
    }

    public function checkReviewAuthority(array $options = []) : bool {
        $tablename = self::getTablenameByOptions($options);
        if(stripos($tablename, '_')===0){
            // 以_开头的应用私有表，通用控制器不作处理
            return false;
        }
        if($table = TableMetaModel::byGUID($tablename)){
            if($table->review){
                return TableAuthorityModel::can('A', ['tablename'=>$tablename]);
            }
            return true;
        }
        return false;
    }
}