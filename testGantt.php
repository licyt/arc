<?php

require 'gantt.php';

$sG = new statusGantt();
$sG->statusType = "Project";
//$sG->statusLogRowId = 1;
$sG->iFrom = "2016-04-01";
$sG->iTill = "2016-10-01";
$sG->iWidth = 800;
$sG->loadLanes();

$slider = new cSlider();

echo
  head(
    linkCss("css/gantt.css").
    linkCss("css/jquery.nstSlider.min.css").
  	linkJs("js/basic.js").
  	linkJs("js/gantt.js").
  	linkJs("//code.jquery.com/jquery-1.11.0.min.js").
  	linkJs("js/jquery.nstSlider.min.js")
  ).
  body(
  	$sG->display().
    $slider->display().
  	$slider->setup()
  );

?>