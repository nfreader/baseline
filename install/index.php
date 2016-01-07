<?php
require_once('../inc/bootstrap.php');

require_once('sql.php');

if(isset($_GET['install'])):
  $db = new database();
  $db->query($sql);
  try {
    $db->execute();
  } catch (Exception $e) {
    return returnError("Database error: ".$e->getMessage());
  }
  echo "The database tables were successfully created. <a href='".APP_URL."'>Home</a>.";
else:
?>

<h1>Copy this into your MySQL database. <a href="?install">Or let me try for you</a></h1>
<pre><?php echo $sql; ?></pre>

<?php endif;?>
