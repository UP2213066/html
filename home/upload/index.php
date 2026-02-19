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
  echo "Invalid file type of" . htmlspecialchars($fileExtention);
  exit();
}

$allowedMimes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '	application/vnd.ms-excel', 'text/csv', 'application/vnd.oasis.opendocument.spreadsheet'];
if (!in_array($mimeType, $allowedMimes)) {
  unlinkFile($targetFile);  
  echo "Invalid MIME type of " . htmlspecialchars($mimeType);
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
  try {
    $connection = new mysqli($hostname, $uploading_staff_username, $uploading_staff_password, $database);
  } catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
  }
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
  try {
    $connection = new mysqli($hostname, $update_staff_username, $update_staff_username, $database);
  } catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
  }
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
  try {
    $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
  } catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
  }
  $query = "SELECT id FROM placement_students";
  $result = $connection->query($query);
  $placementStudents = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $placementStudents[] = $row['id'];
    }
  }
  try {
    $connection = new mysqli($hostname, $uploading_students_username, $uploading_students_password, $database);
  } catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
  }
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
        $preparedSQL->bind_param("sssss", $data[0], $data[1], $data[2], $data[3], $data[4]);
        $preparedSQL->execute();
      } else {
      }
    }
  }
  header('location: /home/student/');
  exit();
} elseif ($_POST['fileType'] === 'placementStudentUpload') {
  try {
    $connection = new mysqli($hostname, $uploading_students_username, $uploading_students_password, $database);
  } catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
  } 
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
        try {
          $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
        } catch (mysqli_sql_exception $e) {
          echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
          exit();
        }
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
        try {
          $connection = new mysqli($hostname, $update_student_username, $update_student_password, $database);
        } catch (mysqli_sql_exception $e) {
          echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
          exit();
        }
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
      try {
        $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
      } catch (mysqli_sql_exception $e) {
        echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
        exit();
      }
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
          try {
            $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
          } catch (mysqli_sql_exception $e) {
            echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
            exit();
          }
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
                  try {
                    $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
                  } catch (mysqli_sql_exception $e) {
                    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
                    exit();
                  }
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
                  try {
                    $connection = new mysqli($hostname, $update_student_username, $update_student_password, $database);
                  } catch (mysqli_sql_exception $e) {
                    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
                    exit();
                  }
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
  exit();
}