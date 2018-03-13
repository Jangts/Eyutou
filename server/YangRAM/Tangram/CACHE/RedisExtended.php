<?php
// 核心缓存模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\CACHE;

// 引入相关命名空间，以简化书写

/**
 * @class Tangram\CACHE\RedisExtended
 * Redis拓展类
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
 
final class RedisExtended extends \Redis {     
    /**
     * 私有化克隆函数，防止类外克隆对象
     */
    private function __clone(){
        return false;
    }
}
 