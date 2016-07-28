<?php

require_once 'html.php';
require_once 'database.php';

class statusGantt {
	public $iFrom;
	public $iTill;
  public $iWidth=300; /* pixels */
  public $labelWidth=100;
  public $statusType;
  public $statusLogRowId;
  public $lanes = array();
  
  public function loadLanes() {
    // interval duration in seconds
    $iDuration = strtotime($this->iTill)-strtotime($this->iFrom);
    $iRatio = ($this->iWidth)/$iDuration; 				
    $query=
      "SELECT StatusType, StatusLogRowId, StatusName, StatusColor, StatusLogTimeStamp ".
      "FROM StatusLog ".
      "INNER JOIN Status ON StatusLog_idStatus=idStatus ".
      "WHERE (1=1) ".
        ($this->iFrom ? 
        		"AND (StatusLogTimeStamp>='$this->iFrom') " :"").
        ($this->iTill ? 
        		"AND (StatusLogTimeStamp<'$this->iTill') " :"").
        ($this->statusType ? 
        		"AND (StatusType='".$this->statusType."') " : "").
        ($this->statusLogRowId ? 
        		"AND (statusLogRowId=".$this->statusLogRowId.") " : "").
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
  	  
  	    $left = round($iRatio * ($startTime-strtotime($this->iFrom)));
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
  
  public function display($useLabels=false) {
  	$gantt = new cHtmlDiv();
  	$gantt->setAttribute("CLASS", "gantt");
  	/*
  	$gantt->setAttribute("STYLE", 
  		    "width: ".($this->iWidth+($useLabels ? $this->labelWidth : 0)).";");
    */
  	foreach ($this->lanes as $StatusType=>$subLanes) {
  	  if (!$StatusType) continue;
  	  foreach ($subLanes as $StatusLogRowId=>$bars) {
  	  	if (!$StatusLogRowId) continue;
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
?>