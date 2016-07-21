<?php

require_once 'html.php';
require_once 'database.php';

function statusGantt($iFrom, $iTill, $iWidth=1000 /* pixels */) {
  // interval duration in seconds
  $iDuration = strtotime($iTill)-strtotime($iFrom);
  $iRatio = ($iWidth-200)/$iDuration; 				// -200px lane label width
  
  $query=
	"SELECT StatusType, StatusLogRowId, StatusName, StatusColor, StatusLogTimeStamp ".
	"FROM StatusLog ".
	"INNER JOIN Status ON StatusLog_idStatus=idStatus ".
	"WHERE (StatusLogTimeStamp>'$iFrom') AND (StatusLogTimeStamp<'$iTill') ".
	"ORDER BY StatusType, StatusLogRowId, StatusLogTimeStamp ";
	
  if ($dbRes=myQuery($query)) {
  	
  	$lanes = array();
  	$lastStatus = array();
  	
  	while ($status=mysql_fetch_assoc($dbRes)) {
  	  if (!isset($lastStatus)) {
  	  	foreach ($status as $column=>$value) {
  	  	  $lastStatus[$column]= $value;
  	  	}
  	  	continue;
  	  }
  	  
  	  $startTime = strtotime($lastStatus[StatusLogTimeStamp]);
  	  $endTime = strtotime($status[StatusLogTimeStamp]);
  	  $duration = $endTime - $startTime;
  	  
  	  $left = round($iRatio * ($startTime-strtotime($iFrom)));
  	  $width = round($iRatio * $duration);
  	  
  	  $bar = new cHtmlDiv();
  	  $bar->setAttribute("CLASS", "ganttBar");
  	  $bar->setAttribute("STYLE",
  	  		"left: ".$left."px; ".
  	  		"width: ".max(1,$width)."px; ".
  	  		"background-color: #".$lastStatus[StatusColor].";"
  	  );
  	  //$bar->setAttribute("CONTENT", $lastStatus[StatusName]);
  	  $bar->setAttribute("TITLE", $lastStatus[StatusName]);
  	  
  	  $lanes[$lastStatus[StatusType]][$lastStatus[StatusLogRowId]] .= $bar->display();
  	  
  	  foreach ($status as $column=>$value) {
  	    $lastStatus[$column]= $value;
  	  }
  	}
  	
  	$gantt = new cHtmlDiv();
  	$gantt->setAttribute("STYLE", "width: $iWidth;");
  	foreach ($lanes as $StatusType=>$subLanes) {
  	  foreach ($subLanes as $StatusLogRowId=>$bars) {
  	  	$lane = new cHtmlDiv();
  	  	$lane->setAttribute("CLASS", "ganttLane");
  	  	$lane->setAttribute("CONTENT", $bars);
  	  	$laneLabel = new cHtmlDiv();
  	  	$laneLabel->setAttribute("CLASS", "ganttLaneLabel");
  	  	$q0 = "SELECT ".$StatusType."Name FROM $StatusType WHERE id".$StatusType."=$StatusLogRowId";
  	  	if (($dbRes0 = myQuery($q0)) && ($dbRow0 = mysql_fetch_assoc($dbRes0))) {
  	  	  $name = $dbRow0[$StatusType."Name"];
  	  	  $laneLabel->setAttribute("CONTENT", $StatusType.": ".$name);
  	  	  $content .= $laneLabel->display().$lane->display();
  	  	} 
  	  }
  	}
  	$gantt->setAttribute("CONTENT", $content);
  }
  
  return $gantt->display();
}


echo 
  head(
  	linkCss("css/gantt.css")
  ).
  body(
  	statusGantt("2016-04-01", "2016-06-01")
  );

?>