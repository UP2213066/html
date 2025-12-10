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
error_reporting(E_ALL); 
ini_set('display_errors', TRUE); 
ini_set('display_startup_errors', TRUE);
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("UPDATE students SET firstName=?, lastName=?, courseCode=?, moduleCode=?, supervisor=?, moderator=? WHERE id=?");
$preparedSQL->bind_param("sssssss", $_POST['firstName'], $_POST['lastName'], $_POST['courseCode'], $_POST['moduleCode'], $_POST['supervisor'], $_POST['moderator'], $_SESSION['idToUpdate']);
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