<?php
namespace Tangram\CACHE\ses_handlers;

/**
 * Database Session
 * 数据库存储SESSION解决方案
**/
final class DbSession implements _interface {
	private static $instance = NULL;

	public static function instance(){
		if(System::$instance===NULL&&DbSession::$instance===NULL){
			DbSession::$instance = new self;
		}
		return DbSession::$instance;
	}

	private
	$coon,
	$table = DB_TMP.'sessions';

	private function __construct() {
		# 自行链接主数据库，以绕开ORM套件的权限控制
	}

	function open($savePath, $sName) {
		$sql = "SELECT data FROM `%s`";
		$sql = sprintf($sql, $this->table);
		$res = $this->coon->query($sql);
		if($res==false){
			$sql = "CREATE TABLE `%s` (";
			$sql .= "id varchar(32) NOT NULL,SK_STAMP int(32) NOT NULL,data longtext NOT NULL";
			$sql .= ");ALTER TABLE `%s` ADD INDEX(`id`)";
			$sql = sprintf($sql, $this->table, $this->table);
			$this->coon->query($sql);
		}
		return true;
	}

	function close() {
		$this->conn = NULL;
		return true;
	}

	function read($id) {
		$sql = "SELECT data FROM `%s` WHERE id='%s'";
		$sql = sprintf($sql, $this->table, $id);
		$res = $this->coon->query($sql);
		$row = $res->fetch();
		return !$row ? null : $row['data'];
	}

	function write($id, $data) {
		$expiry = time();
		$sql = "REPLACE INTO `%s` (id,SK_STAMP,data) VALUES ('%s', '%d', '%s')";
		$sql = sprintf($sql, $this->table, $id, $expiry, $data);
		$this->coon->query($sql);
		return true;
	}

	function destroy($id) {
		$sql = "DELETE FROM `%s` WHERE id='%s'";
		$sql = sprintf($sql, $this->table, $id);
		$this->coon->query($sql);
		return true;
	}

	function gc($maxlifetime) {
		$sql = "DELETE FROM `%s` WHERE SK_STAMP < '%d'";
		$sql = sprintf($sql, $this->table, time()+$maxlifetime);
		$this->coon->query($sql);
		$this->coon->query('OPTIMIZE TABLE '.$this->table);
		return true;
	}
}
