<!-- 
    Project: Final Year Project Admin Web Application
    Author: Ayden Lunnon
    Student Number: UP2213066
    Course: BSc (hons) Cybersecurity and Forensic Computing, University of Portsmouth
    Year: 2025/26

    Description: This file is responsesible for pulling project modules from the database
    based on the course code sent via a GET request.

    © 2025-2026 Ayden Lunnon. All rights reserved.
    This code is submitted as part of a university project and may not be 
    reused or redistributed without permission.
-->
<?php
include '/var/www/html/validate.php';

if (!isset($_GET['course'])) {
    echo json_encode([]);
    exit;
}

$course = $_GET['course'];

$connection = new mysqli($hostname, $username, $password, $database);
$preparedSQL = $connection->prepare("SELECT moduleCode, moduleName FROM projects WHERE courseCode=?");
$preparedSQL->bind_param("s", $course);
$preparedSQL->execute();
$result = $preparedSQL->get_result();

$modules = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $modules[] = [
            "code" => trim($row['moduleCode']),
            "name" => trim($row['moduleName'])
        ];
    }
}

echo json_encode($modules);
?>
