<?php if (!$user->isAdmin()){
  die("You must be an administrator to view this page");
} ?>

<div class="page-header">
  <h1>Application Logs</h1>
</div>

<?php

$limit = PER_PAGE;

$app = new app();

if(isset($_GET['page'])){
  $page = $_GET['page'];
  $offset = $page * $limit;
  $logs = $app->getLogs($offset);
} else {
  $page = 0;
  $logs = $app->getLogs();
}

$pages = ceil($logs->total/$limit);

$i = 0;

$nextpage = $page + 1;
$prevpage = $page - 1;

?>

<ul class='pagination'>
  <?php if ($prevpage >= 0): ?>
  <li><a href="?action=viewLogs&page=<?php echo $prevpage;?>">&laquo;</a></li>
  <?php endif; ?>

  <?php while ($i<=$pages-1) :?>
    <?php if ($i == $page) :?>
      <li class='active'>
        <a href="?action=viewLogs&page=<?php echo $i;?>">
          <?php echo $i+1;?>
        </a>
      </li>
    <?php else :?>
      <li>
        <a href="?action=viewLogs&page=<?php echo $i;?>">
          <?php echo $i+1;?>
        </a>
      </li>
    <?php endif;?>
  <?php $i++; endwhile;?>
  <?php if ($nextpage < $pages) : ?>
  <li><a href="?action=viewLogs&page=<?php echo $nextpage;?>">&raquo;</a></li>
  <?php endif;?>
</ul>

<table class="table table-bordered table-condensed">
  <thead>
    <tr>
      <th>Number</th>
      <th>UID</th>
      <th>Name</th>
      <th>What</th>
      <th>Data</th>
      <th>From</th>
      <th>When</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($logs->logs as $log) :
    $log = $user->formatUser($log);?>
    <tr>
      <td><?php echo $log->id;?></td>
      <td><?php echo $log->who;?></td>
      <td><?php echo $log->userlabel;?></td>
      <td><?php echo $log->what;?></td>
      <td><?php echo $log->data;?></td>
      <td><?php echo $log->from;?></td>
      <td><?php echo timestamp($log->timestamp);?></td>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>
