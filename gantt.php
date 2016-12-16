<?php

require_once 'database.php';

date_default_timezone_set("Europe/Bratislava");

class statusGantt {
  public $iFrom;
  public $iTill;
  public $iWidth=300; /* pixels */
  public $labelWidth=100;
  public $statusType;
  public $statusLogRowId;
  public $lanes = array();
  
  public function emptyLanes() {
    while (count($this->lanes)) array_pop($this->lanes);
  }
  
  public function loadLanes() {
    $this->emptyLanes();
    
    // interval duration in seconds
    if (!($iDuration = strtotime($this->iTill)-strtotime($this->iFrom))) return;
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
  
  public function subLanes($statusType, $statusLogRowId) {
    $subSG = new statusGantt();
    $subSG->iFrom = $this->iFrom;
    $subSG->iTill = $this->iTill;
    $subSG->iWidth = $this->iWidth;
    // for all children of this parent
    $query = 
      "SELECT RelationLObject, RelationLId FROM Relation ".
      "WHERE (RelationRObject='$statusType') AND (RelationRId=$statusLogRowId) ".
      "ORDER BY RelationLObject, RelationLId";
    if ($dbResult = myQuery($query)) {
      while ($child = mysql_fetch_assoc($dbResult)) {
        $dbTable = $GLOBALS[dbScheme]->getTableByName($child[RelationLObject]);
        if ($dbTable->hasStatus()) {
          $subSG->statusType = $child[RelationLObject];
          $subSG->statusLogRowId = $child[RelationLId];
          $subSG->loadLanes();
          $subLanes.=$subSG->displayLanes(); 
        }
      }
    }
    return $subLanes;
  }
  
  public function displayLanes() {
  	foreach ($this->lanes as $StatusType=>$subLanes) {
  	  if (!$StatusType) continue;
  	  foreach ($subLanes as $StatusLogRowId=>$bars) {
  	  	if (!$StatusLogRowId) continue;
  	  	  $q0 = "SELECT ".$StatusType."Name FROM $StatusType WHERE id".$StatusType."=$StatusLogRowId";
  	  	  if (($dbRes0 = myQuery($q0)) && ($dbRow0 = mysql_fetch_assoc($dbRes0))) {
  	  	    $laneLabel = $dbRow0[$StatusType."Name"];
  	  	  } else {
  	  	  	$laneLabel = "";
  	  	  }
  	  	  $lanes .= 
    	      "<TR id=\"".$StatusType.$StatusLogRowId."\">".
  	  	      "<TD CLASS=\"gantt\" STYLE=\"width:200px;\">"."<DIV CLASS=\"ganttLaneLabel\">".$StatusType."</DIV>"."</TD>".
    	        "<TD CLASS=\"gantt\" STYLE=\"width:200px;\">"."<DIV CLASS=\"ganttLaneLabel\">".$laneLabel."</DIV>"."</TD>".
  	  	      "<TD CLASS=\"gantt\">"."<DIV CLASS=\"ganttLane\">".$bars."</DIV>"."</TD>".
  	        "</TR>".
  	  	    $this->subLanes($StatusType, $StatusLogRowId);
  	  }
  	}
  	return $lanes;
  }

  public function display() {
    return "<TABLE CLASS=\"gantt\">".$this->displayLanes()."</TABLE>";
  }
}

?>