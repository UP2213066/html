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
if ($_POST['searchType'] === "id" || isset($_GET['id'])) {
    if (isset($_GET['id'])) {
        $_POST['id'] = $_GET['id'];
    }
    $connection = new mysqli($hostname, $username, $password, $database);
    $preparedSQL = $connection->prepare("SELECT id, firstName, lastName, courseCode, moduleCode, supervisor, moderator FROM students WHERE id=?");
    $preparedSQL->bind_param("s", $_POST['id']);
    $preparedSQL->execute();
    $_SESSION['idToUpdate'] = $_POST['id'];
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
        <link rel="stylesheet" href="/style.css?v=2">
    </head>
    <body>
        <nav class="navigationBar">
            <a class="home" href="/home/">University of Portsmouth</a>
            <a href="/logout.php">Logout</a>
            <a href="./">Back</a>
        </nav>
        <main>
            <?php echo "<h1>" . $name . "</h1>" ?>
            <form action="/home/student/update.php" method="post">
                <label for="firstName">First Name:</label>
                <?php echo '<input type="text" id="firstName" name="firstName" value="' . $firstName . '">' ?>
                <br><br>
                <label for="lastName">Last Name:</label>
                <?php echo '<input type="text" id="lastName" name="lastName" value="' . $lastName . '">' ?>
                <br><br>
                <label for="course">Course Code:</label>
                <?php echo '<input type="text" id="course" name="course" value="' . $course . '">' ?>
                <br><br>
                <label for="module">Module Code:</label>
                <?php echo '<input type="text" id="module" name="module" value="' . $module . '">' ?>
                <br><br>
                <label for="supervisor">Supervisor:</label>
                <?php echo '<input type="text" id="supervisor" name="supervisor" value="' . $supervisor . '">' ?>
                <br><br>
                <label for="moderator">Moderator:</label>
                <?php echo '<input type="text" id="moderator" name="moderator" value="' . $moderator . '">' ?>
                <br><br>
                <input type="submit" value="Submit Changes">
                <br><br>
                <button type="submit" formaction="/home/student/delete/">Delete Student</button>
            </form>
            <?php
                $connection = new mysqli($hostname, $username, $password, $database);
                $preparedSQL = $connection->prepare("SELECT id, note FROM student_notes WHERE studentID=?");
                $preparedSQL->bind_param("s", $_POST['id']);
                $preparedSQL->execute();
                $result = $preparedSQL->get_result();
                echo "<h2>Notes:</h2>";
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $note = $row['note'];
                        $id = $row['id'];
                        echo "<a href='/home/student/note?id=$id'> $note </a><br>";
                    }
                } else {
                    echo "No Notes Found<br>";
                }
            ?>
            <form>
                <button type="submit" formaction="/home/student/note/add/">Add Note</button>
            </form>
            
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>