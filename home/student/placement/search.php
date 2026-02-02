<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database with a students
    ID to pull up their profile for editing.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
if ($_POST['searchType'] === "id" || isset($_GET['id'])) {
    if (isset($_GET['id'])) {
        $_POST['id'] = $_GET['id'];
    }
    if (strtoupper(substr($_POST['id'], 0, 2)) === "UP") {
        $_POST['id'] = substr($_POST['id'], 2);
    }
    $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
    $preparedSQL = $connection->prepare("SELECT id, firstName, lastName, placementEndYear FROM placement_students WHERE id=?");
    $preparedSQL->bind_param("s", $_POST['id']);
    $preparedSQL->execute();
    $_SESSION['idToUpdate'] = $_POST['id'];
    $result = $preparedSQL->get_result();
    if ($result->num_rows > 0) {
        unset($_SESSION['search-error']);
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $_SESSION['idToUpdate'] = $id;
            $firstName = $row['firstName'];
            $lastName = $row['lastName'];
            $name = $firstName . ' ' . $lastName;
            $_SESSION['nameToUpdate'] = $name;
            $placementEndYear = $row['placementEndYear'];
        }
    } else {
        $_SESSION['search-error'] = "Student Not Found";
        header("Location: /home/placement/search/");
        die();
    }
} else {
    $_SESSION['search-error'] = "Student Not Found";
    header("Location: /home/placement/search/");
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
            <?php 
            include "/var/www/html/redirects.php";
            if (isset($_GET['redirect']) && isset($redirects[$_GET['redirect']])) {
                $redirect = $redirects[$_GET['redirect']];
                echo "<a href='$redirect'>Back</a>";
            } else {
                echo "<a href='./'>Back</a>";
            } 
            ?>
        </nav>
        <main>
            <?php echo "<h1>" . $name . "</h1>" ?>
            <form action="/home/student/placement/update.php" method="post">
                <label for="firstName">First Name:</label>
                <?php echo '<input type="text" id="firstName" name="firstName" value="' . $firstName . '">' ?>
                <label for="lastName">Last Name:</label>
                <?php echo '<input type="text" id="lastName" name="lastName" value="' . $lastName . '">' ?>
                <label for="placementEndYear">Placement End Year:</label>
                <?php 
                $year = date("Y") - 1;
                echo '<select id="placementEndYear" name="placementEndYear">';
                echo '<option value="Academic Year ' . $year . '/' . (substr($year + 1, 2)) . '">Academic Year  ' . $year . '/' . (substr($year + 1, 2)) . '</option>';
                echo '<option value="Academic Year ' . ($year + 1) . '/' . (substr($year + 2, 2)) . '">Academic Year ' . ($year + 1) . '/' . (substr($year + 2, 2)) . '</option>';
                echo '</select>';
                echo '<input type="submit" value="Submit Changes">';
                ?>
                <button type="submit" formaction="/home/student/placement/delete/">Delete Student</button>
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>