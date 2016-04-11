<?php
//
// This script uses cCssHideIdTags (creates .css file that implements hiding of ID and LABEL Tags)
//

include_once( "./cssHideIdTags.php" );

$cssFilePath = "";
$cssFileName = "hidetags.css";

$css = new cCssHideIdTags( $dbServerName, $dbUser, $dbPassword, $dbName , $cssFilePath , $cssFileName );
$css->createCssFile();
?>