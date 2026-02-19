<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for handling file uploads for staff,
    student, and quota data. It processes the uploaded spreadsheet files and
    updates the database accordingly.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
require '/var/www/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['csrf_token'])) {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo "Invalid CSRF token.";
    header('Location: /');
    exit();
  }
} else {
  header('Location: /');
  exit();
}
function unlinkFile($targetFile) {
  if (file_exists($targetFile)) {
    unlink($targetFile); 
  }
}
$targetDir = '/uploads/';
$file = $_FILES['fileUpload'];
$fileExtention = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$tmpFile = $_FILES['fileUpload']['tmp_name'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($tmpFile);
$uniqueName = bin2hex(random_bytes(16)) . '.' . $fileExtention;
$targetFile = $targetDir . $uniqueName;

if ($file['size'] > 5242880){
  unlinkFile($targetFile);
  echo "File too large";
  exit();
}

$allowedExtentions = ['xlsx', 'xls', 'csv', 'ods'];
if (!in_array($fileExtention, $allowedExtentions)) {
  unlinkFile($targetFile);
  echo "Invalid file type of $fileExtention";
  exit();
}

$allowedMimes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '	application/vnd.ms-excel', 'text/csv', 'application/vnd.oasis.opendocument.spreadsheet'];
if (!in_array($mimeType, $allowedMimes)) {
  unlinkFile($targetFile);  
  echo "Invalid MIME type of $mimeType";
  exit();
}

if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
  unlinkFile($targetFile);
  echo "Failed to save file";
  exit();
}
try {
  $spreadsheet = IOFactory::load($targetFile);
  $sheet = $spreadsheet->getActiveSheet();
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
  unlinkFile($targetFile);
  header('location: /home/');
  exit;
}
unlinkFile($targetFile);
if ($_POST['fileType'] === 'staffUpload') {
  echo 'Processing staff upload...<br>';
  $connection = new mysqli($hostname, $uploading_staff_username, $uploading_staff_password, $database);
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
    }
  }
  header('location: /home/staff/');
  exit();
} elseif ($_POST['fileType'] === 'quotaUpload') {
  echo 'Processing quota upload...<br>';
  $connection = new mysqli($hostname, $update_staff_username, $update_staff_username, $database);
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
    }
  }
  header('location: /home/staff/');
  exit();
} elseif ($_POST['fileType'] === 'studentUpload') {
  echo 'Processing student upload...<br>';
  $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
  $query = "SELECT id FROM placement_students";
  $result = $connection->query($query);
  $placementStudents = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $placementStudents[] = $row['id'];
    }
  }
  echo $placementStudents;
  $connection = new mysqli($hostname, $uploading_students_username, $uploading_students_password, $database);
  $preparedSQL = $connection->prepare("INSERT IGNORE INTO students VALUES(?, ?, ?, ?, ?, NULL, NULL, NULL, NULL)");
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
      if ($data[0] === NULL || $data[1] === NULL || $data[2] === NULL || $data[3] === NULL || $data[4] === NULL) {
        break;
      }
      if (strtoupper(substr($data[0], 0, 2) == "UP")) {
        $data[0] = substr($data[0],2);
      }
      if (!in_array($data[0], $placementStudents)) {
        echo "Adding student ID " . $data[0] . "<br>";
        $preparedSQL->bind_param("sssss", $data[0], $data[1], $data[2], $data[3], $data[4]);
        $preparedSQL->execute();
      } else {
        echo "Skipping placement student ID " . $data[0] . "<br>";
      }
    }
  }
  header('location: /home/student/');
  exit();
} elseif ($_POST['fileType'] === 'placementStudentUpload') {
  echo 'Processing placement student upload...<br>';
  $connection = new mysqli($hostname, $uploading_students_username, $uploading_students_password, $database);
  $preparedSQL = $connection->prepare("INSERT IGNORE INTO placement_students VALUES(?, ?, ?, ?)");
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
      if ($data[0] === NULL || $data[1] === NULL || $data[2] === NULL || $data[3] === NULL) {
        break;
      }
      if (strtoupper(substr($data[0], 0, 2) == "UP")) {
        $data[0] = substr($data[0],2);
      }
      $preparedSQL->bind_param("ssss", $data[0], $data[1], $data[2], $data[3]);
      $preparedSQL->execute();
    }
  }
  header('location: /home/student/');
  exit();
} elseif ($_POST['fileType'] === 'selfReportUpload') {
  echo 'Processing self report upload...<br>';
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
      var_dump($data);
      if ($data[0] === NULL || $data[1] === NULL || $data[2] === NULL || $data[3] === NULL || $data[5] === NULL) {
        break;
      }
      if (strtoupper($data[5]) === "YES") {
        $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
        $preparedSQL = $connection->prepare("SELECT email FROM staff WHERE name=?");
        $preparedSQL->bind_param("s", $data[1]);
        $preparedSQL->execute();
        $result = $preparedSQL->get_result();
        $email = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $email = $row['email'];
            }
        }
        $connection = new mysqli($hostname, $update_student_username, $update_student_password, $database);
        if (strtoupper($data[0]) === "SUPERVISOR") {
          $preparedSQL = $connection->prepare("UPDATE students SET supervisor=?, supervisorEmail=? WHERE id=?");
        } elseif (strtoupper($data[0]) === "MODERATOR") {
          $preparedSQL = $connection->prepare("UPDATE students SET moderator=?, moderatorEmail=? WHERE id=?");
        }
        if(substr($data[3], 0, 2) == "UP") {
          $data[3] = substr($data[3], 2);
        }
        $preparedSQL->bind_param("sss", $data[1], $email, $data[3]);
        $preparedSQL->execute();
      }
    }
  }
  header('location: /home/student/');
  exit();
} elseif ($_POST['fileType'] === 'studentSupervisorChoiceUpload') {
  echo 'Processing student choice upload...<br>';
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
      if ($data[0] === NULL || $data[1] === NULL || $data[2] === NULL || $data[3] === NULL) {
        break;
      }
      if(substr($data[0], 0, 2) == "UP") {
          $data[0] = substr($data[0], 2);
        }
      $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
      $preparedSQL = $connection->prepare("SELECT supervisor FROM students WHERE id=?");
      $preparedSQL->bind_param("s", $data[0]);
      $preparedSQL->execute();
      $result = $preparedSQL->get_result();
      $currentSupervisor = "";
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $currentSupervisor = $row['supervisor'];
          }
      }
      if ($currentSupervisor === NULL || $currentSupervisor === "") {
        $choices = [$data[3], $data[4], $data[5]];
        foreach ($choices as $choice) {
          $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
          $preparedSQL = $connection->prepare("SELECT allocatedStudents, quota, studentsToAvoid FROM staff WHERE name=?");
          $preparedSQL->bind_param("s", $choice);
          $preparedSQL->execute();
          $result = $preparedSQL->get_result();
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $allocatedStudents = $row['allocatedStudents'];
              $quota = $row['quota'];
              if($row['studentsToAvoid'] === NULL) {
                $row['studentsToAvoid'] = "";
              }
              $studentsToAvoid = explode(", ", $row['studentsToAvoid']);
              if(!in_array($data[0], $studentsToAvoid)) {
                if ($allocatedStudents < $quota) {
                  $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
                  $preparedSQL = $connection->prepare("SELECT email FROM staff WHERE name=?");
                  $preparedSQL->bind_param("s", $choice);
                  $preparedSQL->execute();
                  $result = $preparedSQL->get_result();
                  $email = "";
                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          $email = $row['email'];
                      }
                  }
                  $connection = new mysqli($hostname, $update_student_username, $update_student_password, $database);
                  $preparedSQL = $connection->prepare("UPDATE students SET supervisor=?, supervisorEmail=? WHERE id=?");
                  $preparedSQL->bind_param("sss", $choice, $email, $data[0]);
                  $preparedSQL->execute();
                  break 2; 
                }
              }
            }
          }
        }
      }
    }
  }
  header('location: /home/student/');
  exit();
} else {
  echo 'Invalid upload type.';
}