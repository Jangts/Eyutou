<?php
namespace Services\Users\Models;

use Tangram\MODEL\UserModel;

final class MemberModel extends \AF\Models\BaseMemberModel {

    protected static
    $tablename = DB_REG.'users',
    $encryptedFields = ['password'],
    $defaultPorpertyValues = [
        'uid'           =>  -1,
        'status'        =>  '0',
        'username'      =>  'guest',
        'nickname'      =>  'Guest',
        'unicodename'   =>  'Guest',
        'avatar'        =>  NULL,
        'email'         =>  '',
        'mobile'        =>  '',
        'regtime'       =>  '1'.DATETIME,
        'password'      =>  '',
        'remark'        =>  ''
    ];

    public function checkPinCode($pin){
        return true;
    }

    public function getUserAccountCopy(){
        return new UserModel($this->modelProperties['uid'], UserModel::QUERY_UID);
    }

    public function getBaseInfo(){
        if($this->savedProperties){
            return [
                'uid'           =>  $this->__guid,
                'username'      =>  $this->savedProperties['username'],
                'nickname'      =>  $this->savedProperties['nickname'],
                'avatar'        =>  $this->savedProperties['avatar']
            ];
        }
        return [
            'uid'           =>  -1,
            'username'      =>  'guest',
            'nickname'      =>  'Guest',
            'avatar'        =>  NULL
        ];
    }
}
