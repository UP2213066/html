<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for updating the database using the new
    edited data sent.

    © 2025 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("UPDATE student_notes SET note=? WHERE id=?");
$preparedSQL->bind_param("ss", $_POST['note'], $_SESSION['noteIDToDelete']);
$preparedSQL->execute();
$connection->close();
header("Location: /home/student/");
die();