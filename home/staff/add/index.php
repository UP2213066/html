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
                <?php echo '<input type="text" id="name" name="name" value="' . $name . '">' ?>
                <label for="email">Email:</label>
                <?php echo '<input type="text" id="email" name="email" value="' . $email . '">' ?>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="Admin">Admin</option>
                    <option value="Supervisor/Moderator">Supervisor/Moderator</option>
                </select>
                <label for="role">Quota:</label>
                <?php echo '<input type="text" id="quota" name="quota" value="' . $quota . '">' ?>
                <input type="submit" value="Submit">
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>