<?php
require_once('../inc/bootstrap.php');
if (!DEBUG){
  die("DEBUG MODE MUST BE ENABLED. THIS TEST WILL DESTROY DATA!!");
}
$user = new user();

var_dump($user);

$user = new user('18570146e03');

var_dump($user);
