<?php

require 'gantt.php';

date_default_timezone_set("Europe/Bratislava");

$sG = new statusGantt();
$sG->statusType = "Project";
//$sG->statusLogRowId = 1;
$sG->iFrom = strtotime("2016-05-01");
$sG->iTill = strtotime("2016-07-01");
$sG->iWidth = 800;
$sG->loadLanes();

$slider = new cSlider();

echo
  head(
    linkCss("css/gantt.css").
    linkCss("css/jquery.nstSlider.min.css").
  	linkJs("js/basic.js").
  	linkJs("js/ajax.js").
  	linkJs("//code.jquery.com/jquery-1.11.0.min.js").
  	linkJs("js/jquery.nstSlider.min.js")
  ).
  body(
  	$sG->display(true, true).
    $slider->display().
  	$slider->setup()
	);

?>