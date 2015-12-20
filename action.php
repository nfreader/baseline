<?php
$msg = '';
//Check for config.php
if (!defined(APP_NAME)){
  require_once('inc/bootstrap.php');
}

//A $user class is available to us via index.php
//But just in case it isn't...
if (!$user) {
  $user = new user();
}

if(isset($_GET['action'])){
  $action = $_GET['action'];
} else {
  $msg = returnError("No action specified.");
}
if (!$user->isLoggedIn()){
  switch($action) {
    default:
      $msg = returnError("Action not found: $action");
    break;

    case 'register':
      $msg = $user->register($_POST['username'],
      $_POST['email'],
      $_POST['password'],
      $_POST['password2']);
    break;

    case 'login':
      $msg = $user->login($_POST['username'],$_POST['password']);
    break;
  }
} else {
  if ('register' == $action){
    die("You are already registered");
  }
  switch ($action) {
    default:
      $msg = returnError("Action not found: $action");
    break;

    case 'logout':
      $msg = $user->logout();
    break;

    case 'viewUserList':
    if($user->isAdmin()){
      $include = 'admin/userList';
    } else {
      $msg = returnError("Access denied");
    }
    break;

    case 'activateUser':
    if($user->isAdmin()){
      $msg = $user->activateUser($_GET['user']);
      $include = 'admin/userList';
    } else {
      $msg = returnError("Access denied");
    }
    break;

    case 'deactivateUser':
    if($user->isAdmin()){
      $msg = $user->deactivateUser($_GET['user']);
      $include = 'admin/userList';
    } else {
      $msg = returnError("Access denied");
    }
    break;
  }
}
$msg = str_replace('}{','},{',"[$msg]");
if (RETURN_JSON) {
  echo $msg;
} else {
  $alert = '';
  foreach (json_decode($msg, TRUE) as $message){
    $alert.= alert($message);
  }
}