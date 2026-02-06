<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for adding a new staff member to the
    database and will update an existing staff member's information.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $uploading_staff_username, $uploading_staff_password, $database);
$preparedSQL = $connection->prepare("INSERT INTO staff VALUES(?, ?, ?, ?, ?, 0, NULL) ON DUPLICATE KEY UPDATE quota=VALUES(quota), allocatedStudents=VALUES(allocatedStudents), studentsToAvoid=VALUES(studentsToAvoid)");
$preparedSQL->bind_param("sssss", $_POST['email'], $_POST['name'], $_POST['role'], $start_password, $_POST['quota']);
$preparedSQL->execute();
header('Location: /home/staff/');
exit();