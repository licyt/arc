<?php

session_start();

require_once("database.php");

echo 
  head(
    charset().
	linkCss("css/hmat.css").
	linkCss("css/hideIdTags.css").
  	linkCss("css/tabHeadAdminInput.css").
  	linkCss("css/tabHeadAdminSpan.css").
  	linkCss("css/switchBrowserInput.css").
  	linkCss("css/switchBrowserSpan.css").
  	linkCss("css/switchBrowseForm.css").
	linkCss("css/jquery-ui.css").
  	linkCss("css/jquery-ui-timepicker-addon.css").
  	linkCss("css/spectrum.css").
	linkJs("jquery-1.12.3.min.js").
	linkJs("jquery-ui.js").
  	linkJs("jscolor.js").
  	linkJs("spectrum.js").
  	linkJs("jquery-ui-sliderAccess.js").
  	linkJs("jquery-ui-timepicker-addon.js").
    linkJs("global.js")  		 
  ).
  body(
    $dbScheme->allDetailForms()
  );

echo "the end";

//print_r($_SESSION);  //debug
//print_r($_POST);


?>