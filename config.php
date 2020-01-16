<?php
$absolute_way = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);
$absolute_way = 'D:/openServer/domains/atvdoc/public_html';
$storage_way = 'D:/openServer/domains/atvdoc/storage/';
$folder = str_replace($_SERVER['DOCUMENT_ROOT'],'',stripslashes($absolute_way));

// HTTP
define('HTTP_SERVER', 'http://'.$_SERVER['HTTP_HOST'].$folder.'/');
// HTTPS
define('HTTPS_SERVER', 'https://'.$_SERVER['HTTP_HOST'].$folder.'/');

// DIR
define('DIR_APPLICATION', $absolute_way.'/catalog/');
define('DIR_SYSTEM', $absolute_way.'/system/');
define('DIR_IMAGE', $absolute_way.'/image/');
define('DIR_STORAGE', $storage_way);  //Поменять
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'atvdoc');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');