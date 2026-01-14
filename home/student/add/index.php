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