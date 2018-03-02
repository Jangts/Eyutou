<?php
require_once(dirname(__FILE__) . '/simple_html_dom.php');

/**
 * @class NICrawler_dom_drivers_SimpleHtmlDom
 * @desc NICrawler Simple Html Document Object Mode
 * 
 * @final
 * @author Jangts
 * @version 1.0.0
 */
final class NICrawler_dom_drivers_SimpleHtmlDOM implements NICrawler_dom_drivers__abstract {
    protected $dom = null;

    public function getElementById($id) {
        $methodName = 'getElementById';
        if (method_exists($this->dom, $methodName)) {
            return $this->dom->getElementById($id);
        } else {
            NICrawler_Log::warning("method $methodName not exists");
            return FALSE;
        }
    }

    public function getElementsByTagName($tag) {
        $methodName = 'getElementsByTagName';
        if (method_exists($this->dom, $methodName)) {
            return $this->dom->getElementsByTagName($tag);
        } else {
            NICrawler_Log::warning("method $methodName not exists");
            return FALSE;
        }
    }

    public function loadHTML($content) {
        if (NULL === $this->dom) {
            if (function_exists('str_get_html')) {
                $this->dom = str_get_html($content);
            }
        } else {
            if (method_exists($this->dom, 'load')) {
                $this->dom->load($content);
            }
        }
        return $this;
    }

    /**
     * @deprecated
     */
    public function select($pattern = '', $index = null, $contextnode = null) {
        if($contextnode){
            return [];
        }
        return $this->dom->find($pattern, $index);
    }

    public function getHyperLinks() {
        $links = [];
        $results = $this->dom->find('a', null);
        foreach($results as $node){
            $links[] = $node->href;
        }
        return $links;
    }

    public function getAttribute($node, $attr){
        if($link = @$node->$attr){
            return $link;
        }
        return null;
    }

    public function getNodeText($node){
        return $node->plaintext;
    }

    function __destruct() {
        if (method_exists($this->dom, 'clear')) {
            $this->dom->clear();
        }
    }
}
