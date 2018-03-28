<?php
/** Pointing to the startup file "boot.php",
 * only if the default document is not "boot.php".
 */
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/server/boot.php';
include $_SERVER['SCRIPT_FILENAME'];