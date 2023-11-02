<?php
require '../../connect.php';

if (!isset($_SESSION['fid']) || !isset($_SESSION['instid'])) {
    header("Location: ../facultylogin.php");
    exit;
}

$faculty_id = $_SESSION['fid'];
$inst_id = $_SESSION['instid'];

// Fetch department names for the first dropdown
$dept_query = "SELECT DISTINCT fdept FROM faculty WHERE instid = ?";
$dept_stmt = $conn->prepare($dept_query);
$dept_stmt->bind_param("s", $inst_id);
$dept_stmt->execute();
$dept_result = $dept_stmt->get_result();

// Fetch faculty names for the second dropdown
$faculty_query = "SELECT fname, fid FROM faculty WHERE instid = ?";
$faculty_stmt = $conn->prepare($faculty_query);
$faculty_stmt->bind_param("s", $inst_id);
$faculty_stmt->execute();
$faculty_result = $faculty_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" type="text/css" href="facultydashboard.css">
</head>

<body>
    <header>
        <nav>
            <h1>College Accreditation</h1>
            <ul id="navli">
                <li><a class="homered" href="facultydashboard.php">Faculty Dashboard</a></li>
                <li><a class="homeblack" href="facultyprofile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    

    <ul id="eventlists">
        <li><a class="homeblack" href="facultyrupload.php">Faculty Research Work</a></li>
        <li><a class="homeblack" href="facultyeventupload.php">Event Details Work</a></li>
    </ul>
</div>

</body>

</html>
