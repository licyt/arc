<?php

function loadGUI() {
  // load table GUI into global memory array
  $query = "SELECT idGUI, GUIElement, GUIAttribute, GUIValue FROM GUI";
  if ($dbResult =  myQuery($query)) {
	while ($dbRow = mysql_fetch_assoc($dbResult)) {
	  $GLOBALS[GUI][$dbRow[GUIElement]][$dbRow[GUIAttribute]] = $dbRow[GUIValue];
	}
  }
}

function gui($element, $attribute, $default='') {
  return (isset($GLOBALS[GUI][$element][$attribute])?$GLOBALS[GUI][$element][$attribute]:$default);
}

function iug($value, $attribute, $default='') {
  foreach ($GLOBALS[GUI] as $name=>$element) {
  	if ($element[$attribute]==$value) return $name;
  }
  return $default;
}


?>