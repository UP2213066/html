<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database with a staff
    members name to pull up their profile for editing.

    © 2025 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
if ($_POST['searchType'] === "name") {
    $name = $_POST['firstName'] . ' ' . $_POST['lastName'];
    $connection = new mysqli($hostname, $username, $password, $database);
    $preparedSQL = $connection->prepare("SELECT name, email, role, quota, allocatedStudents, studentsToAvoid FROM staff WHERE name=?");
    $preparedSQL->bind_param("s", $name);
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
        }
    } else {
        $_SESSION['search-error'] = "Student Not Found";
        header("Location: /home/student/");
        die();
    }
} elseif ($_POST['searchType'] === "id") {
    $connection = new mysqli($hostname, $username, $password, $database);
    $preparedSQL = $connection->prepare("SELECT id, firstName, lastName, courseCode, moduleCode, supervisor, moderator FROM students WHERE id=?");
    $preparedSQL->bind_param("s", $_POST['id']);
    $preparedSQL->execute();
    $result = $preparedSQL->get_result();
    if ($result->num_rows > 0) {
        unset($_SESSION['search-error']);
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $name = $row['firstName'] . ' ' . $row['lastName'];
            $course = $row['courseCode'];
            $module = $row['moduleCode'];
            if (isset($row['supervisor'])) {
                $supervisor = $row['supervisor'];
            } else {
                $supervisor = "";
            }
            if (isset($row['moderator'])) {
                $moderator = $row['moderator'];
            } else {
                $moderator = "";
            }
        }
    } else {
        $_SESSION['search-error'] = "Student Not Found";
        header("Location: /home/student/");
        die();
    }
} else {
    $_SESSION['search-error'] = "Student Not Found";
    header("Location: /home/student/");
    die();
}
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
        <?php echo "<h1>" . $name . "</h1>" ?>
            <form action="update.php" method="post">
                <label for="name">Name:</label>
                <?php echo '<input type="text" id="name" name="name" value="' . $name . '">' ?>
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