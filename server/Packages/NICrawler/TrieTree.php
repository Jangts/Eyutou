<?php
/**
 * @class NICrawler_TrieTree
 * @desc 字典树的简单实现，没有做内存优化
 * @desc A simple implementation of trie without improvements on memory
 * 
 * @author Jangts
 * @version 1.0.0
**/
class NICrawler_TrieTree {
    protected $root = [];

    public function __construct($strings = []) {
        $this->root = array(
            'children' => [],       
            'count'    => 0,
        );
        foreach ($strings as $str) {
            $this->insert($str);
        }
    }

    public function insert($str) {
        try {
            $str        = strval($str);
            $intLen     = strlen($str);
            $curNode = &$this->root;

            for ($i = 0; $i < $intLen; ++$i) {
                if (!isset($curNode['children'][$str[$i]])) {
                    $curNode['children'][$str[$i]] = array(
                        'children' => [],
                        'count'    => 0,
                    );
                }
                $curNode = &$curNode['children'][$str[$i]];
            }

            $curNode['count'] += 1;
            unset($curNode);

        } catch (Exception $e) {
            NICrawler_Log::fatal($e->getMessage());
            return false;
        }

        return true;
    }

    public function delete($str) {
        $curNode = &$this->locateNode($str);
        if (!is_null($curNode) && $curNode['count'] > 0) {
            $curNode['count'] -= 1;
        }
        unset($curNode);
        return true;
    }

    public function has($str) {
        $targetNode = &$this->locateNode($str);
        $bolRes = false;
        if (!is_null($targetNode) && $targetNode['count'] > 0) {
            $bolRes = true;
        }
        unset($targetNode);
        return $bolRes;
    }

    protected function &locateNode($str) {
        $str = strval($str);
        $intLen     = strlen($str);
        $curNode = &$this->root;

        for ($i = 0; $i < $intLen; ++$i) {
            if (!isset($curNode['children'][$str[$i]])) {
                return null;
            }
            $curNode = &$curNode['children'][$str[$i]];
        }

        return $curNode;
    }
};
