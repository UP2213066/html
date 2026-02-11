<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for displaying the student home page
    with various options for managing student data.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FYP Student Editor</title>
        <link rel="stylesheet" href="/style.css?v=2">
    </head>
    <body>
        <nav class="navigationBar">
            <a class="home" href="/home/">Home</a>
            <a href="/logout.php">Logout</a>
            <?php 
                include "/var/www/html/redirects.php";
                if (isset($_GET['redirect']) && isset($redirects[$_GET['redirect']])) {
                    $redirect = $redirects[$_GET['redirect']];
                    echo "<a href='$redirect'>Back</a>";
                } else {
                    echo "<a href='../'>Back</a>";
                } 
            ?>
        </nav>
        <main>
            <?php 
            echo '<h1>Welcome, ' . $_SESSION['name'] . '!</h1>';
            ?>
            <section class="actionGridSection">
                <button class="actionGridButton" onclick="window.location.href='/home/student/upload/student-choice/supervisor/'">Supervisor Choices</button>
                <button class="actionGridButton" onclick="window.location.href='/home/student/upload/student-choice/moderator/'">Moderator Choices</button>
            </section>
        </main>
       <?php include '/var/www/html/footer.php';?>
    </body>
</html>
