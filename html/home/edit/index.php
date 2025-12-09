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
session_start();
if (!isset($_SESSION['role']) && !$_SESSION['role'] === 'Admin') {
    header('Location: /'); 
    exit();
}
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
            <h2>Bulk Upload</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="staffBulkUpload" id="staffBulkUpload" accept=".xlsx,.xls,.ods,.csv" required>
                <input type="submit" value="Upload">
            </form>
        </main>
    </body>
    <footer>
        <?php echo '<p>© ' . date("Y", ) . ' Ayden Lunnon. All rights reserved.</p>' ?>
    </footer>
</html>
