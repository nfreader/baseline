<?php

if (empty($_GET['user'])) {
  die("No user specified");
}

$viewuser = new user($_GET['user'],true);

?>

<div class="page-header">
  <h1>
    <small>This is</small>
    <?php echo $viewuser->userlabel;?>
    <small> Joined <?php echo $viewuser->createdlabel; ?></small>
  </h1>
</div>

<?php if ($user->isAdmin()) :?>
  <div class="panel panel-danger">
    <div class="panel-heading">
      <strong>Admin only</strong>
    </div>
    <div class="panel-body">
      <p><?php echo $viewuser->statuslink; ?></p>

      <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th>Number</th>
            <th>What</th>
            <th>Data</th>
            <th>From</th>
            <th>When</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($viewuser->logs as $log) : ?>
            <tr>
              <td><?php echo $log->id;?></td>
              <td><?php echo $log->what;?></td>
              <td><?php echo $log->data;?></td>
              <td><?php echo $log->from;?></td>
              <td><?php echo timestamp($log->timestamp);?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
