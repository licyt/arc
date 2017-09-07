<?php

require_once 'database.php';

function printQuote($quoteId) {
  global $dbScheme;
  // load template 
  $template = file_get_contents("./templates/printQuote.html");
  // gather data
  $dbQuote = $dbScheme->getTableByName("Quote");
  $quote = $dbQuote->getCurrentRecord($quoteId);
  // parent project
  $dbProject = $dbScheme->getTableByName("Project");
  $project = $dbProject->getCurrentRecord(getParentId("Quote", $quoteId, "Project"));
  // contact person
  $dbPerson = $dbScheme->getTableByName("Person");
  $person = $dbPerson->getCurrentRecord(getParentId("Project", $project[idProject], "Person"));
  $quote["PersonName"] = $person["PersonName"];
  $quote["PersonSurname"] = $person["PersonSurname"];
  // dataset 
  $dbDataSet = $dbScheme->getTableByName("DataSet");
  $dataSet = $dbDataSet->getCurrentRecord(getFirstChildId("DataSet", "Quote", $quoteId));
  // parts
  $dbPart = $dbScheme->getTableByName("Part");
  $quote["Parts"] = getChildren("Part", "Dataset", $dataSet[idDataSet]);
  // calculate totals for parts
  foreach ($quote["Parts"] as $index=>$part) {
    $quote["Parts"][$index]["PartTotal"] = $part["PartPrice"]*$part["PartQuantity"];
  }
  // mustache data
  $m = new Mustache_Engine;
  return $m->render($template, $quote);
}



if ($_REQUEST[idQuote]>0) echo printQuote($_REQUEST[idQuote]);

?>