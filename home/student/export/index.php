<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '/var/www/html/validate.php';
require '/var/www/vendor/autoload.php';

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();
foreach (range('A', 'Z') as $col) {
    $worksheet->getColumnDimension($col)->setWidth(20);
}
$connection = new mysqli($hostname, $read_student_username, $read_student_password, $database);
$preparedSQL = $connection->prepare("SELECT id, firstName, lastName, courseCode, moduleCode, supervisor, supervisorEmail, moderator, moderatorEmail FROM students");
$preparedSQL->execute();
$result = $preparedSQL->get_result();
if ($result->num_rows > 0) {
    $worksheet->setCellValue('A1', 'Student ID');
    $worksheet->setCellValue('B1', 'First Name');
    $worksheet->setCellValue('C1', 'Last Name');
    $worksheet->setCellValue('D1', 'Course Code');
    $worksheet->setCellValue('E1', 'Module Code');
    $worksheet->setCellValue('F1', 'Supervisor');
    $worksheet->setCellValue('G1', 'Supervisor Email');
    $worksheet->setCellValue('H1', 'Moderator');
    $worksheet->setCellValue('I1', 'Moderator Email');
    $worksheet->getStyle('A1:I' . ($result->num_rows+1))
    ->getAlignment()
    ->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    ->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $supervisor = $row['supervisor'] ?? "NO SUPERVISOR";
        $moderator = $row['moderator'] ?? "NO MODERATOR";
        $worksheet->setCellValue('A' . $rowNumber, $row['id']);
        $worksheet->setCellValue('B' . $rowNumber, $row['firstName']);
        $worksheet->setCellValue('C' . $rowNumber, $row['lastName']);
        $worksheet->setCellValue('D' . $rowNumber, $row['courseCode']);
        $worksheet->setCellValue('E' . $rowNumber, $row['moduleCode']);
        $worksheet->setCellValue('F' . $rowNumber, $supervisor);
        $worksheet->setCellValue('G' . $rowNumber, $moderator);
        $rowNumber++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=StudentExport.xlsx");
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save('php://output');
echo "Download complete.";
?>