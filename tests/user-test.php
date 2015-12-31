<?php
require_once('../inc/bootstrap.php');
if (!DEBUG){
  die("DEBUG MODE MUST BE ENABLED. THIS TEST WILL DESTROY DATA!!");
}

$db = new database();
$db->query("TRUNCATE tbl_user; TRUNCATE tbl_session; TRUNCATE tbl_log;");
try {
  $db->execute();
} catch (Exception $e) {
  return array("Database error: ".$e->getMessage(),1);
}

$user = new user();
$json = $user->register('First User','test@test.test','test','test');
//Pass, activate, make admin
var_dump($user);
$user = '';

$user = new user();
$json.= $user->register('Duplicate Email','test@test','test','test');
//Fail
var_dump($user);
$user = '';

$user = new user();
$json.= $user->register('Password Mismatch','bar@foo.bar','test','tset');
//Fail
var_dump($user);
$user = '';


$user = new user();
$json.= $user->register('Second User','foo@bar.foo','test','test');
//Pass
var_dump($user);
$user = '';


$user = new user();
$json.= $user->login('First User','tset');
//Fail
var_dump($user);
$user = '';


$user = new user();
$json.= $user->login('First','tset');
//Fail
var_dump($user);
$user = '';


$user = new user();
$json.= $user->login('Stupid','test');
//Fail
var_dump($user);
$user = '';


$user = new user();
$json.= $user->login('First User','test');
echo "<em>First User Session</em>";
var_dump($_SESSION);
echo "<em>First User Admin Test</em>";
var_dump($user->isAdmin()); //True
//Pass
var_dump($user);
$user = '';


$user = new user();
$json.= $user->logout();
session_start();
//Pass
var_dump($user);
$user = '';


$user = new user();
$json.= $user->login('foo','test');
echo "<em>Login failure session</em>";
var_dump($_SESSION);
echo "<em>Login failure admin check</em>";
var_dump($user->isAdmin()); //False
//Fail
var_dump($user);
$user = '';


$user = new user();
$json.= $user->login('Second User','test');
echo "<em>Second User Session</em>";
var_dump($_SESSION);
echo "<em>Second user admin check</em>";
var_dump($user->isAdmin()); //False
//Soft fail
var_dump($user);

$json.= $user->logout();

$json = str_replace('}{','},{',"[$json]");
if (RETURN_JSON){
  var_dump($json);
} else {
  var_dump(json_decode($json));
}

$db->query("TRUNCATE tbl_user; TRUNCATE tbl_session; TRUNCATE tbl_log;");
try {
  $db->execute();
} catch (Exception $e) {
  return array("Database error: ".$e->getMessage(),1);
}
