<?php
/*
 * @author xuruiqi
 * @date   2014.09.21
 * @copyright reetsee.com
 * @desc   Dom's abstract class
 */
interface NICrawler_dom_drivers__abstract {
    function getElementById($id);

    function getElementsByTagName($tag);

    function loadHTML($content);

    function select($pattern = '', $index = null, $contextnode = null);

    function getHyperLinks();
}
