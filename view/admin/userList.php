<?php if (!$user->isAdmin()){
  die("You must be an administrator to view this page");
} ?>

<div class="page-header">
  <h1>User list</h1>
</div>

<?php 
$users = $user->getUserList();
?>

<table class="table table-bordered table-condensed">
  <thead>
    <tr>
      <th>UID</th>
      <th>Name</th>
      <th>Status</th>
      <th>Rank</th>
      <th>Email</th>
      <th>Registered</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $userRow) : 
  $userRow = $user->formatUser($userRow); ?>
    <tr>
      <td><?php echo $userRow->uid;?></td>
      <td><?php echo $userRow->userlabel;?></td>
      <td><?php echo $userRow->statuslink;?></td>
      <td><?php echo $userRow->rankname;?></td>
      <td><?php echo $userRow->email;?></td>
      <td><?php echo $userRow->createdlabel;?></td>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>
