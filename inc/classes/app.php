<?php

class app {

  public $app_name;

  public function __construct() {
    if (DEBUG){
      $this->app_name = APP_NAME." (DEBUG MODE)";
    } else {
      $this->app_name = APP_NAME;
    }
  }

  public function isInstalled(){
    clearstatcache();
    if (file_exists(ABSPATH.'inc/config.php')) {
      return TRUE;
    }
    return FALSE;
  }

  public function logEvent($what, $data) {
    $db = new database();
    $db->query("INSERT INTO tbl_log (who, what, timestamp, data, `from`)
      VALUES (?, ?, NOW(), ?, ?)");
    if (LOG_EVENT_IPS){
      $IP = sha1($_SERVER['REMOTE_ADDR']);
    } else {
      $IP = '';
    }
    $user = new user();
    $db->bind(1,$user->uid);
    $db->bind(2,$what);
    $db->bind(3,$data);
    $db->bind(4,$IP);
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
  }

  public function systemMail($to, $subject, $message){
    if (defined(EMAIL_FROM)){
      $from = EMAIL_FROM;
    } else {
      $from = str_replace(' ', '', strtolower(APP_NAME."@".$_SERVER['SERVER_NAME']));
    }
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $msgheader = "<html><head></head><body style='font-family: sans-serif;'>";
    $msgfooter = "</body></html>";
    mail($to, $subject, $msgheader.$message.$msgfooter, $headers, $from);
  }

  public function getLogs($offset=0, $count=PER_PAGE) {
    $db = new database();
    $db->query("SELECT tbl_log.*, tbl_user.*
      FROM tbl_log
      LEFT JOIN tbl_user ON tbl_log.who = tbl_user.uid
      LIMIT $offset, $count");
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
    $logs = new stdclass();
    $logs->logs = $db->resultset();
    $count = $db->countRows('tbl_log');
    $logs->total = $count;
    return $logs;
  }

  public function getUserLogSample($uid) {
    $db = new database();
    $db->query("SELECT tbl_log.*
      FROM tbl_log
      WHERE who = ?
      LIMIT 0, 5");
    $db->bind(1,$uid);
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
    return $db->resultset();
  }

}
