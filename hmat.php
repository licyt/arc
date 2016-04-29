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
	linkCss("css/datepicker.css").
  	linkJs("js/jscolor.js").
	linkJs("js/datepicker.js")
  ).
  body(
  		"<img src='img/hmat_logo.png'>".
  		"<div>".$dbScheme->allDetailForms()."</div>"
  );

echo "the end";

//print_r($_SESSION);  //debug
//print_r($_POST);


?>