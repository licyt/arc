<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com

session_start();

require_once 'color.php';
require_once("database.php");

$admin = $dbScheme->admin();

echo 
  head(
    charset().
	  linkCss("css/hmat.css").
  	linkCss("css/gantt.css").
	  //linkCss("css/hideIdTags.css").
  	
    style($dbScheme->style).

    linkCss("css/project.css").
  	linkCss("css/quote.css").
  	linkCss("css/status.css").
  	linkCss("css/task.css").
  	linkCss("css/job.css").
  	linkCss("css/note.css").
  	linkCss("css/dataset.css").
  	linkCss("css/demand.css").
  	linkCss("css/invoice.css").
  	linkCss("css/build.css").
  	linkCss("css/deliverynote.css").
  	linkCss("css/industry.css").
  	linkCss("css/company.css").
  	linkCss("css/account.css").
  	linkCss("css/address.css").
  	linkCss("css/material.css").
  	linkCss("css/person.css").
  	linkCss("css/printparameters.css").
  	linkCss("css/part.css").
  	linkCss("css/platform.css").
  	linkCss("css/deliverytransport.css").
  	linkCss("css/gui.css").
  	linkCss("css/relation.css").
  	linkCss("css/statuslog.css").
  	linkCss("css/action.css").
  	linkCss("css/payment.css").
  	linkCss("css/history.css").
  	
  	linkCss("css/datepicker.css").
  		
  	linkJs("https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"). // jQuery @ google
  	
  	linkJs("js/jscolor.js").
  	linkJs("js/datepicker.js").
  	
    linkJs("js/hmat.js").
    linkJs("js/basic.js").
  	linkJs("js/ajax.js").
  	linkJs("js/gantt.js").
  	linkJs("js/cHtmlSuggest.js")
  		
  ).
  body(
    "<div>".
  	  "<div id='svnrevision'>".
        "<span style=\"font-size:14px;\">Life Cycle Tracker ".
          "<img src=\"img/LiCyTlogo.png\" style=\"height:20px;display:inline;\">".
        " gramm-A-ton</span>".
        " SVN:".shell_exec('svnversion').
        " DB:".$dbName."[".$GLOBALS[queryCount]."]".
      "</div>".
      "<div id='columnMenu'></div>".
      "<div id='fileBrowser'></div>".
      "<img id='logo' src='img/hmat_logo.png'>".
  	  $admin.
    "</div>",
  	"Load();"
  );


//print_r($_SESSION);  //debug
//print_r($_POST);


?>