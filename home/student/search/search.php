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
    $preparedSQL = $connection->prepare("SELECT id, firstName, lastName, courseCode, moduleCode, supervisor, supervisorEmail, moderator, moderatorEmail FROM students WHERE id=?");
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
                $supervisorEmail = $row['supervisorEmail'];
            } else {
                $supervisor = "";
                $supervisorEmail = "";
            }
            if (isset($row['moderator'])) {
                $moderator = $row['moderator'];
                $moderatorEmail = $row['moderatorEmail'];
            } else {
                $moderator = "";
                $moderatorEmail = "";
            }
        }
    } else {
        $_SESSION['search-error'] = "Student Not Found";
        header("Location: /home/student/search/");
        die();
    }
} else {
    $_SESSION['search-error'] = "Student Not Found";
    header("Location: /home/student/search/");
    die();
}
$connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
$preparedSQL = $connection->prepare("SELECT moduleCode, moduleName FROM projects WHERE courseCode=?");
$preparedSQL->bind_param("s", $course);
$preparedSQL->execute();
$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $moduleCodes[] = $row['moduleCode'];
        $moduleNames[] = $row['moduleName'];
    }
    foreach($moduleCodes as $moduleCode) {
        if (trim($moduleCode) == trim($module)) {
            $index = array_search($moduleCode, $moduleCodes);
            $currentModuleCode = $moduleCodes[$index];
            $currentModuleName = $moduleNames[$index];
            array_splice($moduleCodes, $index, 1);
            array_splice($moduleNames, $index, 1);
            break;
        }
    }
} 
$connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
$preparedSQL = $connection->prepare("SELECT courseCode, courseName FROM courses");
$preparedSQL->execute();
$result = $preparedSQL->get_result();
$courseFound = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courseCodes[] = $row['courseCode'];
        $courseNames[] = $row['courseName'];
    }
    foreach($courseCodes as $courseCode) {
        if (trim($courseCode) == trim($course)) {
            $courseFound = true;
            break;
        }
    }
}
if ($courseFound) {
    $index = array_search($course, $courseCodes);
    $currentCourseCode = $courseCodes[$index];
    $currentCourseName = $courseNames[$index];
    array_splice($courseCodes, $index, 1);
    array_splice($courseNames, $index, 1);
} else {
    $currentCourseCode = $course;
    $currentCourseName = "UNKNOWN COURSE";
}
$connection = new mysqli($hostname, $read_staff_username, $read_staff_password, $database);
$preparedSQL = $connection->prepare("SELECT name, email, quota, allocatedStudents, studentsToAvoid FROM staff");
$preparedSQL->execute();
$result = $preparedSQL->get_result();
$supervisorFound = false;
$moderatorFound = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffMembers[] = ["name" => $row['name'], "email" => $row['email'], "quota" => $row['quota'], "allocatedStudents" => explode(',', $row['allocatedStudents']), "studentsToAvoid" => explode(',', $row['studentsToAvoid'])];
    }
    foreach($staffMembers as $staff) {
        $index = array_search($staff, $staffMembers);
        if (trim($staff['email']) == trim($moderatorEmail)) {
            $moderatorFound = true;
            $currentModeratorEmail = $staffMembers[$index]["email"];
            $currentModeratorName = $staffMembers[$index]["name"];
            array_splice($staffMembers, $index, 1);
        } elseif (trim($staff['email']) == trim($supervisorEmail)) {
            $supervisorFound = true;
            $currentSupervisorEmail = $staffMembers[$index]["email"];
            $currentSupervisorName = $staffMembers[$index]["name"];
            array_splice($staffMembers, $index, 1);
        }
    }
}
if (!$supervisorFound) {
    $currentSupervisorEmail = "SUPERVISOR NOT FOUND";
    $currentSupervisorName = $supervisor;
}  
if (!$moderatorFound) {
    $currentModeratorEmail = "MODERATOR NOT FOUND";
    $currentModeratorName = $moderator;
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
            <form action="/home/student/update.php" method="post">
                <label for="firstName">First Name:</label>
                <?php echo '<input type="text" id="firstName" name="firstName" value="' . $firstName . '">' ?>
                <label for="lastName">Last Name:</label>
                <?php echo '<input type="text" id="lastName" name="lastName" value="' . $lastName . '">' ?>
                <label for="course">Course Code:</label>
                <select id="course" name="course">
                    <?php
                    $index = 0;
                    if ($courseCodes == []) {
                        echo "<option value='NONE'>NO MODULES FOUND</option>";
                    } else {
                        echo "<option value='$currentCourseCode'>$currentCourseCode - $currentCourseName</option>";
                        foreach($courseCodes as $courseCode) {
                            $name = $courseNames[$index];
                            echo "<option value='$courseCode'>$courseCode - $name</option>";
                            $index++;
                        }
                    }
                    ?>
                </select> 
                <label for="module">Module Code:</label>
                <select id="module" name="module">
                </select>
                <script>
                    const currentModule = "<?php echo $module; ?>";

                    function loadModulesForCourse() {
                        const course = document.getElementById("course").value;

                        fetch("/home/student/pullProjects.php?course=" + course)
                            .then(response => response.json())
                            .then(data => {
                                const moduleSelect = document.getElementById("module");
                                moduleSelect.innerHTML = "";

                                if (data.length === 0) {
                                    const noOpt = document.createElement("option");
                                    noOpt.value = "NONE";
                                    noOpt.textContent = "NO MODULES FOUND";
                                    moduleSelect.appendChild(noOpt);
                                    return;
                                }

                                data.forEach(mod => {
                                    const opt = document.createElement("option");
                                    opt.value = mod.code;
                                    opt.textContent = `${mod.code} - ${mod.name}`;

                                    if (mod.code === currentModule) {
                                        opt.selected = true;
                                    }

                                    moduleSelect.appendChild(opt);
                                });
                            });
                    }

                    document.getElementById("course").addEventListener("change", loadModulesForCourse);

                    window.addEventListener("DOMContentLoaded", loadModulesForCourse);
                </script>
                <label for="supervisor">Supervisor:</label>
                <select id="supervisor" name="supervisor">
                    <?php
                    $index = 0;
                    if ($staffMembers == []) {
                        echo "<option value='NONE'>NO SUPERVISORS FOUND</option>";
                    } else {
                        if ($supervisorFound) {
                            echo "<option value='$currentSupervisorEmail'>$currentSupervisorName - $currentSupervisorEmail</option>";
                        }
                        foreach($staffMembers as $staff) {
                            $name = $staff["name"];
                            $email = $staff["email"];
                            echo "<option value='$email'>$name - $email</option>";
                        }
                        echo "<option value=''>Not Selected</option>";
                    }
                    ?>
                </select>
                <label for="moderator">Moderator:</label>
                <select id="moderator" name="moderator">
                    <?php
                    $index = 0;
                    if ($staffMembers == []) {
                        echo "<option value='NONE'>NO MODERATORS FOUND</option>";
                    } else {
                        if ($moderatorFound) {
                            echo "<option value='$currentModeratorEmail'>$currentModeratorName - $currentModeratorEmail</option>";
                        }
                        foreach($staffMembers as $staff) {
                            $name = $staff["name"];
                            $email = $staff["email"];
                            echo "<option value='$email'>$name - $email</option>";
                        }
                        echo "<option value=''>Not Selected</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Submit Changes">
                <button type="submit" formaction="/home/student/delete/">Delete Student</button>
            </form>
            <?php
                $connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
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
            <br>
            <form>
                <button type="submit" formaction="/home/student/note/add/">Add Note</button>
            </form>
            
        </main>
    </body>
    <?php include '/var/www/html/footer.php';?>
</html>