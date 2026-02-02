<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database and pulling
    every staff member to view and edit.

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
        <h1>All Staff</h1>
        <?php
        $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
        $preparedSQL = $connection->prepare("SELECT name, email, role, quota, allocatedStudents, studentsToAvoid FROM staff WHERE email <> ?");
        $preparedSQL->bind_param("s", $_SESSION['email']);
        $preparedSQL->execute();
        $result = $preparedSQL->get_result();
        if ($result->num_rows > 0) {
            unset($_SESSION['search-error']);
            echo "<table>";
            echo "<tr style='font-size: 1.25em; background-color: purple; color: white;'>";
            echo "<th>Name</th>";
            echo "<th>Email</th>";
            echo "<th>Role</th>";
            echo "<th>Quota</th>";
            echo "<th>Allocated</th>";
            echo "<th>Avoid</th>";
            echo "<th>Profile</th>";
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='font-weight: normal;'>";
                $name = $row['name'];
                $email = $row['email'];
                $role = $row['role'];
                $quota = $row['quota'];
                $allocatedStudents = $row['allocatedStudents'];
                $studentsToAvoid = $row['studentsToAvoid'] ?? "NONE";
                echo "<th>$name</th>";
                echo "<th>$email</th>";
                echo "<th>$role</th>";
                echo "<th>$quota</th>";
                echo "<th>$allocatedStudents</th>";
                echo "<th>$studentsToAvoid</th>";
                echo "<th><a href='/home/staff/search/search.php?email=$email&redirect=staff_all'>View Profile</a></th>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<h2>No staff members found. Go to <a href='/home/account/'>Account Manager</a> to edit your own account</h2>";
        }
        ?>
    </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>