<?php

require_once 'database.php';

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

function getFirstChildId($childTableName, $parentTableName, $parentId) {
  // special behaviour for StatusLog which uses foreign key to status
  if (($childTableName=="StatusLog") && ($parentTableName=="Status")) {
    $query="SELECT idStatusLog FROM StatusLog WHERE StatusLog_idStatus=$parentId";
    if ($dbRes=myQuery($query)) {
      if ($dbRow=mysql_fetch_assoc($dbRes)) {
        return $dbRow[idStatusLog];
      }
    }
  }
  // default search in table Relation
  $query=
    "SELECT RelationLId FROM Relation ".
    "WHERE (RelationLObject='$childTableName') ".
    "AND (RelationRObject='$parentTableName') ".
    "AND (RelationRId=$parentId) ";
  if ($dbRes=myQuery($query)) {
    if ($dbRow=mysql_fetch_assoc($dbRes)) {
      return $dbRow[RelationLId];
    }
  }
  // no relation found
  return -1;
}

function getChildren($childTableName, $parentTableName, $parentId) {
  global $dbScheme;
  $result = array();
  // special behaviour for StatusLog which uses foreign key to status
  if (($childTableName=="StatusLog") && ($parentTableName=="Status")) {
    $query="SELECT idStatusLog FROM StatusLog WHERE StatusLog_idStatus=$parentId";
    if ($dbRes=myQuery($query)) {
      while ($dbRow=mysql_fetch_assoc($dbRes)) {
        array_push($result, $dbRow);
      }
    }
  } else {
    // default search in table Relation
    $dbTable = $dbScheme->getTableByName($childTableName);
    $query=
      "SELECT RelationLId FROM Relation ".
      "WHERE (RelationLObject='$childTableName') ".
      "AND (RelationRObject='$parentTableName') ".
      "AND (RelationRId=$parentId) ";
    if ($dbRes=myQuery($query)) {
      while ($dbRow=mysql_fetch_assoc($dbRes)) {
        array_push($result, $dbTable->getCurrentRecord($dbRow[RelationLId]));
      }
    }
  }
  return $result;
}

function updateStatus($LObject, $LId, $statusId) {
  $query =
    "UPDATE Relation SET ".
      "RelationRId=$statusId".
    " WHERE (RelationType=\"RRCP\")".
    " AND (RelationLObject='$LObject')".
    " AND (RelationLId=$LId)".
    " AND (RelationRObject='Status')";
  myQuery($query);
  if (!mysql_affected_rows()) { // no rows affected = relation doesn't exist yet
    // create relation to status iow. "insert" status
    insertRRCP($LObject, $LId, 'Status', $statusId);
  }
}

function insertRRCP($LObject, $LId, $RObject, $RId) {
  if (!$LObject || !($LId>0) || !$RObject || !($RId>0)) return null;
  $query = 
    "INSERT INTO Relation SET ".
    "RelationType=\"RRCP\", ".
    "RelationLObject=\"$LObject\", ".
    "RelationLId=$LId, ".
    "RelationRObject=\"$RObject\", ".
    "RelationRId=$RId";
  return myQuery($query);
}

function insertTTCP($LObject, $RObject) {
  if (!$LObject || !$RObject) return null;
  $query =
  "INSERT INTO Relation SET ".
  "RelationType=\"TTCP\", ".
  "RelationLObject=\"$LObject\", ".
  "RelationRObject=\"$RObject\"";
  return myQuery($query);
}


function jobParent($sourceJobId) {
  $query =
    "SELECT RelationRObject, RelationRId ".
    "FROM Relation ".
    "WHERE (RelationLObject='Job') AND (RelationLId=$sourceJobId) ".
    "AND (RelationRObject<>'Status') ".
    "AND (RelationRObject<>'Task') ".
    "AND (RelationRObject<>'Job') ";
  return mysql_fetch_assoc(myQuery($query));
}

function copyJobTarget($sourceJobId, $targetJobId) {
  if ($parent = jobParent($sourceJobId)) { 
    insertRRCP("Job", $targetJobId, $parent[RelationRObject], $parent[RelationRId]);
  }
}

?>