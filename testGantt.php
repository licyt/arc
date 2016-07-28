<?php

require 'gantt.php';

$sG = new statusGantt();
$sG->statusType = "Project";
$sG->statusLogRowId = 1;
$sG->iFrom = "2016-04-14";
$sG->iTill = "2016-07-21";
$sG->loadLanes();

echo
head(linkCss("css/gantt.css")).
body($sG->display());

?>