<?php

require_once("database.php");

class cStatusLog extends cDbTable {
  public function Write($idTableName, $idStatus) {
	$this->insert(array(
	  "StatusLog_idTableName"=>$idTableName, 
	  "StatusLog_idStatus"=>$idStatus
	));
  }
}

$statusLog = new cStatusLog("StatusLog");

?>