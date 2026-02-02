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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $update_student_username, $update_student_password, $database);
$preparedSQL = $connection->prepare("UPDATE placement_students SET firstName=?, lastName=?, placementEndYear=? WHERE id=?");
$preparedSQL->bind_param("ssss", $_POST['firstName'], $_POST['lastName'], $_POST['placementEndYear'], $_SESSION['idToUpdate']);
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