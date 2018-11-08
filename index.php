<?php

define('ENVIRONMENT', isset($_SERVER['HTTP_ENV']) ? $_SERVER['HTTP_ENV'] : 'product');
isset($_SERVER['HTTP_FETCH_DUMMY']) && define('FETCH_DUMMY', $_SERVER['HTTP_FETCH_DUMMY']);
define('APP_PATH', dirname(__FILE__));
define('CONFIGPATH', APP_PATH . '/app/configs/config.ini');
require_once APP_PATH . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
if (!defined('CONFIGPATH')) echo 'No configpath defined, please define configpath and try again.';

switch (ENVIRONMENT) {
  case 'develop':
    error_reporting(-1);
    ini_set('display_errors', 1);
    ini_set('yaf.environ', 'develop');
    break;
  case 'testing':

  case 'product':
    ini_set('display_errors', 0);

    break;
  default:
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'The application environment is not set correctly.';
    exit(1); // EXIT_ERROR
}


//http://php.net/manual/zh/yaf-application.getconfig.php
$application = new Yaf_Application(CONFIGPATH);

$application->bootstrap()->run();
?>
