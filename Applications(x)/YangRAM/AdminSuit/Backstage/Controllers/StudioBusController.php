<?php
namespace Admin\Backstage\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Tangram\ClassLoader;
use Request;
use App;

use AF\Routers\BaseRouter;
use AF\Controllers\BasePrivateController;
use Passport;
use Tangram\MODEL\UserModel;

class StudioBusController extends BasePrivateController {

    public function __construct(App $app, Request $request){
        $this->request = $request;
        if(isset($request->ARI->patharr[0])){
            $this->app = new App($request->ARI->patharr[0]);
        }else{
            $this->app = $app;
        }
    }

    public function checkPower(){
        // if(Passport::hasVisaed('', 'STUDIO')){
        if($admininfo = Passport::inGroup('Administrators', false)){
            // 语言设置仅对严谨模式有效
            $GLOBALS['NEWIDEA']->LANGUAGE = $admininfo['LANGUAGE'];
            return $admininfo;
        }else{
            $this->reject();
        }
    }

    public function reject($href = NULL){
        $viewModel = 'Admin_LoginViewModel';
        $fullclassname = '\Admin\Backstage\Models\\'.$viewModel;
        $filename = AP_CURR.'Models/'.$viewModel;
        ClassLoader::execute($filename);
        $model = new $fullclassname;
        $model->init($GLOBALS['NEWIDEA']->AA, $this->request, 'default')->analysis($this->request)->render();     
    }
    
    public function render($patharr){
        $admininfo = $this->checkPower();
        $app = $this->app;
        if(isset($patharr[1])){
            $classname = BaseRouter::correctClassName($patharr[1]);
            $viewModel = 'Admin_'.$classname.'ViewModel';
        }else{
            $viewModel = 'Admin_MainViewModel';
        }
        $fullclassname = '\\'.$app->xProps['Namespace'].'\\Models\\'.$viewModel;
        $filename = $app->Path.'Models/'.$viewModel;
        ClassLoader::execute($filename);
        if(class_exists($fullclassname)){
            $model = new $fullclassname;
            $model->init($app, $this->request)->analysis($admininfo)->render();
        }else{
            new Status(1442.2, 'Admin View Model Not Found', "Class $fullclassname Not Found! Files of application [$app->Name] may have been tampered.", true);
        }
        return;
    }
}