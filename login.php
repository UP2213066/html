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
try {
    $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
}catch (mysqli_sql_exception $e) {
    $_SESSION['login_error'] = "Something went wrong while processing your request. Please refresh the page or try again later.";
    header('Location: /');
    exit();
}
$preparedSQL = $connection->prepare("SELECT email, password, name, role, attempts, lockUntil FROM staff WHERE email = ?");
$preparedSQL->bind_param("s", $_POST['username']);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
$connection->close();
$found = false;
$targetResponseTime = 0.5; // seconds
$start = microtime(true);
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if ($row['lockUntil'] <= date("Y-m-d H:i:s", time())) {
        if ($_POST['username'] === $row['email'] && password_verify($_POST['password'], $row['password'])) {
            $found = true;
            $newAttempts = 0;
            $connection = new mysqli($hostname, $update_staff_username, $update_staff_password, $database);
            $preparedSQL = $connection->prepare("UPDATE staff SET attempts=? WHERE email = ?");
            $preparedSQL->bind_param("ss", $newAttempts, $_POST['username']);
            $preparedSQL->execute();
            $connection->close();
            session_regenerate_id(true);
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
        } else {
            if ($row['attempts'] >= 5) {
                $lockUntil = date("Y-m-d H:i:s", time() + 300);
                $connection = new mysqli($hostname, $update_staff_username, $update_staff_password, $database);
                $preparedSQL = $connection->prepare("UPDATE staff SET lockUntil=? WHERE email = ?");
                $preparedSQL->bind_param("ss", $lockUntil, $_POST['username']);
                $preparedSQL->execute();
                $connection->close();
                $connection = new mysqli($hostname, $insert_attemp_username, $insert_attempt_password, $database);
                $now = date("Y-m-d H:i:s", time());
                $preparedSQL = $connection->prepare("INSERT INTO failedLogins VALUES email=?, IP=?, timestamp=?");
                $preparedSQL->bind_param("ss", $_POST['username'], $_SERVER['REMOTE_ADDR'], $now);
                $preparedSQL->execute();
                $connection->close();
            } else {
                $newAttempts = $row['attempts'] + 1;
                $connection = new mysqli($hostname, $update_staff_username, $update_staff_password, $database);
                $preparedSQL = $connection->prepare("UPDATE staff SET attempts=? WHERE email = ?");
                $preparedSQL->bind_param("ss", $newAttempts, $_POST['username']);
                $preparedSQL->execute();
                $connection->close();
                $now = date("Y-m-d H:i:s", time());
                $preparedSQL = $connection->prepare("INSERT INTO failedLogins VALUES email=?, IP=?, timestamp=?");
                $preparedSQL->bind_param("ss", $_POST['username'], $_SERVER['REMOTE_ADDR'], $now);
                $preparedSQL->execute();
                $connection->close();
            }
        }
    }
} else {
    password_verify('password', '$2y$10$usesomesillystringforsalt$abcdefghijklmnopqrstu');
}
$elapsed = microtime(true) - $start;
if ($elapsed < $targetResponseTime) {
    usleep(($targetResponseTime - $elapsed) * 1e6); // convert seconds to microseconds
}
if ($found) {
    header('Location: /home');
} else {

    $_SESSION['login_error'] = "Username or password incorrect";
    header('Location: /');
    exit();
}
