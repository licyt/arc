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
	if ($dbRes = mysql_query($query)) {
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
	if ($dbRes = mysql_query($query)) {
		while ($dbRow = mysql_fetch_row($dbRes)) {
	  $rows->addOption($dbRow[0], $dbRow[1]);
		}
	}
	$rows->setSelected($value);
	return $rows->display();
}

function getParentId($childTableName, $childId, $parentTableName) {
  $query=
    "SELECT RelationRId FROM Relation ".
    "WHERE (RelationLObject='$childTableName') ".
    "AND (RelationLId=$childId) ".
    "AND (RelationRObject='$parentTableName')";
  if ($dbRes=mysql_query($query)) {
  	if ($dbRow=mysql_fetch_assoc($dbRes)) {
  	  return $dbRow[RelationRId];
  	} 
  }
  // no relation found
  return -1;
}


?>