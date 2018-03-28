<?php
if(empty($_GET["code"])){
    define('__CODE__',      404);
}else{
    define('__CODE__',      intval($_GET["code"]));
}
include 'boot.php';