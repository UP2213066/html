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
session_start();
include '/sec/db.php';
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$role = trim($_POST['role']);
$quota = trim($_POST['quota']);
$oldName = trim($_SESSION['nameToUpdate']);
$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("UPDATE staff SET name=?, email=?, role=?, quota=? WHERE name=?");
$preparedSQL->bind_param("sssss", $name, $email, $role, $quota, $oldName);
$preparedSQL->execute();
$connection->close();
$name = $_SESSION['name'];
$role = $_SESSION['role'];
session_unset();
$_SESSION['name'] = $name;
$_SESSION['role'] = $role;
header("Location: /home/edit");
die();