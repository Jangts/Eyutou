<?php
session_start();
require_once "Config.php";

$logIn = new logInByRR($app_key, $app_secret);
$logUrl = $logIn->logUrl($callback_url, $scope);
echo '<script>window.location.href="'.$logUrl.'";</script>';
?>
