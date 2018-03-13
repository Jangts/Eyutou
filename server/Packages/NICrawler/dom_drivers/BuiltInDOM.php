<?php

/**
 * @class NICrawler_dom_drivers_BuiltInDOM
 * @desc NICrawler Built-in Document Object Mode
 * 
 * @final
 * @author Jangts
 * @version 1.0.0
 */
final class NICrawler_dom_drivers_BuiltInDOM implements NICrawler_dom_drivers__abstract {
    protected
    $dom = null,
    $domxpath = null;

    function getElementById($id){}

    function getElementsByTagName($tag){}

    function loadHTML($content){
        $this->dom = new DOMDocument();
        if (@$this->dom->loadHTML($content) == FALSE) {
            NICrawler_Log::warning('Failed to call $this->dom->loadHTML');
            $this->dom      = null;
            $this->domxpath = null;
        } else {
            $this->domxpath = new DOMXPath($this->dom);
        }
        return $this;
    }

    public function select($pattern = '', $index = null, $contextnode = null) {
        if($this->domxpath){
            if ($contextnode !== null) {
                $results = $this->domxpath->query($pattern, $contextnode);
            } else {
                $results = $this->domxpath->query($pattern);
            }
            if(is_numeric($index)){
                return [$results->item($i)];
            }
            return $results;
        }
        return [];
    }

    public function getHyperLinks() {
        $links = [];
        $results = $this->select('//a');
        foreach($results as $node){
            if($link = $node->getAttribute('href')){
                $links[] = $link;
            }
        }
        return $links;
    }

    public function getAttribute($node, $attr){
        if($link = $node->getAttribute($attr)){
            return $link;
        }
        return null;
    }

    public function getNodeText($node){
        return $node->textContent;
    }

    function __destruct() {
        if (method_exists($this->dom, 'clear')) {
            $this->dom->clear();
        }
    }
}