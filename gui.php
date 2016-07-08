<?php

function gui($element, $attribute, $default='') {
  $query = "SELECT GUIvalue FROM GUI ".
    "WHERE GUIelement=\"$element\" AND GUIattribute=\"$attribute\"";
  if ($dbResult =  mysql_query($query)) {
    if ($row = mysql_fetch_assoc($dbResult)) {
      $result = $row[GUIvalue];
  	}
  }
  return ($result?$result:$default);
}

function iug($value, $attribute, $default='') {
  $query = 
    "SELECT GUIelement FROM GUI ".
	"WHERE GUIvalue=\"$value\" AND GUIattribute=\"$attribute\"";
  if ($dbResult =  mysql_query($query)) {
	if ($row = mysql_fetch_assoc($dbResult)) {
	  $result = $row[GUIelement];
	}
  }
  return ($result?$result:$default);
}

?>