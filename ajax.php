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

function tableList() {
  $query = "SELECT GUIvalue FROM GUI WHERE GUIattribute='tableName'";
  if ($dbRes = myQuery($query)) {
    $button = new cHtmlDiv("buttonCancel");
    $button->setAttribute("CONTENT", "o Cancel");
    $button->setAttribute("onClick", "hide('columnMenu');");
    $result .= $button->display();
    $button = new cHtmlDiv("buttonCancel");
    $button->setAttribute("CONTENT", "+ Add");
    $button->setAttribute("CLASS", "addButton");
    $button->setAttribute("onClick", "hide('columnMenu');tableDialog(event, 'new');");
    $result .= $button->display();
    while ($dbRow = mysql_fetch_assoc($dbRes)) {
      $tableName = $dbRow[GUIvalue];
      $button = new cHtmlDiv("buttonTable".$tableName);
      $button->setAttribute("CONTENT", $tableName);
      $button->setAttribute("onClick", "hide('columnMenu');tableDialog(event, '$tableName');");
      $result .= $button->display();
    }
  }
  return $result;
}

function tableDialog($tableName) {
  $input = new cHtmlInput("tableName", "text", $tableName);
  $top = new cHtmlInput("buttonTop", "text", gui("button".$tableName, "top", 0));
  $left = new cHtmlInput("buttonLeft", "text", gui("button".$tableName, "left", 0));
  $width = new cHtmlInput("buttonWidth", "text", gui("button".$tableName, "width", 0));
  $save = new cHtmlDiv("buttonSave");
  $save->setAttribute("CONTENT", "save");
  $save->setAttribute("onClick", "hide('columnMenu');tableSave();");
  $cancel = new cHtmlDiv("buttonCancel");
  $cancel->setAttribute("CONTENT", "cancel");
  $cancel->setAttribute("onClick", "hide('columnMenu');");
  return 
    table(
      tr(td("Table").td($input->display())).
      tr(td("top").td($top->display())).
      tr(td("left").td($left->display())).
      tr(td("width").td($width->display())). 
      tr(td($save->display()).td($cancel->display()))  
    );
}

function camelize($string, $space=" ") {
  return implode($space, array_map('ucfirst', explode($space, $string)));
}

function createTable($tableName) {
  myQuery(
    "CREATE TABLE IF NOT EXISTS $tableName ".
    "(id".$tableName." INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id".$tableName."))"
  );
}

function showTableInMenu($tableName) {
  global $dbName;
  if (!iug($tableName, "tableName", false)) 
    ugi($dbName, "tableName", $tableName, true);
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
  $newRow = $htmlTable->displayRows();
  $json = json_encode($newRow);
  $error = json_last_error();
  $result[newRow] = preg_replace('/<\/?TR[^>]*>/i', '', $newRow);
  $result[onEditClick] = $editRow[onClick];
  $result[onKeyPress] = $editRow[onKeyPress];
  // sub-browsers
  $sbRow["sbIndent"]="";
  $sbRow["sbColSpan"] = sizeof($oldRow)-1;
  $sbRow["subBrowser"] = $dbTable->subBrowsers($_REQUEST[newRowId]);
  $htmlTable->deleteRows();
  $htmlTable->addRow($dbTable->getName()."Sb".$_REQUEST[newRowId], $sbRow);
  // strip <TR></TR> but only at the beginning and the end of the row
  $result[subBrowser] = preg_replace('/^<TR[^>]*>/i', '', $htmlTable->displayRows());
  $result[subBrowser] = preg_replace('/<\/TR[^>]*>$/i', '', $result[subBrowser]);
  
  //foreach ($result as $key=>$value) {$result[$key] = addslashes($value);}
  
  $json=json_encode($result);
  echo $json;
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
  $editRow = $dbTable->editColumns($result[currentRecordId]);
  $sbRow["sbIndent"]="";
  $sbRow["sbColSpan"] = sizeof($editRow)-1;
  $sbRow["subBrowser"] = $dbTable->subBrowsers($result[currentRecordId]);
  $htmlTable = new cHtmlTable($dbTable);
  $htmlTable->addRow($dbTable->getName()."Sb".$result[currentRecordId], $sbRow);
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

// -------------------------------------------------------
// d a t a   s t r u c t u r e   m a n i p u l a t i o n 
// ------------------------------------------------------- 

// ------------------------------------------------------------------------------------- tableList
elseif (isset($_REQUEST[tableList])) {
  $result[tableList] = tableList();
  echo json_encode($result);
}
// ------------------------------------------------------------------------------------- tableDialog
elseif (isset($_REQUEST[tableDialog])) {
  $result[tableDialog] = tableDialog($_REQUEST[tableName]);
  echo json_encode($result);
}
// ------------------------------------------------------------------------------------- tableSave
elseif (isset($_REQUEST[tableSave])) {
  $tableName = camelize($_REQUEST[tableName]);
  $top = $_REQUEST[top];
  $left = $_REQUEST[left];
  $width = $_REQUEST[width];
  
  createTable($tableName);
  showTableInMenu($tableName);
  
  ugi("button".$tableName, "top", $top);
  ugi("button".$tableName, "left", $left);
  ugi("button".$tableName, "width", $width);
  
  
  
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
  $_SESSION[column][mode] = "add";
  $_SESSION[column][name] = $_REQUEST[columnName];
  $_SESSION[column][table] = $_REQUEST[tableName];
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $result[columnEditor] = $dbTable->columnEditor("");
  echo json_encode($result);
}

// ------------------------------------------------------------------------------------- changeColumn
elseif (isset($_REQUEST[changeColumn])) {
  $_SESSION[column][mode] = "change";
  $_SESSION[column][name] = $_REQUEST[columnName];
  $_SESSION[column][table] = $_REQUEST[tableName];
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $result[columnEditor] = $dbTable->columnEditor($_REQUEST[columnName]);
  echo json_encode($result);
}

// ------------------------------------------------------------------------------------- moveColumn
elseif (isset($_REQUEST[moveColumn])) {
  switch ($_SESSION[column][mode]) {
    case "move":
      $_SESSION[column][mode]="";
      $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
      $dbTable->swapColumns($_SESSION[column][name], $_REQUEST[columnName]);
      $result[browser] = $dbTable->browse();
      echo json_encode($result);
      break;
    default:
      $_SESSION[column][mode]="move";
      $_SESSION[column][name] = $_REQUEST[columnName];
  }
}

// ------------------------------------------------------------------------------------- deleteColumn
elseif (isset($_REQUEST[deleteColumn])) {
  $dbTable = $dbScheme->getTableByName($_REQUEST[tableName]);
  $dbTable->deleteColumn($_REQUEST[columnName]);
  $result[browser] = $dbTable->browse();
  echo json_encode($result);
}

// ------------------------------------------------------------------------------------- confirmColumn
elseif (isset($_REQUEST[confirmColumn])) {
  $tableName = $_SESSION[column][table];
  $dbTable = $dbScheme->getTableByName($tableName);
  switch ($_SESSION[column][mode]) {
    case "add":
      $dbTable->addColumn($_REQUEST[columnName], $_REQUEST[displayedName], $_REQUEST[dataType], $_SESSION[column][name]);
      break;
    case "change":
      $dbTable->modifyColumn($_SESSION[column][name], $_REQUEST[displayedName], $_REQUEST[dataType]);
      break;
  }
  $result[browser] = $dbTable->browse();
  echo json_encode($result);
}

?>