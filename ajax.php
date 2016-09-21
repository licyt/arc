<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com
// 2016 (C) Rastislav Seč, rastislav.sec@gmail.com aka tomcat

session_start();

require_once("html.php");
require_once("database.php");
require_once 'gantt.php';

//var_dump($_REQUEST);

// -----------------------------------------------------------------------  list files in directory
function listDir($path) {
  //echo $path;
  $dirName = pathinfo($path, PATHINFO_DIRNAME);
  $baseName = pathinfo($path, PATHINFO_BASENAME);
  $extension = pathinfo($path, PATHINFO_EXTENSION);
  if (is_dir($path)) {
    $directory = scandir($path);
  } else {
  	$directory = scandir($dirName);
  }

  $elementId = $_REQUEST[elementId];
  $div = new cHtmlDiv;
  
  foreach ($directory as $fileName) {
  	if (($fileName == ".") || 
  	   (($path == $GLOBALS['RepositoryDir']) && ($fileName == ".."))) 
  	    continue;
  	$fullName = (is_dir($path)?$path:$dirName)."/".$fileName;
  	if (is_dir($fullName)) {
  	  $img = new cHtmlImg("./img/folder.gif");
  	}
  	$js=
      "e=elementById('$elementId');".
      (is_dir($path) ? "" : "updatePath('$elementId', '..');").
      "updatePath('$elementId', '$fileName');".
  	  (is_file($fullName) ? "hide('fileBrowser');" : "browseFile(e);");
  	$div->setAttribute("onClick", $js);
    $div->setAttribute("CONTENT", (is_dir($fullName) ? $img->display() : "").$fileName);
    $result.=$div->display();
  }
  return $result;
}


// ======================================================================= REQUEST processing switch


// ------------------------------------------------------------------------------ browseFile
if (isset($_REQUEST[browseFile])) {
  $SCRIPT_DIRECTORY = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
  $RepositoryDir = $SCRIPT_DIRECTORY.$RepositoryPath;
  $filePath = $RepositoryDir.$_REQUEST[filePath];
  echo listDir($filePath); 
}

// ----------------------------------------------------------------------------- searchType by tomcat
elseif( $_REQUEST["searchType"] === "suggestSearch" ) {
  $con = mysqli_connect($dbServerName,$dbUser,$dbPassword,$dbName);
  if (!$con) {
    die('SQL ERRORL:'.mysqli_error($con).' Could not connect to '.$dbServerName );
  }
  $retval = "";
  $optionsName = "";
  $sql=
    "SELECT id".$_REQUEST["tableName"].",".$_REQUEST["columnName"].
  	" FROM ".$_REQUEST["tableName"].
  	" WHERE ".$_REQUEST["columnName"]." LIKE '".$_REQUEST["searchString"]."%'".
  	" ORDER BY ".$_REQUEST["columnName"]." ASC";
  $optionList = Array();
  if( $result = mysqli_query($con,$sql) ) {
  	while( $row = $result->fetch_assoc() ) {
  	  $retval .= 
    	  "<option".
    	    " data-value=\"".$row["id".$_REQUEST["tableName"]]."\"".
    	    " name=\"".$_REQUEST["destinationId"]."Options\">".
    	    $row[$_REQUEST["columnName"]].
  	      "</option>";
    }
  }
  mysqli_close($con);
  echo $retval;
}

// ------------------------------------------------------------------------------------ loadTable
elseif (isset($_REQUEST[loadTable])) {
  if ($_REQUEST[table]) {
  	$table = $dbScheme->tables[$_REQUEST[table]]; // cDbTable
  	$fields = fieldsForAction($table);
  	$commands = commandsForAction($table);
  } else {
  	$fields = new cHtmlInput("ActionField", "HIDDEN");
  	$commands = new cHtmlInput("ActionCommand", "HIDDEN");
  }
  echo $fields->display()."|".$commands->display();
}

// --------------------------------------------------------------------------------- loadParameters
elseif (isset($_REQUEST[loadParameters])) {
  $params = loadParameters($dbScheme->tables[$_REQUEST[table]], $_REQUEST[command]);
  echo $params[1]->display();
}

// ------------------------------------------------------------------------------------ loadRightRows
elseif (isset($_REQUEST[loadRightRows])) {
  echo loadRightRows($_REQUEST[table]);
}

// ------------------------------------------------------------------------------------ loadLeftRows
elseif (isset($_REQUEST[loadLeftRows])) {
	echo loadLeftRows($_REQUEST[table]);
}

// ------------------------------------------------------------------------------------ loadGantt
elseif (isset($_REQUEST[loadGantt])) {
	$sG = new statusGantt();
	$sG->statusType = "Project";
	$sG->iFrom =date("Y-m-d H:i:s", $_REQUEST[leftValue]);
	$sG->iTill =date("Y-m-d H:i:s", $_REQUEST[rightValue]);
	$sG->iWidth = 800;
	$sG->loadLanes();
	echo $sG->display(true, false);
}



/*
 *                       B R O W S E R     R O W      M A N I P U L A T I O N
 */


// ------------------------------------------------------------------------------------------- insertRow
elseif (isset($_REQUEST[insertRow])) {
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $dbTable->setParent($dbScheme->getTableByName($_REQUEST[parentName]));
  $htmlTable = new cHtmlTable($dbTable);
  $result[oldRowId] = $dbTable->getCurrentRecordId();
  $oldRow = $dbTable->displayRow($result[oldRowId], $dbTable->getCurrentRecord());
  $result[onClick] = $oldRow[onClick];
  $htmlTable->addRow(
    $_REQUEST[tableName]."Row".$result[oldRowId], 
    $oldRow
  );
  $result[oldRow] = preg_replace('/<\/?TR[^>]*>/i', '', $htmlTable->displayRows());
  // reuse cHtmlTable to format html of the new table row
  $htmlTable->deleteRows();
  $dbTable->setMode("INSERT");
  $dbTable->setCurrentRecordId(-1);
  $htmlTable->addRow(
      $_REQUEST[tableName]."Row-1",
      $dbTable->editColumns()
      );
  $result[newRow] = preg_replace('/<\/?TR[^>]*>/i', '', $htmlTable->displayRows());
  echo json_encode($result);
}

// ------------------------------------------------------------------------------------------- loadRow
// ------------------------------------------------------------------------------------------ submitRow
elseif (isset($_REQUEST[loadRow])||isset($_REQUEST[submitRow])) {
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  if ($_REQUEST[parentName]) {
    $dbTable->setParent($dbScheme->getTableByName($_REQUEST[parentName]));
  }
  // -------------- submitRow
  if (isset($_REQUEST[submitRow])) {
    if ($dbTable->getCurrentRecordId()==-1) {
      $dbTable->setMode("INSERT");
      $result[oldRowId] = -1;
    }
    $dbTable->commit(); //----------------------------------------------- commit() !
    $result[newRowId] = $dbTable->getCurrentRecordId();
    $_REQUEST[newRowId] = $result[newRowId]; 
  } else {
    $result[oldRowId] = $dbTable->getCurrentRecordId();
  }
  // -------------- loadRow
  $dbTable->getCurrentRecord($dbTable->getCurrentRecordId());
  if ($_REQUEST[newRowId]) {
    $dbTable->getCurrentRecord($_REQUEST[newRowId]);
  }
  $oldRow = $dbTable->displayRow($result[oldRowId], $dbTable->getLastRecord());
  $result[onClick] = str_replace(", -1);", ", ".$result[newRowId].");", $oldRow[onClick]);
  // use cHtmlTable to format html of the old table row
  $htmlTable = new cHtmlTable($dbTable);
  $htmlTable->addRow(
    $_REQUEST[tableName]."Row".$result[oldRowId], 
    $oldRow
  );
  $result[oldRow] = preg_replace('/<\/?TR[^>]*>/i', '', $htmlTable->displayRows());
  // reuse cHtmlTable to format html of the new table row
  $htmlTable->deleteRows();
  $editRow = $dbTable->editColumns($_REQUEST[newRowId]);
  $htmlTable->addRow(
    $_REQUEST[tableName]."Row".$_REQUEST[newRowId],
    $editRow
  );
  $result[newRow] = preg_replace('/<\/?TR[^>]*>/i', '', $htmlTable->displayRows());
  $result[onEditClick] = $editRow[onClick];
  $result[onKeyPress] = $editRow[onKeyPress];
  // sub-browsers
  $sbRow["sbIndent"]="";
  $sbRow["sbColSpan"] = sizeof($oldRow)-1;
  $sbRow["subBrowser"] = $dbTable->subBrowsers();
  $htmlTable->deleteRows();
  $htmlTable->addRow($dbTable->getName()."Sb".$_REQUEST[newRowId], $sbRow);
  // strip <TR></TR> but only at the beginning and the end of the row
  $result[subBrowser] = preg_replace('/^<TR[^>]*>/i', '', $htmlTable->displayRows());
  $result[subBrowser] = preg_replace('/<\/TR[^>]*>$/i', '', $result[subBrowser]);
  echo json_encode($result);
}

// --------------------------------------------------------------------------------------------- deleteRow
elseif (isset($_REQUEST[deleteRow])) {
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $result[oldRowId] = $dbTable->getCurrentRecordId();
  $dbTable->setMode("DELETE");
  $dbTable->commit(); //--------------------------------------------- commit() !
  echo json_encode($result);
}

// --------------------------------------------------------------------------------------------- switchTab
elseif (isset($_REQUEST[switchTab])) {
  //$_SESSION[tabControl][$_REQUEST[tableName]][selected] = $_REQUEST[tabName];
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  switch ($_REQUEST[tabName]) {
    case "RelationLeft" : 
      $_SESSION[relation] = 1; 
      break;
    case "RelationRight": 
      $_SESSION[relation] = 2; 
      break;
    default: 
      $_SESSION[relation] = 0; 
  }
  $dbTable->lumpChildren();
  $result[currentRecordId] = $dbTable->getCurrentRecordId();
  $editRow = $dbTable->editColumns($dbTable->getCurrentRecordId());
  $sbRow["sbIndent"]="";
  $sbRow["sbColSpan"] = sizeof($editRow)-1;
  $sbRow["subBrowser"] = $dbTable->subBrowsers();
  $htmlTable = new cHtmlTable($dbTable);
  $htmlTable->addRow($dbTable->getName()."Sb".$dbTable->getCurrentRecordId(), $sbRow);
  // strip <TR></TR> but only at the beginning and the end of the row
  $result[subBrowser] = preg_replace('/^<TR[^>]*>/i', '', $htmlTable->displayRows());
  $result[subBrowser] = preg_replace('/<\/TR[^>]*>$/i', '', $result[subBrowser]);
  echo json_encode($result);
}

// --------------------------------------------------------------------------------------- jumpToRow
elseif (isset($_REQUEST[jumpToRow])) {
  $tableRelation = $dbScheme->getTableByName("Relation");
  $relation = $tableRelation->getCurrentRecord($_REQUEST[idRelation]);
  switch ($_REQUEST[relationDirection]) {
    case 1:
      $_SESSION[tabControl][Admin][selected] = $relation[RelationRObject];
      unset($_SESSION[tabControl][Admin][$relation[RelationRObject]]);
      $targetTable = $dbScheme->getTableByName($relation[RelationRObject]);
      $targetTable->getCurrentRecord($relation[RelationRId]);
      break;
    case 2:
      $_SESSION[tabControl][Admin][selected] = $relation[RelationLObject];
      unset($_SESSION[tabControl][Admin][$relation[RelationLObject]]);
      $targetTable = $dbScheme->getTableByName($relation[RelationLObject]);
      $targetTable->getCurrentRecord($relation[RelationLId]);
      break;
  }
  $targetTable->unsetParent();
  $result[browser] = $targetTable->browse();
  $result[rowId] = $targetTable->getName()."Row".$targetTable->getCurrentRecordId();
  echo json_encode($result);
}


// ------------------------------------------------------------------------------------- columnMenu
elseif (isset($_REQUEST[columnMenu])) {
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $result[columnMenu] = $dbTable->columnMenu($_REQUEST[columnName]);
  echo json_encode($result);
}

// ------------------------------------------------------------------------------------- addColumn
elseif (isset($_REQUEST[addColumn])) {
  $_SESSION[columnMode] = "add";
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $result[columnEditor] = $dbTable->columnEditor("");
  echo json_encode($result);
}

// ------------------------------------------------------------------------------------- alterColumn
elseif (isset($_REQUEST[alterColumn])) {
  $_SESSION[columnMode] = "alter";
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $result[columnEditor] = $dbTable->columnEditor($_REQUEST[columnName]);
  echo json_encode($result);
}

?>