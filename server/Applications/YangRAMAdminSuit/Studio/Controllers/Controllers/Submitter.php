<?php
namespace Admin\Pub\Controllers;

use CM\GEC;
use CM\SPC;
use CM\TableRowMetaModel;

class Submitter extends \Controller {
	public function sav(){
		$post = $this->request->INPUTS;
		$this->saveContent(false, $post);
	}
	public function pub(){
		$post = $this->request->INPUTS;
		$this->saveContent(true, $post);
	}
	
	public function rmv(){
		$post = $this->request->INPUTS;
		$this->remvContent($post);
	}
	
	private function saveContent($publishing, $post){
		$post = $post->stopAttack()->getArrayCopy();
		if(isset($post['TABLENAME'])&&isset($post['ID'])){
			if($post['TABLENAME']=='general'){
				if($post['ID']=='new'){
					$obj = GEC::create($post);
					if($obj&&$id = $obj->ID){
						echo $id;
					}else{
						echo '<ERROR>';
					}
				}else{
					$obj = GEC::byGUID($post['ID']);
					if($obj->put($post)->save()){
						echo $post['ID'];
					}else{
						echo '<ERROR>';
					}
				}
			}else{
				if($publishing){
					$post["SK_STATE"] = '1';
				}else{
					$post["SK_STATE"] = '0';
				}
				if($post['ID']=='new'){
					$obj = SPC::create($post);
					if($obj&&$id = $obj->ID){
						echo $id;
					}else{
						echo '<ERROR>';
					}
				}else{
					$obj = SPC::byGUID($post['ID']);
					if($obj->put($post)->save()){
						echo $post['ID'];
					}else{
						echo '<ERROR>';
					}
				}
			}
		}
	}
	
	private function remvContent($post){
		$post = $post->getArrayCopy();
		if(isset($post['TABLENAME'])&&isset($post['ID'])){
			if($post['TABLENAME']=='general'){
				if(GEC::remove("ID = '".$post['ID']."'", 1)){
					echo $post['ID'];
				}else{
					echo '<ERROR>';
				}
			}else{
				if(TableRowMetaModel::remove("ID = '".$post['ID']."'", 1)){
					echo $post['ID'];
				}else{
					echo '<ERROR>';
				}
			}
		}
	}
}