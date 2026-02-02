<?php
include '/var/www/html/validate.php';

if (!isset($_GET['course'])) {
    echo json_encode([]);
    exit;
}

$course = $_GET['course'];

$connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
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
