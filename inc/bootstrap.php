<?php
//Header settings to allow UTF-8mb strings (emoji!)
header('Content-type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');

//Definitions
define('DEBUG',TRUE);

//Load classes
function __autoload($class_name) {
  include 'classes/'. $class_name . '.php';
}

require_once('constants.php');
require_once('app-constants.php');

$app = new app();
if(!$app->isInstalled()){
  die("FATAL ERROR: Application does not appear to be installed.");
}

//Load database settings
require_once('config.php');

require_once('functions.php');
require_once('app-functions.php');

session_start();
