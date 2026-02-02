<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for adding a new student note to the
    database using the data sent via a POST request.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $insert_notes_username, $insert_notes_password, $database);
$preparedSQL = $connection->prepare("INSERT INTO notes (studentID, note) VALUES (?, ?)");
$preparedSQL->bind_param("ss", $_SESSION['idToUpdate'], $_POST['note']);
$preparedSQL->execute();
header('Location: /home/student/');
exit();