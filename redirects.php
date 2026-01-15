<?php
$redirects = ["student_all" => "/home/students/all/"]

if (isset($_GET['redirect'])) {
    $redirect = $redirects[$_GET['redirect']];
    if ($redirect != "") {
        echo "<a href='$redirect'>Back</a>";
    } else {
        echo "<a href='../''>Back</a>";
    }
} else {
    echo "<a href='../''>Back</a>";
}
?>