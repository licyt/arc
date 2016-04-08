//
// This script generates css file that implements tag hidding for idLabelTag and idInputTag
//
// USAGE: Run in browser and copy+paste the output into desired css file
//

include_once ("./database.php");
include_once("./dbConfig.php");

<?php
	echo "Begin"
	
	$db = new cDbScheme;
		
	$db->link($dbServerName, $dbUser, $dbPassword);
	
	// get all table names
	$db->useDb($dbName);

	// for each table name
	foreach ($db->tables[$tableName] as $i => $value) {
        // create an instance of cDbTable
		if(!empty ($table) ) {
			unset($table);
		}
		$table = new cDbTable($value);
		
		// find which field is autoincrement
		foreach ($table->fields as $j => $field ) {
			//
			if ( !($field->isAutoInc()) ) {
				continue;
			}
			else {
				// we found our autoincrementing field
				echo $field->getFieldByName();
				break;
			}
		}
	}
	
	// missing disconnect
	
	echo "End"
// cssIdTagScript

?>