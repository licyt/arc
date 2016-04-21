<?php

session_start();

require_once("database.php");

echo 
  head(
    charset().
	linkCss("hmat.css").
	linkCss("hideIdTags.css").
  	linkCss("tabHeadAdminInput.css").
  	linkCss("tabHeadAdminSpan.css").
  	linkCss("jsDatePick_ltr.min.css").
  	linkJs("jsDatePick.min.1.3.js").
  	linkJs("jscolor.js").
    linkJs("global.js")  		 
  ).
  body(
    $dbScheme->allDetailForms()
  );

echo "the end";

//print_r($_SESSION);  //debug
//print_r($_POST);


?>