<?php

session_start();

require_once("database.php");
require_once("status.php");


echo 
  head(
    charset().
	linkCss("hmat.css")
  ).
  body(
    $dbScheme->allDetailForms()
  );

echo "the end";

//print_r($_SESSION);  //debug
//print_r($_POST);

?>