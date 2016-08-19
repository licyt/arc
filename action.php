<?php

// load list of fields in htmlSelect
function fieldsForAction($table /* cDbTable */, $value="") {
  $fields = new cHtmlSelect;
  $fields->setAttribute("ID", "ActionField");
  $fields->setAttribute("NAME", "ActionField");
  if ($table->getName()=="Job") {
    $query = "SELECT idTask, TaskName FROM Task ORDER BY TaskName";
    if ($dbRes=myQuery($query)) {
      while ($dbRow=mysql_fetch_assoc($dbRes)) {
        $fields->addOption($dbRow[idTask], $dbRow[TaskName]);
      }
    }
  } else {
    foreach ($table->fields as $field) {
      $fields->addOption($field->getName(), $field->getName());
    }
  }
  $fields->setSelected($value);
  return $fields;
}

// load list of available commands
function commandsForAction($table /* cDbTable */, $value="") {
  $commands = new cHtmlSelect;
  $commands->setAttribute("ID", "ActionCommand");
  $commands->setAttribute("NAME", "ActionCommand");
  $commands->setAttribute("onChange", "loadParameters();");
  $commands->addOption("CREATE", "CREATE");
  $commands->addOption("UPDATE", "UPDATE");
  $commands->addOption("DELETE", "DELETE");
  if ($table->hasChild()) {
	  $commands->addOption("CREATE CHILD", "CREATE CHILD");
  }
  if ($table->hasStatus()) {
	  $commands->addOption("SET STATUS", "SET STATUS");
  }
  //$commands->addOption("IS SET", "IS SET");
  $commands->setSelected($value);
  return $commands;
}

function loadParameters($table /* cDbTable */, $command /* string */, $param1="" /*, $param2=""*/) {
  $params = array();
  switch ($command) {
  	case "CREATE": // ------------------------------------------------------------------ CREATE
	  if ($table->getName()=="Job") {
  		$params[1] = new cHtmlSelect;
	    $params[1]->setAttribute("ID", "ActionParam1");
	    $params[1]->setAttribute("NAME", "ActionParam1");
	    $query = "SELECT idTask, TaskName FROM Task ORDER BY TaskName";
	    if ($dbRes=myQuery($query)) {
	      while ($dbRow=mysql_fetch_assoc($dbRes)) {
	      	$params[1]->addOption($dbRow[idTask], $dbRow[TaskName]);
	      }
	    }
	    $params[1]->setSelected($param1);
	    /*$params[2] = new cHtmlInput("ActionParam2", "HIDDEN");*/
	  }
  	  break;
	case "CREATE CHILD": // ----------------------------------------------------------- CREATE CHILD
	  $params[1] = new cHtmlSelect;
	  $params[1]->setAttribute("ID", "ActionParam1");
	  $params[1]->setAttribute("NAME", "ActionParam1");
	  if ($table->children) {
		foreach ($table->children as $child) {
	      $params[1]->addOption($child->getName(), $child->getName());
		}
	  }
	  /*$params[2] = new cHtmlInput("ActionParam2", "HIDDEN");*/
	  break;
	case "SET STATUS": // --------------------------------------------------------------- SET STATUS
	  $params[1] = new cHtmlSelect;
	  $params[1]->setAttribute("ID", "ActionParam1");
	  $params[1]->setAttribute("NAME", "ActionParam1");
	  $query =
		"SELECT idStatus, StatusName, StatusColor".
		" FROM Status ".
		" WHERE StatusType=\"".$table->getName()."\"";
	  if ($dbRes=myQuery($query)) {
	    while ($dbRow=mysql_fetch_assoc($dbRes)) {
		  $params[1]->addOption($dbRow[idStatus], $dbRow[StatusName], $dbRow[StatusColor]);
	    }
	  }
	  $params[1]->setSelected($param1);
	  /*$params[2] = new cHtmlInput("ActionParam2", "HIDDEN");*/
	  break;
	default: // ----------------------------------------------------------------------------- default
	  $params[1] = new cHtmlInput("ActionParam1", "TEXT");
	  /*$params[2] = new cHtmlInput("ActionParam2", "TEXT");*/
	break;
  }
  return $params;
}

function getSubTask($idTask) {
  if ($dbRes = myQuery("SELECT * FROM Task WHERE idTask=".getChildId("Task", "Task", $idTask))) {
    return mysql_fetch_assoc($dbRes);
  }
}

function getNextTask($idTask) {
  if ($dbRes = myQuery("SELECT * FROM Task WHERE idTask=$idTask")) {
    if ($lastTask = mysql_fetch_assoc($dbRes)) {
      $query = 
        "SELECT idTask, TaskName, TaskSequence, TaskDuration ".
        "FROM Task, Relation ".
        "WHERE (idTask=RelationLId) AND (RelationType='RRCP') ".
        "AND (RelationLObject='Task') AND (RelationRObject='Task') ".
        "AND (RelationRId=".getParentId("Task", $idTask, "Task").") ".
        "AND (TaskSequence>".$lastTask[TaskSequence].") ".
        "ORDER BY TaskSequence ".
        "LIMIT 1";
      if ($dbR0 = myQuery($query)) {
        return mysql_fetch_assoc($dbR0);
      }
    }
  }
}

function flagsAreSet($idStatus, $flags) {
  if ($dbRes = myQuery("SELECT StatusFlags FROM Status WHERE idStatus=$idStatus")) {
    if ($dbRow = mysql_fetch_assoc($dbRes)) {
      return $dbRow[StatusFlags] & $flags;    // bitwise and
    }
  }
}

?>