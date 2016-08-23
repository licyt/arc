<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com
// 2016 (C) Rastislav Seč, rastislav.sec@gmail.com aka tomcat

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
if( $_REQUEST["searchType"] === "suggestSearch" ) {
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
if (isset($_REQUEST[loadTable])) {
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
if (isset($_REQUEST[loadParameters])) {
  $params = loadParameters($dbScheme->tables[$_REQUEST[table]], $_REQUEST[command]);
  echo $params[1]->display()/*."|".$params[2]->display()*/;
}

/*
// --------------------------------------------------------------------------------------- loadParam2
if (isset($_REQUEST[loadParam2])) {
  $table = $dbScheme->tables[$_REQUEST[param1]];
  $param2 = new cHtmlSelect;
  $param2->setAttribute("ID", "ActionParam2");
  $param2->setAttribute("NAME", "ActionParam2");
  $query = 
  	"SELECT StatusName, StatusColor".
    " FROM Status ".
    " WHERE StatusType=\"".$_REQUEST[param1]."\"";
  if ($dbRes=mySQL($query)) {
  	while ($dbRow=mysql_fetch_assoc($dbRes)) {
  	  $param2->addOption($dbRow[StatusName], $dbRow[StatusName], $dbRow[StatusColor]);
  	}
  }
  echo $param2->display();
}
*/

// ------------------------------------------------------------------------------------ loadRightRows
if (isset($_REQUEST[loadRightRows])) {
  echo loadRightRows($_REQUEST[table]);
}

// ------------------------------------------------------------------------------------ loadLeftRows
if (isset($_REQUEST[loadLeftRows])) {
	echo loadLeftRows($_REQUEST[table]);
}

// ------------------------------------------------------------------------------------ loadGantt
if (isset($_REQUEST[loadGantt])) {
	$sG = new statusGantt();
	$sG->statusType = "Project";
	$sG->iFrom =date("Y-m-d H:i:s", $_REQUEST[leftValue]);
	$sG->iTill =date("Y-m-d H:i:s", $_REQUEST[rightValue]);
	$sG->iWidth = 800;
	$sG->loadLanes();
	echo $sG->display(true, false);
}

?>