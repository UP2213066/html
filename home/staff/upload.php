<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the spreadsheet uploaded containing
    staff and their quota. It provides an easy way to upload new staff and also update
    changes to a staff member automatically.

    © 2025 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
error_reporting(E_ALL); 
ini_set('display_errors', TRUE); 
ini_set('display_startup_errors', TRUE);
include '/var/www/html/validate.php';
require '/var/www/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$targetDir = '/uploads/';
echo 'Uploading file...<br>';
$file = $_FILES['staffBulkUpload'];
$fileExtention = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$uniqueName = bin2hex(uniqid()) . '.' . $fileExtention;
$targetFile = $targetDir . $uniqueName;

if ($file['size'] > 5242880){
  die('File too large.');
}

$allowed = ['xlsx', 'xls', 'csv', 'ods'];
if (!in_array($fileExtention, $allowed)) {
  die('Invalid file type of ' . $fileExtention);
}

if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
  die('Failed to save file.');
}
try {
  $spreadsheet = IOFactory::load($targetFile);
  $sheet = $spreadsheet->getActiveSheet();
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    die('Error reading spreadsheet: ' . $e->getMessage());
}
if ($_POST['fileType'] === 'staffUpload') {
  echo 'Processing staff upload...<br>';
  $connection = new mysqli($hostname, $username, $password, $database);
  $preparedSQL = $connection->prepare("INSERT INTO staff VALUES(?, ?, ?, ?, 0, 0, NULL) ON DUPLICATE KEY UPDATE quota=VALUE(quota), allocatedStudents=VALUE(allocatedStudents), studentsToAvoid=VALUE(studentsToAvoid)");
  $first = true;
  foreach ($sheet->getRowIterator() as $row) {
    if ($first) {
        $first = false;
    } else {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }
        if ($data[0] === NULL || $data[1] === NULL || $data[2] === NULL) {
          break;
        }
        $preparedSQL->bind_param("ssss", $data[0], $data[1], $startPassword, $data[2]);
        $preparedSQL->execute();
        if (!$preparedSQL) {
          echo $connection->error;
        }
        header('location: /home/staff/');
        exit();
      }
  }
} elseif ($_POST['fileType'] === 'quotaUpload') {
  echo 'Processing quota upload...<br>';
  $connection = new mysqli($hostname, $username, $password, $database);
  $preparedSQL = $connection->prepare("UPDATE staff SET quota=? WHERE name=? and email=?");
  $first = true;
  foreach ($sheet->getRowIterator() as $row) {
    if ($first) {
        $first = false;
    } else {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }
        if ($data[0] === NULL || $data[1] === NULL || $data[2] === NULL) {
            break;
        }
        $preparedSQL->bind_param("sss", $data[2], $data[0], $data[1]);
        $preparedSQL->execute();
        if (!$preparedSQL) {
            echo $connection->error;
        }
        echo 'Updated ' . $data[0] . '(' . $data[1] . ') to have a quota of ' . $data[2] . '<br>';
    }
  }
} else {
  echo 'Invalid upload type.';
}
if (file_exists($targetFile)) {
    unlink($targetFile); 
  }