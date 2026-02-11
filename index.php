<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the staff username and 
    password before testing the credentials against the database.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
session_start();
unset($_SESSION['name']);
unset($_SESSION['role']);
$error = $_SESSION['login_error'] ?? '';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FYP Admin Login</title>
        <link rel="stylesheet" href="/style.css?v=2">
    </head>
    <body>
    <nav class="navigationBar">
        <a class="home" href="/">Home</a>
    </nav>
    <main>
        <h1>Login</h1>
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter Username">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter Password">
                <input type="submit" value="Login">
                <?php if($error) echo '<p class="error">'.$error.'</p>'; ?>
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>