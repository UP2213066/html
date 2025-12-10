<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for displaying the admin team member's
    home page so that they can undertake various tasks.

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
        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <nav class="navigationBar">
            <a class="home" href="/home/">University of Portsmouth</a>
            <a href="/logout.php">Logout</a>
        </nav>
        <main>
            <h1>Staff Editor</h1>
            <h2>Search For A Staff Member</h2>
            <form action="search.php" method="post">
                <input type="text" name="firstName" id="firstName" placeholder="First Name">
                <input type="text" name="lastName" id="lastName" placeholder="Last Name">
                <input type="submit" value="Search">
            </form>
            <?php
            if (isset($_SESSION['search-error'])) {
                echo $_SESSION['search-error'];
            }
            ?>
            <h2>Bulk Upload Staff</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="staffBulkUpload" id="staffBulkUpload" accept=".xlsx,.xls,.ods,.csv" required>
                <input type="hidden" name="fileType" value="staffUpload">
                <input type="submit" value="Upload">
            </form>
            <?php if (isset($_SESSION['staffMessage'])) {
                echo $_SESSION['staffMessage'];
            } ?>
            <h2>Bulk Upload Staff Quota</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="staffBulkUpload" id="staffBulkUpload" accept=".xlsx,.xls,.ods,.csv" required>
                <input type="hidden" name="fileType" value="quotaUpload">
                <input type="submit" value="Upload">
            </form>
            <?php if (isset($_SESSION['quotaMessage'])) {
                echo $_SESSION['quotaMessage'];
            } ?>
            <a href="add/"><button>Add New Staff Member</button></a>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>
