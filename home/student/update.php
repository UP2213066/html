<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for updating the database using the new
    edited student data sent via POST.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
try {
    $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("SELECT name FROM staff WHERE email=?");
$preparedSQL->bind_param("s", $_POST['supervisor']);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $supervisorName = $row['name'];
    }
}
try {
    $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("SELECT name FROM staff WHERE email=?");
$preparedSQL->bind_param("s", $_POST['moderator']);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $moderatorName = $row['name'];
    }
}
try {
    $connection = new mysqli($hostname, $update_student_username, $update_student_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("UPDATE students SET firstName=?, lastName=?, courseCode=?, moduleCode=?, supervisor=?, supervisorEmail=?, moderator=?, moderatorEmail=? WHERE id=?");
$preparedSQL->bind_param("sssssssss", $_POST['firstName'], $_POST['lastName'], $_POST['course'], $_POST['module'], $supervisorName, $_POST['supervisor'], $moderatorName, $_POST['moderator'], $_SESSION['idToUpdate']);
$preparedSQL->execute();
$connection->close();
$name = $_SESSION['name'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];
session_unset();
$_SESSION['name'] = $name;
$_SESSION['role'] = $role;
$_SESSION['email'] = $email;
header("Location: /home/student/");
die();