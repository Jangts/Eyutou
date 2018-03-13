<?php
namespace Tangram\CTRLR\rdb_drivers;

use PDO;
use Status;

/**
 * PDO Extended For SQLite2
**/
class SQLite2 extends SQLite {
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
                self::$instances[$id] = $obj->connectAndReturnInstance($dsn, NULL, NULL, $driverOptions);
			}
			return self::$instances[$id];
		}
		return NULL;
	}

	public static function parseDsn(array $options) {
        if(extension_loaded('PDO_SQLITE')){
			if($path = realpath(str_replace('<%D%>', DBF_PATH, $options['file']))){
            	$dsn = 'sqlite2:'.$path;
				if (!empty($options['port'])) {
					$dsn .= ';port=' . $options['port'];
				} elseif (!empty($options['socket'])) {
					$dsn .= ';unix_socket=' . $options['socket'];
				}
				if (!empty($options['charset'])) {
					$dsn .= ';charset=' . $options['charset'];
				}
				return $dsn;
			}
			new Status(1501, '', 'Error File Option ['.$options['file'].'] For SQLite2', true);
        }
		$sp = new Status(1501, '', 'Need SQL Driver PDO_SQLITE');
        $sp->log();
    }
}
