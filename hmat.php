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
  	linkCss("css/person.css").
  	linkCss("css/printparameters.css").
  	linkCss("css/part.css").
  	linkCss("css/platform_has_part.css").
  	linkCss("css/platform.css").
  	linkCss("css/deliverytransport.css").
  	linkCss("css/gui.css").
  	linkCss("css/statuslog.css").
	linkCss("css/datepicker.css").
  	
  	linkJs("js/hmat.js").
  	linkJs("js/ajax.js").
  	
  	linkJs("js/jscolor.js").
  	linkJs("js/datepicker.js")
  ).
  body( 
	"<div style='position:absolute'>".
  	  "<div id='fileBrowser'></div>".
      "<img class='logo' src='img/hmat_logo.png'>".
	  $dbScheme->admin().
    "</div>",
  	"onLoad();"
  );


//print_r($_SESSION);  //debug
//print_r($_POST);


?>