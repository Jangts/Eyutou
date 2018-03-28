<?php
/**
 * 将该文件保存在web根目录下，命名为attachment.php
 *                                 ~~~~~~~~~~~~~~~~~
 * 1、apache的httpd.conf里面保证有这两句：
 * options Indexes followsymlinks 
 * allowoverride all
 * 
 * 2、在服务器根目录下建一个“.htaccess”，内容为：
 * RewriteEngine on
 * RewriteRule ^upload/(.+)$ attachment.php?show=$1 [L]
 *                             ~~~~~~~~~~~~~~~~  
 * 你可以尝试将attachment.php命名为其他名称，.htaccess里面也要做相应更改
 * 但是我这样做的时候出现莫名其妙的问题，未解决
 */
// 定义文件类型
$mimetypes = [];

function showfile($filename, $type, $data) {
    $size = filesize($data);
    header('Content-type: ' . $type);
    header('Content-Length: ' . $size);
    header('Content-Disposition: attachment; filename=' . $filename);
    readfile($data);
} 
// ///////////////////////////////////////////////////\
// 文件存放目录,建议选择使用非Web 目录(使用绝对物理路径即可),请修改双引号中的内容
$storePath = 'upload/';
// 出错信息存储路径，请自行制作各种类型的错误提示文件： noexists.txt|doc|wmv|mp3... needlogin.. thief..
$errorPath = 'error/';
$filename = trim($_GET['show']);
$filename = str_replace("../", "", $filename); //目录安全设置
// 获取文件类型
preg_match('/\\.([a-z0-9]+)$/i', $filename, $match);
$filetype = $match[1];
$type = $mimetypes[$filetype];
if ($type == '')
    $type = 'application/unknown';
$data = $storePath . $filename;
// 文件不存在
if (!file_exists($data)) {
    $filename = 'noexists.' . $filetype;
    $data = $errorPath . $filename;
    showfile($filename, $type, $data);
    exit;
} 
// 防盗，根据需要，自行修改
// 需要登录的
/**
* if (...)    //未登录；
* {
* $filename = 'needlogin.' . $filetype;
* $data = $errorPath . $filename;
* showfile($filename, $type, $data);
* exit;
* }
*/
// referer防盗，不建议使用。不是所有的用户代理（浏览器）都会设置这个变量，而且有的还可以手工修改 HTTP_REFERER。因此，这个变量不总是真实正确的。
// $referer = $_SERVER['HTTP_REFERER'];
// $server = $_SERVER['HTTP_HOST'];
// preg_match('/https?:\/\/([^\/]+).*/i', $referer, $match);
/**
* if ($match[1] != $server)
* {
* $filename = 'thief.' . $filetype;
* $data = $errorPath . $filename;
* showfile($filename, $type, $data);
* exit;
* }
* 
* //session 防盗，缺点是要在其他页面注册变量sess_referer。
* session_start();
* if (!session_is_registered(sess_referer))
* {
* $filename = 'thief.' . $filetype;
* $data = $errorPath . $filename;
* showfile($filename, $type, $data);
* exit;
* }
*/
showfile($filename, $type, $data);
?>