<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 
  $dbServerName = 'tokamag.nasa.lan';
  
  $databases    = file("./databases.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if ($_REQUEST[dbName]) {
    $_SESSION[dbName]=$_REQUEST[dbName];
  }
  $dbName       = ($_SESSION[dbName]?$_SESSION[dbName]:$databases[0]);
  $_SESSION[dbName] = $dbName;

  $dbUser       = 'root';
  $dbPassword   = 'mindfold';

  $RepositoryPath = "/datafiles/"; // dependency! ajax.js.updatePath()
  
  $lang 		= 'ENG';  
?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       