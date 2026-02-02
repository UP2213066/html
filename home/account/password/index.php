<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the current password and new password
    inputs to send for processing to update the user's password.

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
        <title>Change Password</title>
        <link rel="stylesheet" href="/style.css?v=1">
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
        <h1>Change Password</h1>
            <form action="update.php" method="post">
                <label for="current">Current Password:</label>
                <input type="password" id="current" name="current">
                <label for="new1">Enter New Password:</label>
                <input type="password" id="new1" name="new1">
                <label for="new2">Re-enter New Password:</label>
                <input type="password" id="new2" name="new2">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="submit" value="Submit">
            </form>
            <?php 
                echo $_SESSION['passwordUpdateError'];
                unset($_SESSION['passwordUpdateError']);
            ?>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>