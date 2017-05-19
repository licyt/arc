<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com

error_reporting(E_ERROR);
session_name("licyt");
if (!session_start()) {
  echo "fuck off";
  exit;
}


require_once 'color.php';
require_once("database.php");
require_once("access.php");

$admin = $dbScheme->admin();

echo 
  head(
    charset().
	  linkCss("css/hmat.css").
  	linkCss("css/gantt.css").
  	
    style($dbScheme->style).
  	
    linkCss("css/status.css").
  	linkCss("css/task.css").
  	linkCss("css/job.css").
  	linkCss("css/note.css").
  	linkCss("css/gui.css").
  	linkCss("css/relation.css").
  	linkCss("css/statuslog.css").
  	linkCss("css/action.css").
    linkCss("css/history.css").
  	
  	linkCss("css/datepicker.css").
  		
  	linkJs("https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"). // jQuery @ google
  	
  	linkJs("js/jscolor.js").
  	linkJs("js/datepicker.js").
  	
    linkJs("js/basic.js").
  	linkJs("js/ajax.js").
    linkJs("js/alignTables.js").
  	linkJs("js/gantt.js").
  	linkJs("js/cHtmlSuggest.js").
    linkJs("js/hmat.js")
      
  ).
  body(
    isin() ?
      "<div>".
    	  "<div id='svnrevision'>".
          "<span style=\"font-size:14px;\">Life Cycle Tracker ".
            "<img src=\"img/LiCyTlogo.png\" style=\"height:20px;display:inline;\">".
          " gramm-A-tone</span>".
          " SVN:".shell_exec('svnversion').
          " DB:".dbSelect()."[".$GLOBALS[queryCount]."]".
        "</div>".
        "<div id='popupMenu'></div>".
        "<div id='fileBrowser'></div>".
        "<div id='logo'><img src='img/".$dbName."_logo.png' class='imageLogo' onclick='tableList(event);'></div>".
    	  $admin.
      "</div>"
    : loginForm(),
  	"Load();"
  );


//print_r($_SESSION);  //debug
//print_r($_POST);


?>