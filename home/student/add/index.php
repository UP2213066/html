<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the input that is required
    to add a new student to the database.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
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
                echo "<a href='../'>Back</a>";
            } 
        ?>
    </nav>
    <main>
        <h1>Add Student</h1>
            <form action="add.php" method="post">
                <label for="fname">First Name:</label>
                <?php echo '<input type="text" id="fname" name="fname" value="' . $fname . '">' ?>
                <label for="lname">Last Name:</label>
                <?php echo '<input type="text" id="lname" name="lname" value="' . $lname . '">' ?>
                <label for="id">Student Number:</label>
                <?php echo '<input type="text" id="id" name="id" value="' . $id . '">' ?>
                <label for="course">Course Code:</label>
                <?php echo '<input type="text" id="course" name="course" value="' . $course . '">' ?>
                <label for="module">Module Code:</label>
                <?php echo '<input type="text" id="module" name="module" value="' . $module . '">' ?>
                <input type="submit" value="Submit">
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>