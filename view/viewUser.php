<?php 

if (empty($_GET['user'])) {
  die("No user specified");
}

$viewuser = new user($_GET['user']);

?>

<div class="page-header">
<h1><small>This is</small> <?php echo $viewuser->userlabel;?></h1>
</div>

<?php if ($user->isAdmin()) :?>
  <div class="alert alert-info">
  <strong>Admin only</strong> | <?php echo $viewuser->statuslink; ?>
  </div>
  <?php endif; ?>