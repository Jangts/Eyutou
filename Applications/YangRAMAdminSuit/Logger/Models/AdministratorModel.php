<?php
namespace Admin\Logger\Models;

final class AdministratorModel extends \AF\Models\BaseMemberModel {

    protected static
    $tablename = DB_PUB.'administrators',
    $uniqueIndexes = ['UID'],
    $encryptedFields = ['PIN'],
    $defaultPorpertyValues = [
        'UID'           =>  -1,
        'OPERATORNAME'  =>  'NonAuth User',
        'PIN'           =>  '3fbfeb2d38abd0ddeb7976f78eb655d1',
        'OGROUP'        =>  '0',
        'AVATAR'        =>  __aurl__.'uploads/files/ca28525a8b386236136.jpg',
        'LANGUAGE'      =>  REQUEST_LANGUAGE
    ];

    private $encodePinCode;

    public function checkPinCode($pin){
        if($this->encodePinCode){
            if(static::encrypt($pin) === $this->encodePinCode){
                return true;
            }
        }else{
            if($row=$this->querier->requires()->where('UID', $this->savedProperties['UID'])->where('PIN', static::encrypt($pin))->select('PIN')->item()){
                $this->encodePinCode = $row['PIN'];
                return true;
            }
        }
        return false;
    }

    public function getBaseInfo(){
        if($this->savedProperties){
            return [
                'uid'           =>  $this->__guid,
                'nickname'      =>  $this->savedProperties['OPERATORNAME'],
                'avatar'        =>  $this->savedProperties['AVATAR'],
                'language'      =>  $this->savedProperties['LANGUAGE']
            ];
        }
        return [
            'uid'           =>  -1,
            'nickname'      =>  'NonAuth User',
            'avatar'        =>  __aurl__.'uploads/files/ca28525a8b386236136.jpg',
            'language'      =>  REQUEST_LANGUAGE
        ];
    }
}
