<?php

/**
 * @class NICrawler_dom_drivers_RegExpDOM
 * @desc RegExp Document Object Mode
 * 
 * @final
 * @author Jangts
 * @version 1.0.0
 */
final class NICrawler_dom_drivers_RegExpDOM implements NICrawler_dom_drivers__abstract {
    protected $content = null;

    protected function buildNode($matches){
        $obj = new stdClass;
        $obj->base = $this;
        $obj->content = $matches[0];
    }

    function getElementById($id){
        return null;
    }

    function getElementsByTagName($tag, $contextnode = null){
        return [];
    }

    function loadHTML($content){
        $this->content = $content;
        return $this;
    }

    public function select($pattern = '', $index = null, $contextnode = null) {
        return [];
    }

    public function getHyperLinks() {
        if(preg_match_all("/<a.+?href=[\'\"]{0,1}([^>\'\"]*).*?>/i", $this->content, $matches)){
            return $matches[1];
        }
        return [];
    }

    public function getNodeText($node){
        return '';
    }

    function __destruct() {
        return true;
    }
}