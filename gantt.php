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
  
  public function loadLanes() {
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
  
  public function display($useLabels=false, $useDiv=false) {
  	foreach ($this->lanes as $StatusType=>$subLanes) {
  	  if (!$StatusType) continue;
  	  foreach ($subLanes as $StatusLogRowId=>$bars) {
  	  	if (!$StatusLogRowId) continue;
  	  	if ($useLabels) {
  	  	  $q0 = "SELECT ".$StatusType."Name FROM $StatusType WHERE id".$StatusType."=$StatusLogRowId";
  	  	  if (($dbRes0 = myQuery($q0)) && ($dbRow0 = mysql_fetch_assoc($dbRes0))) {
  	  	    $laneLabel = $dbRow0[$StatusType."Name"];
  	  	  } else {
  	  	  	$laneLabel = "";
  	  	  }
  	  	  $lanes .= 
    	      "<TR>".
  	  	      "<TD CLASS=\"gantt\" STYLE=\"width:200px;\">"."<DIV CLASS=\"ganttLaneLabel\">".$laneLabel."</DIV>"."</TD>".
  	  	      "<TD CLASS=\"gantt\">"."<DIV CLASS=\"ganttLane\">".$bars."</DIV>"."</TD>".
  	        "</TR>";
  	    } else {
  	  	  $lanes .= 
    	      "<DIV CLASS=\"ganttLane\">".$bars."</DIV>";
  	    }
  	  }
  	}
    if ($useLabels) {
      $lanes = "<TABLE CLASS=\"gantt\">".$lanes."</TABLE>";
  	} 
  	if ($useDiv) {
  		$lanes = "<DIV ID=\"gantt\">".$lanes."</DIV>";
  	}
  	return $lanes;
  }
}

class cSlider {
	public $min = "2016-04-01";
	public $max = "2016-09-01";
	public $start = "2016-05-01";
	public $end = "2016-07-20";
	
	public function display() {
		$min = strtotime($this->min);
		$max = strtotime($this->max);
		$start = strtotime($this->start);
		$end = strtotime($this->end);
		return 
		  "<div id=\"slider\" style=\"width:1000px;\">".
		    "<div style=\"text-align:right;width:150px;float:right;\" class=\"rightLabel\"></div>".
		    "<DIV style=\"float:right;\" CLASS=\"nstSlider\" ".
		        "data-range_min=\"".$min."\" data-range_max=\"".$max."\" ".
		        "data-cur_min=\"".$start."\" data-cur_max=\"".$end."\">".
		      "<div class=\"highlightPanel\"></div>".
		      "<div class=\"bar\"></div>".
		      "<div class=\"leftGrip\"></div>".
		      "<div class=\"rightGrip\"></div>".
		    "</DIV>".
		    "<div style=\"text-align:left;width:150px;float:right;\" class=\"leftLabel\"></div>".
		  "</div>";
	}
	
	public function setup() {
	  return 
	    "<script>".
	      file_get_contents('setupSlider.js').
	    "</script>";
	}
}

?>