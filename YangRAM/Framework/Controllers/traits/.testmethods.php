<?php
namespace AF\Controllers\traits;

trait testmethods{
    private function __init_cli_test(){
        assert_options(ASSERT_ACTIVE,       1);
        assert_options(ASSERT_WARNING,      1);
        assert_options(ASSERT_BAIL,         0);
        assert_options(ASSERT_QUIET_EVAL,   0);
        assert_options(ASSERT_CALLBACK,     [$this, '__record_assert_failure']);
        $this->__testBeginTime = microtime(TRUE);
    }

    private function __record_assert_failure(){

    }

    public function __(){
        call_user_func_array('var_dump', func_get_args());
    }

    public function main(){
        exit('{"code":"200","status":"OK","msg":"Welcome to use YangRAM CLI Test!"}');
    }

    public function assertTrue(){

    }

    public function assertEmpty(){

    }
    
    public function assertNotEmpty(){

    }

    public function assertEquals(){

    }
}