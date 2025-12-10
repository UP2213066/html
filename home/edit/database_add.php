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
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("INSERT INTO staff VALUES(?, ?, ?, ?, ?, 0, NULL) ON DUPLICATE KEY UPDATE quota=VALUE(quota), allocatedStudents=VALUE(allocatedStudents), studentsToAvoid=VALUE(studentsToAvoid)");
$preparedSQL->bind_param("sssss", $_POST['name'], $_POST['email'], $startPassword, $_POST['role'], $_POST['quota']);
$preparedSQL->execute();
header('Location: /edit/');
exit();