<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 
  $dbServerName = 'localhost'; //tokamag.nasa.lan';
  $dbUser       = 'root';
  $dbPassword   = 'mindfold';
  
  
  // ------------------------------------------------------------------------------------- Create New Database
  if (isset($_POST[createDb])) {
    $dbName = $_POST[newDbName];
    // Create connection
    $conn = mysqli_connect($dbServerName, $dbUser, $dbPassword);
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    } 
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName DEFAULT CHARACTER SET utf8 COLLATE utf8_slovak_ci";
    if (mysqli_query($conn, $sql)) {
      mysqli_select_db($conn, $dbName);
      runSqlScript($conn, "./sql/systemTables.sql");
      runSqlScript($conn, "./sql/systemGui.sql");
    } else {
      die("Error creating database: " . mysqli_error($conn));
    }
    mysqli_close($conn); 
    // add new database to list of databases 
    $file = fopen("./databases.txt", "ab");
    fwrite($file, $dbName."\n");
    fclose($file);
  } else {
    $databases    = file("./databases.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($_REQUEST[dbName]) {
      $_SESSION[dbName]=$_REQUEST[dbName];
    }
    $dbName = ($_SESSION[dbName]?$_SESSION[dbName]:$databases[0]);
  }
  $_SESSION[dbName] = $dbName;
  
  

  $SCRIPT_DIRECTORY = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME)."/";
  $RepositoryDir = $SCRIPT_DIRECTORY."datafiles/"; // dependency! ajax.js.updatePath();
  
  $lang 		= 'ENG';  
?>