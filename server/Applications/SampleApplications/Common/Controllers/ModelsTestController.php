<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use Tangram\MODEL\ObjectModel;
use Tangram\MODEL\DataList;
use App;

use Lib\models\RestraintModel;
use Lib\models\XtdAttrsModel;
use Lib\models\LocalizedDictModel;
use Tangram\MODEL\UserModel;
use Tangram\MODEL\UserGroupReadolyModel;
use Passport;
use AF\Models\FileBasedModel;
use AF\Models\FilesCollection;

class ModelsTestController extends \Controller {
    public function restraint(){
        // 测试IP
        echo "\r\n\r\n";
        echo "测试IP:";
        echo "\r\n";
        $restraint = new RestraintModel('ip', '127.0.0.1');
        var_dump($restraint, $restraint->check('192.168.1.1'));
        var_dump($restraint->check('this is not a ipv4'), $restraint->corrent('this is not a ipv4'));

        
        // 测试整型区间
        echo "\r\n\r\n";
        echo "测试整型区间:";
        echo "\r\n";
        $restraint = new RestraintModel('int', '3');
        $restraint->set('RANGE', [2, 4]);
        var_dump($restraint, $restraint->check(4));
        var_dump($restraint->check(1), $restraint->corrent(0));

        // 测试时间默认值
        echo "\r\n\r\n";
        echo "测试时间默认值:";
        echo "\r\n";
        $restraint = new RestraintModel('datetime');
        var_dump($restraint, $restraint->check('1991-01-19 13:00:00'));
        var_dump($restraint->check('today'), $restraint->corrent('yesterday'));

        // 测试跨类型浮点数
        echo "\r\n\r\n";
        echo "测试跨类型浮点数:";
        echo "\r\n";
        $restraint = new RestraintModel('float2');
        var_dump($restraint, $restraint->check(1.23));
        var_dump($restraint->check(1.234), $restraint->corrent('1.2345'));

        // 测试可选区间
        echo "\r\n\r\n";
        echo "测试可选区间:";
        echo "\r\n";
        $restraint = new RestraintModel('checkbox', '6');
        $restraint->set('RANGE', ['1', '2', '3', '4', '5', '6', '7']);
        var_dump($restraint, $restraint->check('7'));
        var_dump($restraint->check(1), $restraint->corrent(5));

        // 测试布尔值
        echo "\r\n\r\n";
        echo "测试布尔值:";
        echo "\r\n";
        $restraint = new RestraintModel('bool', 1);
        var_dump($restraint, $restraint->check(true));
        var_dump($restraint->check(false), $restraint->corrent(false));
        var_dump($restraint->check(1), $restraint->corrent(1));
        var_dump($restraint->check(0), $restraint->corrent(0));
        var_dump($restraint->check('1'), $restraint->corrent('1'));
        var_dump($restraint->check('0'), $restraint->corrent('0'));
        var_dump($restraint->check('true'), $restraint->corrent('true'));
        var_dump($restraint->check('false'), $restraint->corrent('false'));
        var_dump($restraint->check('2'), $restraint->corrent('2'));
    }

    public function xtdAttrs(){
        $attrs = [
            'name'              =>  [
                'type'      =>  'varchar',
                'length'    =>  '64',
                'default'   =>  'Yang Chi'
            ],
            'email'             =>  [
                'type'      =>  'email',
                'default'   =>  '419889729@qq.com'
            ],
            'sex'               =>  [
                'type'      =>  'radio',
                'default'   =>  '0',
                'options'   =>  ['0', '1'],
                'opt_map'   =>  ['男', '女']
            ],
            'birthday'          =>  [
                'type'      =>  'date',
                'default'   =>  '1967-01-01',
                'format'    =>  'YYYY年M月D日'
            ],
            'avatar1'           =>  [
                'type'      =>  'file'
            ],
            'avatar2'           =>  [
                'type'      =>  'imgtext'
            ],
            'tags'              =>  [
                'type'      =>  'tags',
                'split_tag' =>  ',',
                'default'   =>  ''
            ],
            'brief'             =>  [
                'type'      =>  'longtext'
            ],
            'followed'          =>  [
                'type'      =>  'is',
                'opt_map'   =>  ['未关注', '已关注']
            ],
            'chargeforfollow'   =>  [
                'type'      =>  'float2',
                'default'   =>  9.99
            ]
        ];

        $data1 = [
            'name'      =>  'Ivan Yeung',
            'email'     => 'microivan@live.com',
            'sex'       =>  0,
            'birthday'  =>  '1991-01-19',
            'avatar1'           =>  'http://img4.imgtn.bdimg.com/it/u=1121475478,2545730346&fm=27&gp=0.jpg',
            'avatar2'           =>  'data:image/jpeg;base64,Z0Omluc3RhbmNlSUQ',
            'tags'              =>  '设计师, 程序员',
            'brief'             =>  '怕是个傻子哦',
            'followed'          =>  1,
            'chargeforfollow'   =>  1.2345
        ];

        $data2 = [
            'name'      =>  'Ivan Yeung',
            'email'     => 'microivan.live.com',
            'sex'       =>  0,
            'birthday'  =>  '1991-01-19',
            'avatar1'           =>  'http://img4.imgtn.bdimg.com/it/u=1121475478,2545730346&fm=27&gp=0.jpg',
            'weibo'             =>  '@万識屋馳酱',
            'tags'              =>  '设计师, 程序员',
            'brief'             =>  '怕是个傻子哦',
            'followed'          =>  1,
            'chargeforfollow'   =>  1.2345
        ];

        $xAttrs = new XtdAttrsModel($attrs);

        $correntValues0 = $xAttrs->getDefaultValues(true);

        $correntValues1 = $xAttrs->correntValues($data1);

        $correntValues2 = $xAttrs->correntValues($data2);

        $correntValues3 = $xAttrs->correntValues($data2, true);

        $correntValues4 = $xAttrs->correntValues($data2, true, true);

        var_dump($xAttrs, $correntValues0, $correntValues1, $correntValues2, $correntValues3, $correntValues4);
    }

    public function userAccount(){
        $user1 = new UserModel();

        $user2 = new UserModel('admin');

        var_dump($user1->isA('Guests'), $user2->isA('#STUDIO_VISA'), $user2->isA('Users'), $user2->isA('#USERS_CARD'), $user2);
    }

    public function usergroup(){
        $usergroup1 = UserGroupReadolyModel::byGUID('#USERS_CARD');

        $usergroup2 = UserGroupReadolyModel::byALIAS('Users');

        $usergroup3 = UserGroupReadolyModel::byAITS('STUDIO', 'CARD', 'OPTR');

        $usergroups = UserGroupReadolyModel::getGroupsOfApp('STUDIO');

        var_dump($usergroup1->getArrayCopy(), $usergroup2->getArrayCopy(), $usergroup3, $usergroups);
    }

    public function passport(){
        $_SESSION['username'] = 'admin';
        $passport1 = Passport::getUserPassportCopy();
        $user = $passport1->getUserAccountCopy();
        $passport2 = Passport::getUserPassportCopy();
        var_dump($passport1, $passport2, $user, $user->checkPassWord('admin'), $user->checkPassWord('HiYangR7M'));
    }

    public function localDict(){
        $dict = new LocalizedDictModel('phparr');

        var_dump($dict);
    }

    public function fileBased(){
        $filedata = new FileBasedModel();
        var_dump($filedata);
    }

    public function filesCollection(){
        
    }
}