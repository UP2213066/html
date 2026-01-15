<?php
$redirects = ["student_all" => "/home/students/all/"];

if (isset($_GET['redirect']) && isset($redirects[$_GET['redirect']])) {
    $redirect = $redirects[$_GET['redirect']];
    echo "<a href='$redirect'>Back</a>";
} else {
    echo "<a href='../'>Back</a>";
}
?>