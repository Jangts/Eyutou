<?php
/** Pointing to the startup file "main.php",
 * only if the default document is not "main.php".
 */
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/server/main.php';
include $_SERVER['SCRIPT_FILENAME'];