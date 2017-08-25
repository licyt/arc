<?php

namespace MaterialiseCloud;

error_reporting(E_ERROR);
session_start();

require_once 'database.php';

require_once("materialise-cloud-api-php/ExportFormats.php");
require_once("materialise-cloud-api-php/MeasurementUnits.php");
require_once("materialise-cloud-api-php/Axes.php");
require_once("materialise-cloud-api-php/ApiHttpRequest.php");
require_once("materialise-cloud-api-php/ApiException.php");
require_once("materialise-cloud-api-php/AccessApiClient.php");
require_once("materialise-cloud-api-php/OperationFileApi.php");
require_once("materialise-cloud-api-php/OperationApiClient.php");

require_once("materialise-cloud-api-php/AnalyzeOperationApiClient.php");
require_once("materialise-cloud-api-php/WallThicknessAnalysisOperationApiClient.php");

require_once("materialise-cloud-api-php/RepairOperationApiClient.php");
require_once("materialise-cloud-api-php/GeometricalRepairOperationApiClient.php");
require_once("materialise-cloud-api-php/ShrinkwrapRepairAccuracies.php");
require_once("materialise-cloud-api-php/ShrinkwrapRepairOperationApiClient.php");

require_once("materialise-cloud-api-php/ScaleOperationApiClient.php");
require_once("materialise-cloud-api-php/GenerateThumbnailOperationApiClient.php");
require_once("materialise-cloud-api-php/ReduceTrianglesOperationApiClient.php");
require_once("materialise-cloud-api-php/HollowingOperationApiClient.php");

require_once("materialise-cloud-api-php/ImportOperationApiClient.php");
require_once("materialise-cloud-api-php/ExportOperationApiClient.php");
require_once("materialise-cloud-api-php/TokenProviderInterface.php");
require_once("materialise-cloud-api-php/TokenProvider.php");

// setup connection to Materialise Cloud
$apiId = "licyt1"; //your api id
$apiSecret = "5789cc87-5080-4b0d-b16e-8ddd45f6812f"; //your api secret
$userEmail = "hmat@hmat.sk"; //your user email
$userPassword = "Mindfold1"; //your user password
$host = "api-cloudtoolkit-sandbox.materialise.net"; // sandbox
//$host = "api.cloud.materialise.com"; // for production environment

// initialise Materialise Cloud Communication Objects
$tokenProvider = new TokenProvider(new AccessApiClient($host), $apiId, $apiSecret, $userEmail, $userPassword);
$filesClient = new OperationFileApi($host, $tokenProvider);
$importer = new ImportOperationApiClient($host, $tokenProvider);
$repairer = new RepairOperationApiClient($host, $tokenProvider);
$importer = new ImportOperationApiClient($host, $tokenProvider);
$previewer = new GenerateThumbnailOperationApiClient($host, $tokenProvider);

// database table Part contains 3d-designs for additive manufacturing
$tablePart = $dbScheme->getTableByName("Part");


// https://api.cloud.materialise.com/Help/Api/POST-web-api-operation-file
function McUploadPart($part) {
  echo "Uploading part ".$part["idPart"]." ".$part["partName"]."<br>";
  $statusPartUploaded = getStatusId("Part", "Uploaded");
  $statusPartUploadError = getStatusId("Part", "Upload Error");
  global $RepositoryDir;
  global $filesClient;
  global $tablePart;
  if ($response = $filesClient->UploadFile($RepositoryDir.$part["PartFileName"])) {
    // success response
    if ($response->fileId) {
      $part["PartMcFileId"] = $response->fileId;
      $part["PartMcCreationTime"] = substr($response->creationDate, 0, 10)." ".substr($response->creationDate, 11, 8);
      $part["PartMcFileUrl"] = $response->fileUrl;
      // save data from response
      $tablePart->update($part);
      // update part status
      updateStatus("Part", $part["idPart"], $statusPartUploaded);
      echo "Part ".$part["idPart"]." Uploaded OK.<br>";
    }
    // error response
    if ($response->errors) {
      $part["PartMcError"] = $response->errors;
      $tablePart->update($part);
      updateStatus("Part", $part["idPart"], $statusPartUploadError);
      echo "Part ".$part["idPart"]." Upload Error.<br>";
    }
  } else {
    // no response
    $part["PartMcError"] = "No Response from Materialise Cloud";
    $tablePart->update($part);
    updateStatus("Part", $part["idPart"], $statusPartUploadError);
    echo "Part ".$part["idPart"]." Upload Error.<br>";
  }
}

// upload all new parts to materialise cloud
function McUploadAll() {
  echo "Upload of All New Parts:<br>";
  global $tablePart;
  $tablePart->dropFilter();
  $tablePart->addToFilter("StatusName", "New");
  $tablePart->iterate('MaterialiseCloud\McUploadPart');
  echo "End of Upload<br>";
}

// https://api.cloud.materialise.com/Help/Api/POST-web-api-operation-import
// https://api.cloud.materialise.com/Help/Api/GET-web-api-operation-operationId-hollowing-result
function McImportPart($part) {
  echo "Importing part ".$part["idPart"]." ".$part["partName"].":<br>";
  $statusPartImported = getStatusId("Part", "Imported");
  $statusPartImportRequestError = getStatusId("Part", "Import Request Error");
  $statusPartImportResultError = getStatusId("Part", "Import Result Error");
  global $importer;
  global $tablePart;
  if ($response = $importer->Import($part["PartMcFileId"], MeasurementUnits::Mm)) {
    // successful request import for a part
    if ($response->operationId) {
      $importer->WaitForOperationToFinish($response->operationId);
      $result = $importer->GetImportOperationResult($response->operationId);
      // successful GET web-api/operation/{operationId}/import/result
      if ($result->resultId) {
        $part["PartMcImportResultId"] = $result->resultId;
        // save resultId
        $tablePart->update($part);
        // update part status
        updateStatus("Part", $part["idPart"], $statusPartImported);
        echo "Part ".$part["idPart"]." Imported OK.<br>";
      }
      // error response for import results
      if ($response->errors) {
        $part["PartMcError"] = $response->errors;
        $table->update($part);
        updateStatus("Part", $part["idPart"], $statusPartImportRequestError);
        echo "Part ".$part["idPart"]." Import Error.<br>";
      }
    }
    // error response for import request
    if ($response->errors) {
      $part["PartMcError"] = $response->errors;
      $table->update($part);
      updateStatus("Part", $part["idPart"], $statusPartImportResultError);
      echo "Part ".$part["idPart"]." Import Error.<br>";
    }
  } else {
    // no response
    $part["PartMcError"] = "No Response from Materialise Cloud";
    $tablePart->update($part);
    updateStatus("Part", $part["idPart"], $statusPartImportRequestError);
  }
}

// import all uploaded parts
function McImportAll() {
  echo "Import of All Uploaded Parts:<br>";
  global $tablePart;
  $tablePart->dropFilter();
  $tablePart->addToFilter("StatusName", "Uploaded");
  $tablePart->iterate('MaterialiseCloud\McImportPart');
  echo "End of Import<br>";
}

// https://api.cloud.materialise.com/Help/Api/POST-web-api-operation-analyze
// https://api.cloud.materialise.com/Help/Api/GET-web-api-operation-operationId-analyze-result
function McAnalyzePart($part) {
  echo "Analyzing part ".$part["idPart"]." ".$part["partName"].":<br>";
  $statusPartAnalyzeed = getStatusId("Part", "Analyzed");
  $statusPartAnalyzeRequestError = getStatusId("Part", "Analyze Request Error");
  $statusPartAnalyzeResultError = getStatusId("Part", "Analyze Result Error");
  global $analyzer;
  global $tablePart;
  if ($response = $analyzer->Analyze($part["PartMcImportResultId"])) {
    // successful request Analyze for a part
    if ($response->operationId) {
      $analyzer->WaitForOperationToFinish($response->operationId);
      $result = $analyzer->GetAnalyzeOperationResult($response->operationId);
      // successful GET web-api/operation/{operationId}/Analyze/result
      if ($result->operationId) {
        $part["PartMcAnalyzeOperationId"] = $result->operationId;
        $part["PartMcVertices"] =  $result->vertices;
        $part["PartMcTriangles"] =  $result->triangles;
        $part["PartMcContours"] =  $result->contours;
        $part["PartMcShells"] =  $result->shells;
        $part["PartMcVolumeMm3"] =  $result->volumeMm3;
        $part["PartMcSurfaceAreaMm2"] =  $result->surfaceAreaMm2;
        $part["PartMcDimensionXMm"] =  $result->dimensionXMm;
        $part["PartMcDimensionYMm"] =  $result->dimensionYMm;
        $part["PartMcDimensionZMm"] =  $result->dimensionZMm;
        $part["PartMcBadEdges"] =  $result->badEdges;
        $part["PartMcBadContours"] =  $result->badContours;
        // Solidity Parameters
        $part["PartMcSpInvertedNormals"] =  $result->solidityParams->invertedNormals;
          //$part["PartMcSpBadEdges"] =  $result->solidityParams->badEdges;
          //$part["PartMcSpBadContours"] =  $result->solidityParams->badContours;
        $part["PartMcSpNearBadEdges"] =  $result->solidityParams->nearBadEdges;
        $part["PartMcSpPlanarHoles"] =  $result->solidityParams->planarHoles;
        // Quality Parameters
        $part["PartMcQpNoiseShells"] =  $result->qualityParams->noiseShells;
        $part["PartMcQpOverlappingTriangles"] =  $result->qualityParams->overlappingTriangles;
        $part["PartMcQpIntersectingTriangles"] =  $result->qualityParams->intersectingTriangles;
        $part["PartMcQpIsFacetingScoreOk"] =  $result->qualityParams->isFacetingScoreOk;
        // save result
        $tablePart->update($part);
        // update part status
        updateStatus("Part", $part["idPart"], $statusPartAnalyzeed);
      }
      // error response for Analyze results
      if ($response->errors) {
        $part["PartMcError"] = $response->errors;
        $table->update($part);
        updateStatus("Part", $part["idPart"], $statusPartAnalyzeRequestError);
      }
    }
    // error response for Analyze request
    if ($response->errors) {
      $part["PartMcError"] = $response->errors;
      $table->update($part);
      updateStatus("Part", $part["idPart"], $statusPartAnalyzeResultError);
    }
  } else {
    // no response
    $part["PartMcError"] = "No Response from Materialise Cloud";
    $tablePart->update($part);
    updateStatus("Part", $part["idPart"], $statusPartAnalyzeRequestError);
  }
}

// analyze all imported parts
function McAnalyzeAll() {
  echo "Analyze All Imported Parts:<br>";
  global $tablePart;
  $tablePart->dropFilter();
  $tablePart->addToFilter("StatusName", "Imported");
  $tablePart->iterate('MaterialiseCloud\McAnalyzePart');
  echo "End of Analyze<br>";
}

// https://api.cloud.materialise.com/Help/Api/POST-web-api-operation-repair
// https://api.cloud.materialise.com/Help/Api/GET-web-api-operation-operationId-repair-result
function McRepairPart($part) {
  if (($part["PartMcBadEdges"] == 0) && ($part["PartMcBadContours"] == 0) && ($part["PartMcVolumeMm3"] > 0)) {
    // this part does not need repairs
    return;
  }
  echo "Repairing part ".$part["idPart"]." ".$part["partName"].":<br>";
  $statusPartRepaired = getStatusId("Part", "Repaired");
  $statusPartRepairRequestError = getStatusId("Part", "Repair Request Error");
  $statusPartRepairResultError = getStatusId("Part", "Repair Result Error");
  global $repairer;
  global $tablePart;
  if ($response = $repairer->Repair($part["PartMcImportResultId"])) {
    // successful request import for a part
    if ($response->operationId) {
      $repairer->WaitForOperationToFinish($response->operationId);
      $result = $repairer->GetRepairOperationResult($response->operationId);
      // successful GET web-api/operation/{operationId}/import/result
      if ($result->resultId) {
        $part["PartMcRepairResultId"] = $result->resultId;
        // save resultId
        $tablePart->update($part);
        // update part status
        updateStatus("Part", $part["idPart"], $statusPartRepaired);
        echo "Part ".$part["idPart"]." Repaired OK.<br>";
      }
      // error response for repair results
      if ($response->errors) {
        $part["PartMcError"] = $response->errors;
        $table->update($part);
        updateStatus("Part", $part["idPart"], $statusPartRepairRequestError);
        echo "Part ".$part["idPart"]." Repair Error.<br>";
      }
    }
    // error response for repair request
    if ($response->errors) {
      $part["PartMcError"] = $response->errors;
      $table->update($part);
      updateStatus("Part", $part["idPart"], $statusPartRepairResultError);
      echo "Part ".$part["idPart"]." Repair Error.<br>";
    }
  } else {
    // no response
    $part["PartMcError"] = "No Response from Materialise Cloud";
    $tablePart->update($part);
    updateStatus("Part", $part["idPart"], $statusPartRepairRequestError);
  }
}

// repair all faulty parts
function McRepairAll() {
  echo "Repair All Faulty Parts:<br>";
  global $tablePart;
  $tablePart->dropFilter();
  $tablePart->addToFilter("StatusName", "Analyzed");
  $tablePart->iterate('MaterialiseCloud\McRepairPart');
  echo "End of Repair<br>";
}

// https://api.cloud.materialise.com/Help/Api/POST-web-api-operation-thumbnail
// https://api.cloud.materialise.com/Help/Api/GET-web-api-operation-operationId-thumbnail-result
function McPreviewPart($part) {
  if (!$part["PartMcImportResultId"] || $part["PartMcPreviewFileId"]) return;
  echo "Previewing part ".$part["idPart"]." ".$part["partName"].":<br>";
  $statusPartPreviewRequestError = getStatusId("Part", "Preview Request Error");
  $statusPartPreviewResultError = getStatusId("Part", "Preview Result Error");
  global $previewer;
  global $tablePart;
  global $filesClient;
  global $RepositoryDir;
  global $imageDir;
  if ($response = $previewer->GenerateThumbnail($part["PartMcImportResultId"], 300, 300, 145, 145, 145)) {
    // successful request import for a part
    if ($response->operationId) {
      $previewer->WaitForOperationToFinish($response->operationId);
      $result = $previewer->GetGenerateThumbnailOperationResult($response->operationId);
      // successful GET web-api/operation/{operationId}/import/result
      if ($result->fileId) {
        $fileName = $part["idPart"].$part["PartName"].".jpg";
        $part["PartMcPreviewFileId"] = $result->fileId;
        $part["PartThumb"] = $imageDir."Part/".$fileName;
        $filesClient->DownloadFile($result->fileId, $RepositoryDir.$part["PartThumb"]);
        // save result
        $tablePart->update($part);
        echo "Part ".$part["idPart"]." Preview generated OK.<br>";
      }
      // error response for repair results
      if ($response->errors) {
        $part["PartMcError"] = $response->errors;
        $table->update($part);
        updateStatus("Part", $part["idPart"], $statusPartPreviewRequestError);
        echo "Part ".$part["idPart"]." Preview Error.<br>";
      }
    }
    // error response for repair request
    if ($response->errors) {
      $part["PartMcError"] = $response->errors;
      $table->update($part);
      updateStatus("Part", $part["idPart"], $statusPartPreviewResultError);
      echo "Part ".$part["idPart"]." Preview Error.<br>";
    }
  } else {
    // no response
    $part["PartMcError"] = "No Response from Materialise Cloud";
    $tablePart->update($part);
    updateStatus("Part", $part["idPart"], $statusPartPreviewRequestError);
  }
}

// generate preview for all imported parts
function McPreviewAll() {
  echo "Generate Preview to all imported Parts:<br>";
  global $tablePart;
  $tablePart->dropFilter();
  //$tablePart->addToFilter("StatusName", "Imported");
  $tablePart->iterate('MaterialiseCloud\McPreviewPart');
  echo "End of Preview<br>";
}

// search parts in different states and process them on Materialise Cloud accordingly
function McLoop() {
  // one loop
  echo "Materialise Cloud Processing Loop ".$i.":<br>";
  McUploadAll();
  McImportAll();
  McPreviewAll();
  McAnalyzeAll();
  McRepairAll();
}

// main program
McLoop();

?>
