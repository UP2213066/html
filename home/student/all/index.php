<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database and pulling
    every student to view and edit.

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
        <title>FYP Student Editor</title>
        <link rel="stylesheet" href="/style.css?v=1">
    </head>
    <body>
    <nav class="navigationBar">
        <a class="home" href="/home/">University of Portsmouth</a>
        <a href="/logout.php">Logout</a>
        <a href="./">Back</a>
    </nav>
    <main>
        <h1>All Students</h1>
<?php
$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("SELECT id, firstName, lastName, courseCode, moduleCode, supervisor, moderator FROM students");
$preparedSQL->execute();
$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    unset($_SESSION['search-error']);
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $name = $firstName . ' ' . $lastName;
        $_SESSION['nameToUpdate'] = $name;
        $course = $row['courseCode'];
        $module = $row['moduleCode'];
        echo "<p>UP$id - $name - $course - $module";
        if (isset($row['supervisor'])) {
            $supervisor = $row['supervisor'];
            echo " - $supervisor";
        } else {
            $supervisor = "";
            echo " - No Supervisor";
        }
        if (isset($row['moderator'])) {
            $moderator = $row['moderator'];
            echo " - $moderator</p>";
        } else {
            $moderator = "";
            echo " - No Moderator</p>";
        }
    }
} else {
    $_SESSION['search-error'] = "Student Not Found";
    header("Location: /home/student/");
    die();
}
?>
        <?php echo "<h1>" . $name . "</h1>" ?>
            <form action="update.php" method="post">
                <label for="firstName">First Name:</label>
                <?php echo '<input type="text" id="firstName" name="firstName" value="' . $firstName . '">' ?>
                <label for="lastName">Last Name:</label>
                <?php echo '<input type="text" id="lastName" name="lastName" value="' . $lastName . '">' ?>
                <label for="course">Course Code:</label>
                <?php echo '<input type="text" id="course" name="course" value="' . $course . '">' ?>
                <label for="module">Module Code:</label>
                <?php echo '<input type="text" id="module" name="module" value="' . $module . '">' ?>
                <label for="supervisor">Supervisor:</label>
                <?php echo '<input type="text" id="supervisor" name="supervisor" value="' . $supervisor . '">' ?>
                <label for="moderator">Moderator:</label>
                <?php echo '<input type="text" id="moderator" name="moderator" value="' . $moderator . '">' ?>
                <input type="submit" value="Submit">
                <button type="submit" formaction="delete/">Delete</button>
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>