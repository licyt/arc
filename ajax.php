<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com

require_once("html.php");
require_once("database.php");

//var_dump($_REQUEST);

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

  $elementId = $_REQUEST[param0];
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

if (isset($_REQUEST[browseFile])) {
  $SCRIPT_DIRECTORY = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
  $RepositoryDir = $SCRIPT_DIRECTORY.$RepositoryPath;
  $filePath = $RepositoryDir.$_REQUEST[param1];
  echo listDir($filePath); 
}

?>