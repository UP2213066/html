<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for querying the database with the staff
    member data to pull up their profile for editing.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';
if ($_POST['searchType'] === "name") {
    $name = $_POST['firstName'] . ' ' . $_POST['lastName'];
    try {
        $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
    } catch (mysqli_sql_exception $e) {
        echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
        exit();
    }
    $preparedSQL = $connection->prepare("SELECT name, email, role, quota, allocatedStudents, studentsToAvoid FROM staff WHERE name=?");
    $preparedSQL->bind_param("s", $name);
    $preparedSQL->execute();
    $result = $preparedSQL->get_result();
    if ($result->num_rows > 0) {
        unset($_SESSION['search-error']);
        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $email = $row['email'];
            $_SESSION['nameToUpdate'] = $name;
            $_SESSION['emailToUpdate'] = $email;
            $role = $row['role'];
            $quota = $row['quota'];
            $allocatedStudents = $row['allocatedStudents'];
            $studentsToAvoid = $row['studentsToAvoid'];
        }
    } else {
        $_SESSION['search-error'] = "Staff Member Not Found";
        header("Location: /home/staff/");
        die();
    }
} elseif ($_POST['searchType'] === "email" || isset($_GET['email'])) {
    if (isset($_GET['email'])) {
        $_POST['email'] = $_GET['email'];
    }
    try {
        $connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
    } catch (mysqli_sql_exception $e) {
        echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
        exit();
    }
    $preparedSQL = $connection->prepare("SELECT name, email, role, quota, allocatedStudents, studentsToAvoid FROM staff WHERE email=?");
    $preparedSQL->bind_param("s", $_POST['email']);
    $preparedSQL->execute();
    $result = $preparedSQL->get_result();
    if ($result->num_rows > 0) {
        unset($_SESSION['search-error']);
        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $email = $row['email'];
            $_SESSION['nameToUpdate'] = $name;
            $_SESSION['emailToUpdate'] = $email;
            $role = $row['role'];
            $quota = $row['quota'];
            $allocatedStudents = $row['allocatedStudents'];
            $studentsToAvoid = $row['studentsToAvoid'];
        }
    } else {
        $_SESSION['search-error'] = "Staff Member Not Found";
        header("Location: /home/staff/");
        die();
    }
} else {
    $_SESSION['search-error'] = "Staff Member Not Found";
    header("Location: /home/staff/");
    die();
}
$allocatedStudents = [];
try {
    $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
} catch (mysqli_sql_exception $e) {
    echo "<p>Something went wrong while processing your request. Please refresh the page or try again later.</p>";
    exit();
}
$preparedSQL = $connection->prepare("SELECT id FROM students WHERE supervisorEmail=?");
$preparedSQL->bind_param("s", $email);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allocatedStudents[] = $row['id'];
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FYP Staff Editor</title>
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
                echo "<a href='./'>Back</a>";
            } 
        ?>
    </nav>
    <main>
        <?php echo "<h1>" . $name . "</h1>" ?>
            <form action="/home/staff/update.php" method="post">
                <label for="name">Name:</label>
                <?php echo '<input type="text" id="name" name="name" value="' . $name . '">' ?>
                <label for="email">Email:</label>
                <?php echo '<input type="text" id="email" name="email" value="' . $email . '">' ?>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="Supervisor/Moderator">Supervisor/Moderator</option>
                    <option value="Admin">Admin</option>
                </select>
                <label for="role">Quota:</label>
                <?php echo '<input type="text" id="quota" name="quota" value="' . $quota . '">' ?>
                <?php 
                echo '<p id="allocatedStudents" name="allocatedStudents">Allocated: ' . count($allocatedStudents) . '</p>'; 
                if (count($allocatedStudents) >= $quota) {
                    echo '<p>QUOTA MET</p>';
                } 
                ?>
                <label for="studentsToAvoid">Students to Avoid (Comma Separated IDs):</label>
                <?php echo '<input type="text" id="studentsToAvoid" name="studentsToAvoid" value="' . $studentsToAvoid . '">' ?>
                <input type="submit" value="Submit">
                <button type="submit" formaction="/home/staff/delete/">Delete</button>
                <?php
                if (!empty($allocatedStudents)) {
                    echo "<p>Allocated Students:</p>";
                    foreach ($allocatedStudents as $student) {
                        echo "<a href='/home/student/search/search.php?id=$student&redirect=staff_all'>UP$student</a>";
                    }
                }
                ?>
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>