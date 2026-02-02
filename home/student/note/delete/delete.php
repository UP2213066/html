<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for deleting a student note from the
    database based on the note ID stored in the session variable.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $delete_notes_username, $delete_notes_password, $database);
$preparedSQL = $connection->prepare("DELETE FROM notes WHERE id=?");
$preparedSQL->bind_param("s", $_SESSION['noteIDToDelete']);
$preparedSQL->execute();
$connection->close();
header("Location: /home/student/");
die();