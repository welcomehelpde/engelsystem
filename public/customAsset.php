<?php

$assetsPath = __DIR__ . '/../customization/assets/';
$assetFiles = scandir($assetsPath);
$assetFiles = array_slice($assetFiles, 2); // remove `.` and `..`

if(isset($_GET['file']) && in_array($_GET['file'], $assetFiles)) {
  $assetIdx = array_search($_GET['file'], $assetFiles);
  $filePath = $assetsPath . $assetFiles[$assetIdx];

  header('Content-Type:' . mime_content_type($filePath));
  readfile($filePath);
}
else {
  header('HTTP/1.0 404 Not Found');
  die('File not found.');
}
