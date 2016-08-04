<?php

function loadRightRows($tableName, $value="") {
	$rows = new cHtmlSelect();
	$rows->setAttribute("ID", "RelationRId");
	$rows->setAttribute("NAME", "RelationRId");
	$rows->setAttribute("onChange", "hide('RelationDelete');show('RelationOk');");
	$lookupName = gui($tableName, "lookupField", $tableName."Name");
	$query=
	"SELECT id".$tableName.", ".$lookupName.
	" FROM ".$tableName.
	" ORDER BY ".$lookupName;
	if ($dbRes = myQuery($query)) {
		while ($dbRow = mysql_fetch_row($dbRes)) {
	  $rows->addOption($dbRow[0], $dbRow[1]);
		}
	}
	$rows->setSelected($value);
	return $rows->display();
}

function loadLeftRows($tableName, $value="") {
	$rows = new cHtmlSelect();
	$rows->setAttribute("ID", "RelationLId");
	$rows->setAttribute("NAME", "RelationLId");
	$rows->setAttribute("onChange", "hide('RelationDelete');show('RelationOk');");
	$lookupName = gui($tableName, "lookupField", $tableName."Name");
	$query=
	"SELECT id".$tableName.", ".$lookupName.
	" FROM ".$tableName.
	" ORDER BY ".$lookupName;
	if ($dbRes = myQuery($query)) {
		while ($dbRow = mysql_fetch_row($dbRes)) {
	  $rows->addOption($dbRow[0], $dbRow[1]);
		}
	}
	$rows->setSelected($value);
	return $rows->display();
}

function getParentId($childTableName, $childId, $parentTableName) {
  // special behaviour for StatusLog which uses foreign key to status
  if (($childTableName=="StatusLog") && ($parentTableName=="Status")) {
  	$query="SELECT StatusLog_idStatus FROM StatusLog WHERE idStatusLog=$childId";
    if ($dbRes=myQuery($query)) {
  	  if ($dbRow=mysql_fetch_assoc($dbRes)) {
  	    return $dbRow[StatusLog_idStatus];
  	  } 
    }
  }
  // default search in table Relation
  $query=
    "SELECT RelationRId FROM Relation ".
    "WHERE (RelationLObject='$childTableName') ".
    "AND (RelationLId=$childId) ".
    "AND (RelationRObject='$parentTableName')";
  if ($dbRes=myQuery($query)) {
  	if ($dbRow=mysql_fetch_assoc($dbRes)) {
  	  return $dbRow[RelationRId];
  	} 
  }
  // no relation found
  return -1;
}


function insertRRCP($LObject, $LId, $RObject, $RId) {
  $query = 
    "INSERT INTO Relation SET ".
    "RelationType=\"RRCP\", ".
    "RelationLObject=\"$LObject\", ".
    "RelationLId=$LId, ".
    "RelationRObject=\"$RObject\", ".
    "RelationRId=$RId";
  return myQuery($query);
}
?>