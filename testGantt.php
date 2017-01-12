<?php

require 'gantt.php';

$sG = new statusGantt();
$sG->statusType = "Project";
//$sG->statusLogRowId = 1;
$sG->iFrom = "2016-04-01";
$sG->iTill = "2016-11-05";
$sG->iWidth = 1300;
$sG->loadLanes();


echo
  head(
    linkCss("css/gantt.css").
    linkCss("css/classic.css").
  	linkJs("js/basic.js").
  	linkJs("js/gantt.js").
  	linkJs("//code.jquery.com/jquery-1.11.0.min.js").
    linkJs("//code.jquery.com/ui/1.10.3/jquery-ui.min.js").
    linkJs("js/jquery.mousewheel.min.js").
    linkJs("js/jQDateRangeSlider-withRuler-min.js")
  ).
  body(
  	"<div id=ganttContainer>".$sG->display()."</div>".
    "<div style=\"padding:30px;\"><div id=\"dateSlider\"></div></div>".
    "<script>".file_get_contents("js/dateSlider.js")."</script>"
  );

?>