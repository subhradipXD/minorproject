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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        /* Add your custom styles here */
        body {
            background-color: #88c8f7;
            color: #000;
            padding: 20px;
        }

        header {
            background-color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        nav li {
            display: inline;
            margin-right: 10px;
        }

        #eventlists {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        #eventlists li {
            margin-bottom: 10px;
        }
    </style>
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

    <div class="container mt-5">
        <h2>Welcome to the Faculty Dashboard</h2>

        <ul id="eventlists">
            <li><a class="homeblack" href="facultyrupload.php">Faculty Research Work</a></li>
            <li><a class="homeblack" href="facultyeventupload.php">Event Details Work</a></li>
        </ul>
    </div>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
