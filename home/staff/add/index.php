<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsible for taking the input that is required
    to add a new staff member to the database.

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
        <h1>Add Staff Member</h1>
            <form action="add.php" method="post">
                <label for="name">Name:</label>
                <?php echo '<input type="text" id="name" name="name">' ?>
                <label for="email">Email:</label>
                <?php echo '<input type="text" id="email" name="email">' ?>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="Supervisor/Moderator">Supervisor/Moderator</option>
                    <option value="Admin">Admin</option>
                </select>
                <label for="role">Quota:</label>
                <?php echo '<input type="text" id="quota" name="quota" value="0">' ?>
                <input type="submit" value="Submit">
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>