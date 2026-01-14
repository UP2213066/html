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
        <h1>Add Note</h1>
            <form action="add.php" method="post">
                <label for="note">Note:</label>
                <?php echo '<input type="text" id="note" name="note" value="' . $note . '">' ?>
                <input type="submit" value="Submit">
            </form>
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>