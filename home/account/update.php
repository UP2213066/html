<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for updating the database using the new
    edited data sent.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
$name = trim($_POST['name']);
try {
    $connection = new mysqli($hostname, $update_staff_username, $update_staff_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("UPDATE staff SET name=?, email=?, role=?, quota=? WHERE email=?");
$preparedSQL->bind_param("sssss", $_POST['name'], $_POST['email'], $_POST['role'], $_POST['quota'], $_SESSION['emailToUpdate']);
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