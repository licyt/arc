<?php

session_start();

require_once("database.php");

echo head(linkCss("hmat.css")).body($dbScheme->allDetailForms());

echo "the end";

//print_r($_SESSION);  //debug
//print_r($_POST);

?>