<?php

function loadGUI() {
  // load table GUI into global memory array
  $query = "SELECT idGUI, GUIElement, GUIAttribute, GUIValue FROM GUI";
  if ($dbResult =  myQuery($query)) {
	while ($dbRow = mysql_fetch_assoc($dbResult)) {
	  if (!$GLOBALS[GUI][$dbRow[GUIElement]][$dbRow[GUIAttribute]])
	    $GLOBALS[GUI][$dbRow[GUIElement]][$dbRow[GUIAttribute]] = array();
	  array_push($GLOBALS[GUI][$dbRow[GUIElement]][$dbRow[GUIAttribute]], $dbRow[GUIValue]);
	}
  }
}

function gui($element, $attribute, $default='', $i=0) {
  return 
    (isset($GLOBALS[GUI][$element][$attribute])
      ? $GLOBALS[GUI][$element][$attribute][$i]
      : false //ugi($element, $attribute, $default)
    );
}

function ugi($element, $attribute, $value, $forceAppend=false) {
  if (!isset($element) || !isset($attribute) || !isset($value) || ($value=="")) return false;
  
  if (isset($GLOBALS[GUI][$element][$attribute]) && !$forceAppend) {
    if ($GLOBALS[GUI][$element][$attribute]!==$value) {
      $query =
        "UPDATE GUI ".
        "SET GUIValue = '$value' ".
        "WHERE (GUIElement = '$element') ".
        "AND (GUIAttribute = '$attribute')";
      myQuery($query);
    }
  } else {
    $query =
      "INSERT INTO GUI SET ".
      "GUIElement = '$element', ".
      "GUIAttribute = '$attribute', ".
      "GUIValue = '$value'";
    myQuery($query);
  }
  $GLOBALS[GUI][$element][$attribute] = $value;
  return $value;
}

function iug($value, $attribute, $default='') {
  foreach ($GLOBALS[GUI] as $name=>$element) {
  	if (is_array($element[$attribute]) && in_array($value, $element[$attribute])) return $name;
  }
  return $default;
}


?>