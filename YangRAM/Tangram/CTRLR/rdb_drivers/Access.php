<?php
namespace Tangram\CTRLR\rdb_drivers;

use PDO;
use Status;



/**
 * PDO Extended For Access
**/
class Access extends _abstract {
    public static function instance(array $options){
		if($dsn = self::parseDsn($options)){
            $id = \hash('md4', $dsn);
    		if(empty(self::$instances[$id])){
                if(isset($options['driverOptions'])){
                    $driverOptions = $options['driverOptions'];
                }else{
                    $driverOptions = [];
                }
    			$obj = new static;
                self::$instances[$id] = $obj->connectAndReturnInstance($dsn, NULL, NULL, $driverOptions, NULL);
    		}
    		return self::$instances[$id];
        }
		return NULL;
	}

	public static function parseDsn(array $options) {
        if(extension_loaded('PDO_ODBC')){
            if($path = realpath(str_replace('<%D%>', DBF_PATH, $options['file']))){
                $dsn = 'odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='.$path;
                if (!empty($options['username'])) {
                    $dsn .= ';UID=' . $options['username'];
                }
                if (!empty($options['password'])) {
                    $dsn .= ';PWD=' . $options['password'];
                }
                return $dsn;
            }
            new Status(1501, '', 'Error File Option ['.$options['file'].'] For Access', true);
        }
        $sp = new Status(1501, '', 'Need SQL Driver PDO_ODBC');
        $sp->log();
    }

    public function showTables(){
        $sql    = "SELECT name FROM MSYSMODELECTS WHERE TYPE=1 AND NAME NOT LIKE 'Msys*'";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function addPrimaryKey($tablename, $field){
        $sql = sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        $sql .= sprintf('ALTER TABLE `%s` ADD PRIMARY KEY (`%s`);', $tablename, $field);
        return $this->exec($sql);
    }

    public function addUnionPrimaryKey($tablename, array $fields){
        $sql = '';
        foreach ($fields as $field) {
            $sql .= sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        }
        $sql .= sprintf('ALTER TABLE `%s` ADD PRIMARY KEY CLUSTERED (`%s`);', $tablename, join('`,`', $fields));
        return $this->exec($sql);
    }

    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $sql = sprintf('ALTER TABLE `%s` DROP PRIMARY KEY;', $tablename);
        return $this->exec($sql);
    }

    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('DROP INDEX %s ON %s;', $indexname, $tablename);
        return $this->exec($sql);
	}
}
