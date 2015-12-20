<?php
class session implements \SessionHandlerInterface {

    public function __construct() {
      session_start();
    }

    public function open($savePath, $session_name) {
      $db = new database();
      $db->query("INSERT INTO tbl_session
                  SET session_id = :sessionName,
                  session_data = ''
                  ON DUPLICATE KEY
                  UPDATE session_lastaccesstime = NOW()");
      $db->bind(':sessionName',$session_name);
      $db->execute();
      return true;
    }

    public function close() {return false;}

    public function read($id)
    {
      $db = new database();
      $db->query("SELECT * FROM tbl_session WHERE session_id = :id");
      $db->bind(':id',$id);
      if ($db->execute()) {
        $result = $db->single(\PDO::FETCH_ASSOC);
        return $result["session_data"];
      }
      return '';
    }

    public function write($id, $data) {
      if ($data == null) {
        return true;
      }
      $db = new database();
      $db->query("INSERT INTO tbl_session
                  SET session_id = :id,
                  session_data = :data
                  ON DUPLICATE KEY UPDATE session_data = :data");
      $db->bind(':id',$id);
      $db->bind(':data',$data);
      try {
        $db->execute();
      } catch (Exception $e) {
        return returnError("Database error: ".$e->getMessage());
      }
      
    }

    public function destroy($id) {
      $db = new database();
      $db->query("DELETE FROM tbl_session WHERE session_id = :id");
      $db->bind(':id',$id);
      $db->execute();
      return true;
    }

    public function gc($maxlifetime) {
      $db = new database();
      $db->query("DELETE FROM tbl_session
                  WHERE session_lastaccesstime < DATE_SUB(NOW(),
                  INTERVAL " . $maxlifetime . " SECOND)");
      $db->execute();
      return true;
    }
}
