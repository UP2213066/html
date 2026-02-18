<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database and pulling
    every student to view and edit.

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
        <link rel="stylesheet" href="/style.css?v=1">
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
        <h1>All Students</h1>
        <?php
        $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
        if ($connection -> error) {
            echo "<p>Database Connection Failed</p>";
            exit();
        }
        $preparedSQL = $connection->prepare("SELECT id, firstName, lastName, courseCode, moduleCode, supervisor, moderator FROM students");
        $preparedSQL->execute();
        $result = $preparedSQL->get_result();
        echo "<h2>Current Students:</h2>";
        if ($result->num_rows > 0) {
            unset($_SESSION['search-error']);
            echo "<table>";
            echo "<tr style='font-size: 1.25em; background-color: purple; color: white;'>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Course</th>";
            echo "<th>Module</th>";
            echo "<th>Supervisor</th>";
            echo "<th>Moderator</th>";
            echo "<th>Profile</th>";
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='font-weight: normal;'>";
                $id = $row['id'];
                $firstName = $row['firstName'];
                $lastName = $row['lastName'];
                $name = $firstName . ' ' . $lastName;
                $course = $row['courseCode'];
                $module = $row['moduleCode'];
                $supervisor = $row['supervisor'] ?? "NO SUPERVISOR";
                $moderator = $row['moderator'] ?? "NO MODERATOR";
                echo "<th>$id</th>";
                echo "<th>$name</th>";
                echo "<th>$course</th>";
                echo "<th>$module</th>";
                echo "<th>$supervisor</th>";
                echo "<th>$moderator</th>";
                echo "<th><a href='/home/student/search/search.php?id=$id&redirect=student_all'>View Profile</a></th>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No final students found.</p>";
        }
        $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
        $preparedSQL = $connection->prepare("SELECT id, firstName, lastName, placementEndYear FROM placement_students");
        $preparedSQL->execute();
        $result = $preparedSQL->get_result();
        echo "<h2>Placement Students:</h2>";
        if ($result->num_rows > 0) {
            unset($_SESSION['search-error']);
            echo "<table>";
            echo "<tr style='font-size: 1.25em; background-color: purple; color: white;'>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Placement End Year</th>";
            echo "<th>Profile</th>";
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='font-weight: normal;'>";
                $id = $row['id'];
                $firstName = $row['firstName'];
                $lastName = $row['lastName'];
                $name = $firstName . ' ' . $lastName;
                $placementEndYear = $row['placementEndYear'];
                echo "<th>$id</th>";
                echo "<th>$name</th>";
                echo "<th>$placementEndYear</th>";
                echo "<th><a href='/home/student/placement/search.php?id=$id&redirect=student_all'>View Profile</a></th>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No placement students found.</p>";
        }
        ?>
    </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>