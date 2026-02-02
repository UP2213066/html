<?php
session_start();
include '/sec/db.php';
$conn = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
$preparedSQL = $conn->prepare('SELECT role FROM staff WHERE email=?');
$preparedSQL->bind_param("s", $_SESSION['email']);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
$found = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['role'] != 'Admin') {
            header('Location: /');
            exit();
        }
    }
} else {
    header('Location: /');
    exit(); 
}