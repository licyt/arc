<?php

require_once 'html.php';
require_once 'database.php';

class statusGantt {
  public $iWidth=800; /* pixels */
  public $labelWidth=200;
  public $statusType;
  public $statusLogRowId;
  public $lanes = array();
  
  public function interval($iFrom, $iTill) {
    // interval duration in seconds
    $iDuration = strtotime($iTill)-strtotime($iFrom);
    $iRatio = ($this->iWidth)/$iDuration; 				
    echo $query=
	  "SELECT StatusType, StatusLogRowId, StatusName, StatusColor, StatusLogTimeStamp ".
	  "FROM StatusLog ".
	  "INNER JOIN Status ON StatusLog_idStatus=idStatus ".
	  "WHERE (StatusLogTimeStamp>='$iFrom') AND (StatusLogTimeStamp<'$iTill') ".
	  ($this->statusType ? "AND (StatusType='".$this->statusType."') " : "").
	  ($this->statusLogRowId ? "AND (statusLogRowId=".$this->statusLogRowId.") " : "").
	  "ORDER BY StatusType, StatusLogRowId, StatusLogTimeStamp ";
	
    if ($dbRes=myQuery($query)) {
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
  	    $bar->setAttribute("TITLE", $lastStatus[StatusLogTimeStamp]."->".$lastStatus[StatusName]."->".$status[StatusLogTimeStamp]);
  	  
  	    $this->lanes[$lastStatus[StatusType]][$lastStatus[StatusLogRowId]] .= $bar->display();
  	  
  	    foreach ($status as $column=>$value) {
  	      $lastStatus[$column]= $value;
  	    }
  	  }
    }
  }
  
  public function display($useLabels=true) {
  	$gantt = new cHtmlDiv();
  	$gantt->setAttribute("STYLE", "width: ".($this->iWidth+$this->labelWidth).";");
  	foreach ($this->lanes as $StatusType=>$subLanes) {
  	  foreach ($subLanes as $StatusLogRowId=>$bars) {
  	  	$lane = new cHtmlDiv();
  	  	$lane->setAttribute("CLASS", "ganttLane");
  	  	$lane->setAttribute("CONTENT", $bars);
  	  	if ($useLabels) {
  	  	  $laneLabel = new cHtmlDiv();
  	  	  $laneLabel->setAttribute("CLASS", "ganttLaneLabel");
  	  	  $q0 = "SELECT ".$StatusType."Name FROM $StatusType WHERE id".$StatusType."=$StatusLogRowId";
  	  	  if (($dbRes0 = myQuery($q0)) && ($dbRow0 = mysql_fetch_assoc($dbRes0))) {
  	  	    $name = $dbRow0[$StatusType."Name"];
  	  	    $laneLabel->setAttribute("CONTENT", $StatusType.": ".$name);
  	  	    $content .= $laneLabel->display();
  	  	  }
  	  	}
  	  	$content .= $lane->display();
  	  }
  	}
  	$gantt->setAttribute("CONTENT", $content);
    return $gantt->display();
  }
}

$sG = new statusGantt();
$sG->statusType = "Project";
$sG->statusLogRowId = 1;
$sG->interval("2016-04-14", "2016-07-21");

echo 
  head(linkCss("css/gantt.css")).
  body($sG->display(false));

?>