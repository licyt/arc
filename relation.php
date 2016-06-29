<?php

function loadRightRows($tableName, $value="") {
	$rows = new cHtmlSelect();
	$rows->setAttribute("ID", "RelationRightId");
	$rows->setAttribute("NAME", "RelationRightId");
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
	$rows->setAttribute("ID", "RelationLeftId");
	$rows->setAttribute("NAME", "RelationLeftId");
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


?>