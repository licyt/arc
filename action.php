<?php

// load list of fields in htmlSelect
function fieldsForAction($table /* cDbTable */, $value="") {
  $fields = new cHtmlSelect;
  $fields->setAttribute("ID", "ActionField");
  $fields->setAttribute("NAME", "ActionField");
  foreach ($table->fields as $field) {
	$fields->addOption($field->getName(), $field->getName());
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
  /*
  if ($table->hasParentStatus()) {
	$commands->addOption("SET PARENT STATUS", "SET PARENT STATUS");
  }
  */
  $commands->addOption("IS SET", "IS SET");
  $commands->setSelected($value);
  return $commands;
}

function loadParameters($table /* cDbTable */, $command /* string */, $param1="", $param2="") {
  $params = array();
  switch ($command) {
	case "CREATE CHILD": // ----------------------------------------------------------- CREATE CHILD
	  $params[1] = new cHtmlSelect;
	  $params[1]->setAttribute("ID", "ActionParam1");
	  $params[1]->setAttribute("NAME", "ActionParam1");
	  if ($table->children) {
		foreach ($table->children as $child) {
	      $params[1]->addOption($child->getName(), $child->getName());
		}
	  }
	  $params[2] = new cHtmlInput("ActionParam2", "HIDDEN");
	  break;
	case "SET STATUS": // --------------------------------------------------------------- SET STATUS
	  $params[1] = new cHtmlSelect;
	  $params[1]->setAttribute("ID", "ActionParam1");
	  $params[1]->setAttribute("NAME", "ActionParam1");
	  $query =
		"SELECT idStatus, StatusName, StatusColor".
		" FROM Status ".
		" WHERE StatusType=\"".$table->getName()."\"";
	  if ($dbRes=mysql_query($query)) {
	    while ($dbRow=mysql_fetch_assoc($dbRes)) {
		  $params[1]->addOption($dbRow[idStatus], $dbRow[StatusName], $dbRow[StatusColor]);
	    }
	  }
	  $params[1]->setSelected($param1);
	  $params[2] = new cHtmlInput("ActionParam2", "HIDDEN");
	  break;
	/*
	case "SET PARENT STATUS":
	  $params[1] = new cHtmlSelect;
	  $params[1]->setAttribute("ID", "ActionParam1");
	  $params[1]->setAttribute("NAME", "ActionParam1");
	  if ($table->parents) {
	    foreach ($table->parents as $parent) {
		  if ($parent->hasStatus()) {
			$params[1]->addOption($parent->getName(), $parent->getName());
		  }
		}
	  }
	  $params[1]->setSelected($param1);
	  $params[1]->setAttribute("onChansge", "loadParam2();");
	  $params[2] = new cHtmlInput("ActionParam2", "HIDDEN");
	  break;
	*/
	default: // ----------------------------------------------------------------------------- default
	  $params[1] = new cHtmlInput("ActionParam1", "HIDDEN");
	  $params[2] = new cHtmlInput("ActionParam2", "HIDDEN");
	break;
  }
  return $params;
}

?>