<?php

// migrate foreign keys to relations

require_once("database.php");

// navigate thru all tables in scheme
foreach ($dbScheme->tables as $table) {
  $tableName = $table->getName();
  $query="SELECT * FROM $tableName";
  if ($dbRes=mysql_query($query)) {
  	// navigate thru all rows in table
  	while ($dbRow=mysql_fetch_assoc($dbRes)) {
  	  // go thru all columns in a row
  	  foreach ($table->fields as $fieldName=>$field) {
  	  	// identify foreing key by field name containing "_id"
  	  	if ($pos=strpos($fieldName, "_id")) {
  	  	  // "parent" table name follows by "id"
  	  	  $t2Name=substr($fieldName, $pos+3);
  	  	  // build SQL
  	  	  $q2=
    	  	"INSERT INTO Relations SET ".
  	  	    "RelationLeftTable='$tableName', ".
  	  	    "RelationLeftId=".$dbRow["id".$tableName].", ".
    	  	"RelationRightTable='$t2Name', ".
  	  	    "RelationRightId=".$dbRow[$fieldName];
  	  	  // try to insert Relation
  	  	  if ($dbR2=mysql_query($q2)) {
  	  	  	// success
  	  	  	echo $q2."<BR>";
  	  	  } else {
  	  	  	// fail
  	  	  	echo "Error: ".$q2."<BR>";
  	  	  }
  	  	}
  	  }
  	}
  }
}

?>