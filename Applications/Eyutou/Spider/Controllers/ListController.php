<?php
namespace App\Controllers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

use Packages\NICrawler;

class SitesController extends \Controller {
    public function main(){
        $crawler = new NICrawler();

        $arrTasks =  [
            //任务的名字随便起，这里把名字叫qqnews
            //the key is the name of a job, here names it qqnews
            'qqnews' => [
                'start_page' =>  'http://www.hsztbzx.com/Content/jsgc',
                // 'start_page' =>  'http://www.demo.yangram.ni/Users/Public/Packages/nicrawler/test.html', //起始网页
                'link_rules' => [
                    /**
                     * 所有在这里列出的正则规则，只要能匹配到超链接，那么那条爬虫就会爬到那条超链接
                     * Regex rules are listed here, the crawler will follow any hyperlinks once the regex matches
                     */
                    'www.hsztbzx.com/DetailPro/<0>',
                    'www.hsztbzx.com/Content/jsgc<*>'
                    // '/news\.qq\.com\/omn\/\w+$/',
                    // '/news\.qq\.com\/cmsn\/\d+\/\d+\.htm$/'
                    // '<w>.baidu.com<*>',
                    // 'www.demo.yangram.ni/<*>'
                ],
                //爬虫从开始页面算起，最多爬取的深度，设置为1表示只爬取起始页面
                //Crawler's max following depth, 1 stands for only crawl the start page
                'max_depth' => 3
            ]   
        ];
        
        //$crawler->setFetchTasks($arrTasks)->run(); 这一行的效果和下面两行的效果一样
        $crawler->setFetchTasks($arrTasks);
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