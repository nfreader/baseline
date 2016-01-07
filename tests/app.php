<?php
require_once('../inc/bootstrap.php');
if (!DEBUG){
  die("DEBUG MODE MUST BE ENABLED. THIS TEST WILL DESTROY DATA!!");
}
$app = new app();
var_dump($app->isDatabaseInstalled());
