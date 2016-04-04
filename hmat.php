<?php

require_once("html.php");
require_once("database.php");
aaa
$table = new cDbTable;
$query = "SHOW TABLES";
if ($result = mysql_query($query)) {
  while ($row = mysql_fetch_array($result)) {
    $tableName=$row["Tables_in_$dbName"];
    $table->setName($tableName);
    $page .= 
      $tableName.br().
      $table->printFields().br();
  }
}


echo head(linkCss("hmat.css")).body($page);

echo "the end";
 
?>