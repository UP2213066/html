<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the students ID via a POST
    request to pull up the student for editing.

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
        <link rel="stylesheet" href="/style.css">
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
            <h1>Student Editor</h1>
            <h2>Search For A Student By Student Number</h2>
            <form action="search.php" method="post">
                <input type="text" name="id" id="id" placeholder="Student Number">
                <input type="hidden" name="searchType" value="id">
                <input type="submit" value="Search">
            </form>
            <?php
                if (isset($_SESSION['search-error'])) {
                    echo $_SESSION['search-error'];
                }
            ?>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>
