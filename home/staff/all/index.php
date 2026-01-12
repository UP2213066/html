<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database and pulling
    every staff member to view and edit.

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
        <link rel="stylesheet" href="/style.css?v=1">
    </head>
    <body>
    <nav class="navigationBar">
        <a class="home" href="/home/">University of Portsmouth</a>
        <a href="/logout.php">Logout</a>
        <a href="../">Back</a>
    </nav>
    <main>
        <h1>All Staff</h1>
        <?php
        $connection = new mysqli($hostname, $username, $password, $database);
        $preparedSQL = $connection->prepare("SELECT name, email, role, quota, allocatedStudents, studentsToAvoid FROM staff");
        $preparedSQL->execute();
        $result = $preparedSQL->get_result();
        if ($result->num_rows > 0) {
            unset($_SESSION['search-error']);
            while ($row = $result->fetch_assoc()) {
                $name = $row['name'];
                $email = $row['email'];
                $_SESSION['emailToUpdate'] = $email;
                $role = $row['role'];
                $quota = $row['quota'];
                $allocatedStudents = $row['allocatedStudents'];
                $studentsToAvoid = $row['studentsToAvoid'];
                echo "<a href='/home/staff/search/search.php?email=$email&return=all'>$name - $email | Role: $role | Quota: $quota | Allocated Students: $allocatedStudents</a><br>";
            }
        } else {
            $_SESSION['search-error'] = "Student Not Found";
            header("Location: /home/student/");
            die();
        }
        ?>
    </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>