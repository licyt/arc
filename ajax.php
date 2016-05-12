<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com

require_once("html.php");
require_once("database.php");

//var_dump($_REQUEST);

function listDir($path) {
  echo $path;
  $elementId = $_REQUEST[param0];
  $directory = scandir($path);
  $div = new cHtmlDiv;
  foreach ($directory as $file) {
  	if (is_dir($file)) {
  	  $img = new cHtmlImg("./img/folder.gif");
  	}
  	$div->setAttribute(onClick, "document.getElementById('$elementId').value='$file'");
    $div->setAttribute("CONTENT", (is_dir($file) ? $img->display() : "").$file);
    $result.=$div->display();
  }
  return $result;
}

if (isset($_REQUEST[browseFile])) {
  $SCRIPT_DIRECTORY = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
  echo listDir($SCRIPT_DIRECTORY.$RepositoryPath);
}

?>