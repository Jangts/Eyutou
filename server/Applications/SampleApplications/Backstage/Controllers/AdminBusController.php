<?php
namespace Lailihong\Backstage\Controllers;

// 引入相关命名空间，以简化书写
use Status;

use Request;
use App;
use Passport;
use AF\Routers\BaseRouter;
use AF\Controllers\BasePrivateController;
use Tangram\MODEL\UserModel;

class AdminBusController extends BasePrivateController {

    public function __construct(App $app, Request $request){
        $this->request = $request;
        if(isset($request->ARI->patharr[0])){
            $this->app = new App($request->ARI->patharr[0]);
        }else{
            $this->app = $app;
        }
    }

    public function checkAuthority($type = NULL, array $options = []){
        // if(Passport::hasVisaed('', 'STUDIO')){
        if($admininfo = Passport::inGroup('Administrators', false)){
            // 语言设置仅对严谨模式有效
            if(!empty($admininfo['language'])){
                $GLOBALS['NEWIDEA']->LANGUAGE = $admininfo['language'];
            }
            return $admininfo;
        }else{
            $this->reject();
        }
    }

    public function reject($href = NULL){
        global $NEWIDEA;
        $viewModel = 'LoginAVModel';
        $fullclassname = '\Lailihong\Backstage\Models\\'.$viewModel;
        $filename = CACAP.'Models/'.$viewModel;
        $NEWIDEA->load($filename);
        $model = new $fullclassname;
        $model->init($GLOBALS['NEWIDEA']->AA, $this->request, 'default')->analysis($this->request)->render();     
    }
    
    public function render($patharr){
        global $NEWIDEA;
        $admininfo = $this->checkAuthority();
        $app = $this->app;
        if(isset($patharr[2])){
            $classname = BaseRouter::correctClassName($patharr[2]);
            $viewModel = $classname.'AVModel';
        }else{
            $viewModel = 'IndexAVModel';
        }
        $fullclassname = '\\'.$app->xProps['Namespace'].'\\Models\\'.$viewModel;
        $filename = $app->Path.'Models/'.$viewModel;
        $NEWIDEA->load($filename);
        if(class_exists($fullclassname)){
            $model = new $fullclassname;
            $model->init($app, $this->request)->analysis($admininfo)->render();
        }else{
            new Status(1442.2, 'Admin View Model Not Found', "Class $fullclassname Not Found! Files of application [$app->Name] may have been tampered.", true);
        }
        return;
    }
}