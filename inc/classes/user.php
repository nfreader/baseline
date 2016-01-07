<?php

class user {

  public $username;
  public $uid;
  public $status;
  public $rank;
  public $userlabel;
  public $statuslink;
  public $createdlabel;
  public $logs;

  public function __construct($uid=null,$extended=false) {

    if (isset($_SESSION['uid'])||$uid) {
      if ($uid){
        $user = $this->getUser($uid);
      } else {
        $user = $this->getUser($_SESSION['uid']);
        if (!$user) {
          $_SESSION = null;
          session_destroy();
        }
      }

      $user = $this->formatUser($user);
      $this->username = $user->username;
      $this->uid = $user->uid;
      $this->status = $user->status;
      $this->rank = $user->rank;
      $this->userlabel = $user->userlabel;
      $this->statuslink = $user->statuslink;
      $this->createdlabel = $user->createdlabel;

      if ($extended) {
        $app = new app;
        $this->logs = $app->getUserLogSample($this->uid);
      }
    }
  }

  public function formatUser(&$user){
    switch($user->rank){
      case 'U':
      default:
        $class = 'info';
        $rank = 'User';
      break;
      case 'M':
        $class = 'success';
        $rank = 'Moderator';
      break;
      case 'A':
        $class = 'danger';
        $rank = 'Admin';
      break;
    }
    $user->userlabel = "<a class='label label-$class' href='?action=";
    $user->userlabel.= "viewUser&user=$user->uid'>$user->username</a>";
    $user->rankname = $rank;
    $user->createdlabel = timestamp($user->created);
    if ($user->status) {
      $user->statuslink = "<span class='label label-success'>Active</span> <a class='btn btn-xs btn-danger' href='?action=deactivateUser&user=$user->uid'><i class='fa fa-times'></i></a>";
    } else {
      $user->statuslink = "<span class='label label-danger'>Inactive</span> <a class='btn btn-xs btn-success' href='?action=activateUser&user=$user->uid'><i class='fa fa-check'></i></a>";
    }
    return $user;
  }

  public function register($username, $email, $password, $password2) {
    $username = filter_var($username,FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_LOW);
    $email = filter_var($email,FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_LOW);
    if ('' === empty($username)) {
      return returnError("Username is invalid");
    }

    if (trim($password) == '') {
      return returnError("Password cannot be empty");
    }

    if ($password != $password2) {
      return returnError("Your password doesn't match");
    }

    if (trim($email) == '') {
      return returnError("Email address cannot be empty");
    }

    if (!$this->isUnique($username,$email)) {
      return returnError("This email address or username is already in use");
    }

    $db = new database();
    $db->query("INSERT INTO
      tbl_user (uid, username, password, email, created)
      VALUES (substr(sha1(uuid()),4,12), ?, ?, ?, NOW())");
    $db->bind(1,$username);
    $db->bind(2,password_hash($password,PASSWORD_DEFAULT));
    $db->bind(3,$email);
    try {
      $db->execute();
    } catch (Exception $e) {
      return array("Database error: ".$e->getMessage(),1);
    }
    $app = new app();
    $app->logEvent("UR","Registered an account: $username from $email");

    if (DEBUG) {
      $this->activateUser($this->getUIDByUsername($username));
      $return = returnSuccess("Your account has been created and activated. Please login now.");
    } else {
      $return = returnSuccess("Your account has been created but is awaiting activation.");
    }

    if(1 == $db->countRows('tbl_user')) {
      $db->query("SELECT uid FROM tbl_user WHERE username = ?");
      $db->bind(1,$username);
      try {
        $db->execute();
      } catch (Exception $e) {
        return returnError("Database error: ".$e->getMessage());
      }
      $uid = $db->single()->uid;
      $db->query("UPDATE tbl_user SET status = 1, rank = 'A'
        WHERE uid = ?");
      $db->bind(1,$uid);
      try {
        $db->execute();
      } catch (Exception $e) {
        return returnError("Database error: ".$e->getMessage());
      }
      $return.= returnSuccess("Initial user detected. You have been promoted to administrator and activated. Please log in now.");
    }
    return $return;
  }

  public function login($username, $password) {
    $db = new database();
    $db->query("SELECT password FROM tbl_user
      WHERE username = ?");
    $db->bind(1,$username);
    $db->execute();
    $user = $db->single();
    if (!$user) {
      return returnError("Password incorrect");
    }
    if(!password_verify($password, $user->password)) {
      return returnError("Password incorrect");
    } else {
      $db->query("SELECT uid, username, email, rank, status
      FROM tbl_user
      WHERE username = :username");
      $db->bind(':username', $username);
      $db->execute();
      $login = $db->single();
      if ($login->status == 0) {
        return returnMessage("Your account is awaiting activation. Please try again at a later time.");
      } else {
        $_SESSION['uid'] = $login->uid;
        $this->username = $login->username;
        $this->status = $login->status;
        $this->uid = $login->uid;
        $this->rank = $login->rank;
        $app = new app();
        $app->logEvent("LI","$this->username logged in");
        return returnSuccess("You are now logged in as $this->username");

      }
    }
  }

  public function logout() {
    $app = new app();
    $app->logEvent("LO","$this->username logged out");
    $_SESSION = null;
    session_destroy();
    return returnSuccess("You have logged out");
  }

  public function isUnique($username, $email) {
    $db = new database();
    $db->query("SELECT COUNT(*) AS count
      FROM tbl_user WHERE username = :username OR email = :email");
    $db->bind(':username', $username);
    $db->bind(':email', $email);
    $db->execute();
    if (0 == $db->single()->count) {
      return true;
    }
    return false;
  }

  public function isAdmin() {
    if ("A" == $this->rank) {
      $db = new database();
      $db->query("SELECT rank FROM tbl_user WHERE tbl_user.uid = ?");
      $db->bind(1,$this->uid);
      if ($db->single()->rank === 'A') {
        return true;
      }
      return false;
    }
    return false;
  }

  public function isLoggedIn(){
    if ($this->status && NULL != $this->uid){
      return true;
    }
    return false;
  }

  public function activateUser($uid) {
    $user = $this->getUser($uid);
    if ($user->uid == $this->uid && !DEBUG){
      return returnError("You can't activate yourself.");
    }
    $db = new database();
    $db->query("UPDATE tbl_user SET status = 1 WHERE uid = ?");
    $db->bind(1,$user->uid);
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
    $app = new app();
    $app->logEvent("AU","Activated $user->username ($user->uid)");
    return returnSuccess("$user->username's acount has been activated");
  }

  public function deactivateUser($uid) {
    $user = $this->getUser($uid);
    if ($user->uid == $this->uid){
      return returnError("You can't deactivate yourself.");
    }
    $db = new database();
    $db->query("UPDATE tbl_user SET status = 0 WHERE uid = ?");
    $db->bind(1,$user->uid);
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
    $app = new app();
    $app->logEvent("DU","Dectivated $user->username ($user->uid)");
    return returnSuccess("$user->username's acount has been deactivated");
  }

  public function getUserByUID($uid) {}

  public function getUIDByUsername($username) {}

  public function getUserByEmail($email) {}

  public function getUserList() {
    $db = new database($offset=0, $count=30);
    $db->query("SELECT username, email, uid, status, rank, created
     FROM tbl_user
     ORDER BY created ASC
     LIMIT $offset, $count");
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
    return $db->resultSet();
  }

  public function getUser($uid){
    $db = new database();
    $db->query("SELECT username, email, uid, status, rank, created
      FROM tbl_user
      WHERE uid = ?");
    $db->bind(1,$uid);
    try {
      $db->execute();
    } catch (Exception $e) {
      return returnError("Database error: ".$e->getMessage());
    }
    return $db->single();
  }

}
