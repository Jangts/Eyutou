<?php
// 核心控制模块公用特性所使用的命名空间，命名空间Tangram\CTRLR的子空间
namespace Tangram\CTRLR\rdb_traits;

/**
 * @trait Tangram\CTRLR\rdb_traits\transaction
 * Transaction Methods For Dealing Relational Data
 * 关系数据处理类的事件处理方法集
**/
trait transaction {
    public function lock(){
        return $this->pdox->lock();
    }

    public function unlock($__key){
        $this->pdox->unlock($__key);
        return $this;
    }

    public function beginAndLock($commitPrevious = true, $rollBackPrevious = true){
        $this->begin($commitPrevious, $rollBackPrevious);
        return $this->pdox->lock();
    }

    public function begin($commitPrevious = true, $rollBackPrevious = true){
        if($this->pdox->locked()){
            return false;
        }
        if($this->pdox->inTransaction()){
            if($commitPrevious){
                $this->pdox->commit();
                return $this->pdox->beginTransaction();
            }
            if($rollBackPrevious){
                $this->pdox->rollBack();
                return $this->pdox->beginTransaction();
            }
            return true;
        }
        return $this->pdox->beginTransaction();
    }

    public function is_intrans(){
        return $this->pdox->inTransaction();
    }

    public function rollBack(){
        if($this->pdox->locked()){
            return false;
        }
        if($this->pdox->inTransaction()){
            return $this->pdox->rollBack();
        }
        return false;
    }

    public function commit(){
        // var_dump($this->pdox->locked());
        //             exit;
        if($this->pdox->locked()){
            return false;
        }
        // var_dump($this->pdox->inTransaction());
        //             exit;
        if($this->pdox->inTransaction()){
            return $this->pdox->commit();
        }
        return false;
    }
}