<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for confirming deletion of a students note
    from the database before performing the irreversible action.

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
        <title>FYP Student Note Delete</title>
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
            <h1>Confirm Student Note Delete</h1>
            <form action="delete.php" method="post">
                <input type="submit" value="Delete Note">
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>
