<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the staff members name and
    sending it via a POST request to pull up the staff member for editing.

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
        <title>FYP Staff Editor</title>
        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <nav class="navigationBar">
            <a class="home" href="/home/">University of Portsmouth</a>
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
            <h1>Staff Editor</h1>
            <h2>Search For A Staff Member By Name</h2>
            <form action="/home/staff/search/search.php?redirect=name_search" method="post">
                <input type="text" name="firstName" id="firstName" placeholder="First Name">
                <input type="text" name="lastName" id="lastName" placeholder="Last Name">
                <input type="hidden" name="searchType" value="name">
                <input type="submit" value="Search">
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>
