<?php
namespace Eyutou\Spider\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

use Packages\NICrawler;
use Eyutou\Spider\Models\PublisherModel;

class QueueController extends \Controller {
    public function main($code){
        $filename = dirname(__FILE__).'/queues/'.$code.'.php';
        if(is_file($filename)){
            $crawler = new NICrawler();
            $crawler->setFetchTasks([
                'queue' =>  include_once($filename)
            ]);
            $m1 = memory_get_usage();
            $t1 = time();
            $crawler->run([], 'SimpleHtmlDom');
            // $crawler->run();
            $m2 = memory_get_usage();
            $t2 = time();
    
            echo '用：:'.($t2 - $t1) . '秒';
            echo "\n";
            echo '内存峰值：' . memory_get_peak_usage()/1048576 . 'M';
        }
    }
}