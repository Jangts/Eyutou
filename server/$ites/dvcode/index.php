<?php
require_once __DIR__.'/Controller.php';
$code = new Controller();
if(isset($_GET['m'])&&$_GET['m']==='check'){
    $code->check();
}else{
    $code->make();
}
