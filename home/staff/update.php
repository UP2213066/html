<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for updating the database using the new
    edited staff data sent via POST.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$name = trim($_POST['name']);
if($_POST['studentsToAvoid'] === "") {
    $_POST['studentsToAvoid'] = NULL;
} else {
    $_POST['studentsToAvoid'] = str_replace('UP', '', strtoupper($_POST['studentsToAvoid']));
    $_POST['studentsToAvoid'] = str_replace(', ', ',', $_POST['studentsToAvoid']);
    $_POST['studentsToAvoid'] = str_replace(',', ', ', $_POST['studentsToAvoid']);
}
$connection = new mysqli($hostname, $update_staff_username, $update_staff_password, $database);
$preparedSQL = $connection->prepare("UPDATE staff SET name=?, email=?, role=?, quota=?, studentsToAvoid=? WHERE email=?");
$preparedSQL->bind_param("ssssss", $_POST['name'], $_POST['email'], $_POST['role'], $_POST['quota'], $_POST['studentsToAvoid'], $_SESSION['emailToUpdate']);
$preparedSQL->execute();
$connection->close();
$name = $_SESSION['name'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];
session_unset();
$_SESSION['name'] = $name;
$_SESSION['role'] = $role;
$_SESSION['email'] = $email;
header("Location: /home/staff/");
die();