<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for processing the password update
    request by validating the entered passwords and updating to the new password

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    header("Location: /");
    exit;
}
try {
    $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("SELECT password FROM staff WHERE email = ?");
$preparedSQL->bind_param("s", $_SESSION['email']);
$preparedSQL->execute();

$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    unset($_SESSION['search-error']);
    $row = $result->fetch_assoc();
    $currentPassword = $row['password'];
}
$connection->close();
if (password_verify($_POST['current'], $currentPassword)) {
    if ($_POST['new1'] === $_POST['new2']) {
        $updatedPassword = password_hash($_POST['new1'], PASSWORD_DEFAULT);
        $connection = new mysqli($hostname, $update_staff_username, $update_staff_password, $database);
        $preparedSQL = $connection->prepare("UPDATE staff SET password = ? WHERE email = ?");
        $preparedSQL->bind_param("ss", $updatedPassword, $_SESSION['email']);
        $preparedSQL->execute();
        $connection->close();
        header("Location: /logout.php");
    } else {
        $_SESSION['passwordUpdateError'] = "Passwords Must Match";
        header("Location: /home/account/password");
    }
} else {
    $_SESSION['passwordUpdateError'] = "Password Incorrect";
    header("Location: /home/account/password");
}
exit;