<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file takes the input from the login page and queries This
    against the data stored on the database. If a match occurs, they will be sent
    to the home page with their session data stored. Otherwise, they will be sent
    back to the login page with no session data.

    © 2025 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
session_start();
include '/sec/db.php';
$conn = new mysqli($hostname, $username, $password, $database);
$sql = 'SELECT email, password, name, role FROM staff';
$result = $conn->query($sql);
$found = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($_POST['username'] == $row['email'] && password_verify($_POST['password'], $row['password'])) {
            $found = true;
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
        }
    }
}
if ($found) {
    header('Location: /home');
} else {
    $_SESSION['login_error'] = "Username or password incorrect";
    session_write_close();     
    header('Location: /');
    exit();
}
