<?php
  require_once('inc/bootstrap.php');
  $user = new user();
  if(isset($_GET['action'])) {
    require_once('action.php');
  }
  if (DEBUG){
    $time = explode(' ', microtime());
    $start = $time[1] + $time[0];
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo APP_NAME; ?></title>

    <!-- Bootstrap -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/datetimepicker.css" rel="stylesheet">

     <style>
      body {
        padding-top: 60px;
      }
      form.register .checkbox {
        display: none;
      }
      .label {
        font-size: inherit;
      }
    </style>
  </head>
  <body>
  <?php require_once('view/nav.php'); ?>
    <div class="container">

    <?php
    if (!empty($alert)) {
      echo $alert;
    }
    ?>
