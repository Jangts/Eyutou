<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Tangram\MODEL\ObjectModel;
use Tangram\MODEL\DataList;
use App;

class DataTestController extends \Controller {
    public function main(){}

    public function arrayobj(){
        $array = ['Microsoft', 'Google', 'Apple', 'Tangram'];

        $object = new \stdClass();

        $object->attr = 'Mi';

        $object->storage = $array;

        foreach($object as $element){
            var_dump($element);
        }

        $arrayObject = new \ArrayObject($array, \ArrayObject::STD_PROP_LIST);

        $arrayObject->attr = 'Mi';

        foreach($arrayObject as $element){
            var_dump($element);
        }

        echo $arrayObject->count();
        echo "\r\n";

        echo count($arrayObject);
        echo "\r\n";

        $arrayObject2 = new \ArrayObject(['Microsoft', 'Google', 'Apple', 'Tangram', 'key'=>'value'], \ArrayObject::ARRAY_AS_PROPS);

        $arrayObject2->attr = 'Mi';

        foreach($arrayObject2 as $element){
            var_dump($element);
        }

        var_dump($array, serialize($array), $object, serialize($object), $arrayObject, $arrayObject->getArrayCopy(), serialize($arrayObject), $arrayObject2, $arrayObject2->getArrayCopy(), serialize($arrayObject2), \ArrayObject::STD_PROP_LIST, \ArrayObject::ARRAY_AS_PROPS);
    }

    public function listobj(){
        $array = ['Microsoft', 'Google', 'Apple', 'Tangram'];

        $listObject = new DataList($array);

        if(!is_a($listObject, 'Collection')){
            die('Not A Collection');
        }

        $listObject->attr = 'Mi';

        $listObject->append('Mi');

        foreach($listObject as $element){
            var_dump($element);
        }

        echo $listObject[3];
        
        var_dump($array, $listObject, $listObject->getArrayCopy(), serialize($listObject));

        echo $listObject->json_encode();

        echo $listObject->xml_encode();
    }

    public function dataobj(){
        $array = [
            'USA'   =>  ['Microsoft', 'Google', 'Apple'], 
            'CHN'   =>  'Tangram'];

        $dataObject = ObjectModel::enclose($array);

        if(!is_a($dataObject, 'DataModel')){
            die('Not A Model');
        }

        $dataObject->attr = 'Mi';

        foreach($dataObject as $attr => $element){
            $listObjec[$attr] = 'Unknow';
            var_dump($element);
        }

        echo $dataObject['CHN'];

        $dataObject->CHN = 'Tencent';

        echo $dataObject->CHN;
        
        var_dump($array, $dataObject, $dataObject->getArrayCopy(), serialize($dataObject));

        echo $dataObject->json_encode();

        echo $dataObject->xml_encode();
    }
}