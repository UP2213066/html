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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '/var/www/html/validate.php';
$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("SELECT password FROM staff WHERE email = ?");
$preparedSQL->bind_param("s", $_SESSION['email']);
$preparedSQL->execute();

$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    unset($_SESSION['search-error']);
    while ($row = $result->fetch_assoc()) {
        $password = $row['password'];
    }
}
$connection->close();
if (password_verify($_POST['current'], $password)) {
    if ($_POST['new1'] === $_POST['new2']) {
        $updatedPassword = password_hash($_POST['new1'], PASSWORD_DEFAULT);
        $connection = new mysqli($hostname, $username, $password, $database);
        $preparedSQL = $connection->prepare("UPDATE staff SET password = ? WHERE email = ?");
        $preparedSQL->bind_param("ss", $password, $_SESSION['email']);
        $preparedSQL->execute();
        $connection->close();
        header("Location: /logout.php");
    } else {
        header("Location: /account/password");
    }
    header("Location: /account/password");
}
die();