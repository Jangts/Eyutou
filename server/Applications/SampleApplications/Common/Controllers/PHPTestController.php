<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写

class test {
    function __construct(){
        echo 'Build';
    }

    function __destruct(){
        echo 'Destroyed';
    }
}

class PHPTestController extends \Controller {
    public function strlen(){
        echo strlen(12345);
        echo "\r\n";
        echo strlen(floatval(12345));
        echo "\r\n";
        echo strlen(12.345);
        echo "\r\n";
        echo strlen(12.34e5);
        echo "\r\n";
        echo strlen('12345');
        echo "\r\n";
        echo strlen(floatval('12345'));
        echo "\r\n";
    }

    public function destruct(){
        // 测试如果不使用unset销毁对象，而使进程自动结束，会不会执行析构函数
        $obj = new test;
        echo PHP_EOL;

        // 测试中断进程是否执行析构函数
        exit;
    }
}