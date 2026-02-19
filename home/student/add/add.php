<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for adding a new student to the
    database and will update an existing student's information.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
try {
    $connection = new mysqli($hostname, $uploading_students_username, $uploading_students_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("INSERT INTO students VALUES(?, ?, ?, ?, ?, NULL, NULL, NULL, NULL) ON DUPLICATE KEY UPDATE id=VALUE(id), firstName=VALUE(firstName), lastName=VALUE(lastName), courseCode=VALUE(courseCode), moduleCode=VALUE(moduleCode)");
$preparedSQL->bind_param("sssss", $_POST['id'], $_POST['fname'], $_POST['lname'], $_POST['course'], $_POST['module']);
$preparedSQL->execute();
header('Location: /home/student/');
exit();