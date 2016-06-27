<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com

session_start();

require_once("database.php");

echo 
  head(
    charset().
	linkCss("css/hmat.css").
	linkCss("css/hideIdTags.css").
  	
  	linkCss("css/project.css").
  	linkCss("css/quote.css").
  	linkCss("css/status.css").
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
  	linkCss("css/platform_has_part.css").
  	linkCss("css/platform.css").
  	linkCss("css/deliverytransport.css").
  	linkCss("css/gui.css").
  	linkCss("css/relation.css").
  	linkCss("css/statuslog.css").
  	linkCss("css/action.css").
  	linkCss("css/payment.css").
  	linkCss("css/history.css").
  	linkCss("css/datepicker.css").
  		
  	//linkJs("https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"). // jQuery @ google
  	
  	linkJs("js/jscolor.js").
  	linkJs("js/datepicker.js").
  	
    linkJs("js/hmat.js").
  	linkJs("js/cHtmlSuggest.js").
  	linkJs("js/ajax.js")
  	
  ).
  body(
  	  "<div id='svnrevision'>SVN REV:".shell_exec('svnversion')."</div>".
  	  "<div style='position:absolute'>".
  	  "<div id='fileBrowser'></div>".
      "<img id='logo' src='img/hmat_logo.png'>".
  		$dbScheme->admin().
    "</div>",
  	"Load();"
  );


//print_r($_SESSION);  //debug
//print_r($_POST);


?>