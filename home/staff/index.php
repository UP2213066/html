<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for displaying the admin team member's
    home page so that they can undertake on a various tasks.

    © 2025 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FYP Staff Editor</title>
        <link rel="stylesheet" href="/style.css?v=2">
    </head>
    <body>
        <nav class="navigationBar">
            <a class="home" href="/home/">University of Portsmouth</a>
            <a href="/logout.php">Logout</a>
            <a href="../">Back</a>
        </nav>
        <main>
            <?php 
            echo '<h1>Welcome, ' . $_SESSION['name'] . '!</h1>';
            ?>
            <section class="actionGridSection">
                <button class="actionGridButton" onclick="window.location.href='/home/staff/all/'">View All Staff</button>
                <button class="actionGridButton" onclick="window.location.href='/home/staff/search/'">Search Staff</button>
                <button class="actionGridButton" onclick="window.location.href='/home/staff/add/'">Add Staff Member</button>
                <button class="actionGridButton" onclick="window.location.href='/home/staff/upload/'">Upload Staff</button>
            </section>
        </main>
       <?php include '/var/www/html/footer.php';?>
    </body>
</html>
