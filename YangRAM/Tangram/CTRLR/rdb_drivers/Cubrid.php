<?php
namespace Tangram\CTRLR\rdb_drivers;

use PDO;
use Status;


/**
 * PDO Extended For Cubrid
**/
class Cubrid extends _abstract {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_CUBRID')){
            $dsn = sprintf('cubrid:host=%s;dbname=%s', $options['host'], $options['dbname']);
            if (!empty($options['port'])) {
                $dsn .= ';port=' . $options['port'];
            }
            return $dsn;
        }
        $sp = new Status(1501, '', 'Need SQL Driver PDO_CUBRID');
        $sp->log();
    }

    public function dropIndexConstraint($tablename, $indexname){
        return false;
	}
}
