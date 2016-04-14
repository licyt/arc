<?php

function GUI($element, $attribute, $default='') {
  $query = "SELECT GUIvalue FROM GUI ".
    "WHERE GUIelement=\"$element\" AND GUIattribute=\"$attribute\"";
  if ($dbResult =  mysql_query($query)) {
    if ($row = mysql_fetch_assoc($dbResult)) {
      $result = $row[GUIvalue];
  	}
  }
  return ($result?$result:$default);
}

?>