<?php
namespace Tangram\CTRLR\rdb_drivers;

use PDO;
use Status;


/**
 * PDO Extended For SQLite
**/
class SQLite extends _abstract {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_SQLITE')){
            if($path = realpath(str_replace('<%D%>', DBF_PATH, $options['file']))){
                return 'sqlite:'.$path;
            }
            new Status(1501, '', 'Error File Option ['.$options['file'].'] For SQLite', true);
        }
        $sp = new Status(1501, '', 'Need SQL Driver PDO_SQLITE');
        $sp->log();
    }

    public function showTables(){
        $sql = "SELECT name FROM sqlite_master WHERE type='table' "
                . "UNION ALL SELECT name FROM sqlite_temp_master "
                . "WHERE type='table' ORDER BY name;";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function getTableFields($tableName){
        list($tableName) = explode(' ', $tableName);
        $sql             = 'PRAGMA table_info( ' . $tableName . ' )';
        $pdos            = $this->query($sql);
        $result          = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info            = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                = array_change_key_case($val);
                $info[$val['name']] = [
                    'name'    => $val['name'],
                    'type'    => $val['type'],
                    'notnull' => 1 === $val['notnull'],
                    'default' => $val['dflt_value'],
                    'primary' => '1' == $val['pk'],
                    'autoinc' => '1' == $val['pk'],
                ];
            }
        }
        return $info;
    }

    public function addPrimaryKey($tablename, $field){
        $primaryKeyName = 'PK_'.$tablename;
        $sql = sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        $sql .= sprintf('CREATE UNIQUE INDEX %s ON `%s` (`%s`);', $primaryKeyName, $tablename, $field);
        return $this->exec($sql);
    }

    public function addUnionPrimaryKey($tablename, array $fields){
        $primaryKeyName = 'PK_'.$tablename;
        $sql = '';
        foreach ($fields as $field) {
            $sql .= sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        }
        $sql .= sprintf('CREATE UNIQUE INDEX %s ON `%s` (`%s`);', $primaryKeyName, $tablename, join('`,`', $fields));
        return $this->exec($sql);
    }

    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $primaryKeyName = ($primaryKeyName ? $primaryKeyName : 'PK_'.$tablename);
        $sql = sprintf('ALTER TABLE `%s` DROP INDEX %s;', $tablename, $primaryKeyName);
        return $this->exec($sql);
    }

    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('ALTER TABLE `%s` DROP INDEX %s;', $tablename, $indexname);
        return $this->exec($sql);
	}

    public function addCheckConstraint($tablename, $condition, $checkName = NULL){
        return false;
    }

    public function dropCheckConstraint($tablename, $checkName){
        return false;
    }
}
