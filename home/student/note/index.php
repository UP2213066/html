<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database of student notes
    to pull all their notes for viewing and editing.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
if (isset($_GET['id'])) {
    $_SESSION['noteIDToDelete'] = $_GET['id'];
    try {
        $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
    } catch (mysqli_sql_exception $e) {
        echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
        exit();
    }
    $preparedSQL = $connection->prepare("SELECT note FROM notes WHERE id=?");
    $preparedSQL->bind_param("s", $_GET['id']);
    $preparedSQL->execute();
    $result = $preparedSQL->get_result();
    if ($result->num_rows > 0) {
        unset($_SESSION['search-error']);
        while ($row = $result->fetch_assoc()) {
            $note = $row['note'];
        }
    } else {
        header("Location: /home/student/");
        die();
    }
} else {
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
            <?php echo "<h1>Student Note</h1>" ?>
            <form action="/home/student/note/update/" method="post">
                <label for="note">Note Text:</label>
                <?php echo '<input type="text" id="note" name="note" value="' . $note . '">' ?>
                <input type="submit" value="Submit">
                <button type="submit" formaction="/home/student/note/delete/">Delete</button>
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>