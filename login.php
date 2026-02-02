<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file takes the input from the login page and queries this
    against the data stored on the database. If a match occurs, they will be sent
    to the home page with their session data stored. Otherwise, they will be sent
    back to the login page with no session data.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
session_start();
include '/sec/db.php';
$connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
$preparedSQL = $connection->prepare("SELECT email, password, name, role FROM staff WHERE email = ?");
$preparedSQL->bind_param("s", $_POST['username']);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
$found = false;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($_POST['username'] === $row['email'] && password_verify($_POST['password'], $row['password'])) {
        $found = true;
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['email'] = $row['email'];
    }
}
$connection->close();
if ($found) {
    header('Location: /home');
} else {
    $_SESSION['login_error'] = "Username or password incorrect";
    session_write_close();     
    header('Location: /');
    exit();
}
