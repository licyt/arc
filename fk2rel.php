<?php

exit; // remove this line if you want to run this script again

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
  	  foreach ($table->fields as $field) {
  	  	$fieldName = $field->getName();
  	  	// identify foreing key by field name containing "_id"
  	  	if ($pos=strpos($fieldName, "_id")) {
  	  	  // "parent" table name follows by "id"
  	  	  $t2Name=substr($fieldName, $pos+3);
  	  	  // build SQL
  	  	  $q2=
    	  	"INSERT INTO Relation SET ".
  	  	    "RelationLObject='$tableName', ".
  	  	    "RelationLId=".$dbRow["id".$tableName].", ".
    	  	"RelationRObject='$t2Name', ".
  	  	    "RelationRId=".$dbRow[$fieldName];
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