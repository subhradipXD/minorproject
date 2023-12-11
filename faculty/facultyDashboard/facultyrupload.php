<?php
require '../../connect.php';

// Check if the faculty is logged in with the sessions
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

// Handle the submission of new research work
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected department and faculty name from the form
    $selectedDepartment = $_POST["faculty_department"];
    $selectedFaculty = $_POST["faculty_name"];

    // Generate a project ID
    $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $proname = $_POST["proname"];
    $proid = $proname . $randomNumber;

    $picopi = $_POST["picopi"];
    $fundagent = $_POST["fundagent"];
    $award = $_POST["award"];
    $duration = $_POST["duration"];
    $addinfo = $_POST["addinfo"];

    // File upload paths
    $ecopy = "uploads/" . basename($_FILES["ecopy"]["name"]);
    $fundstatement = "uploads/" . basename($_FILES["fundstatement"]["name"]);
    $activereport = "uploads/" . basename($_FILES["activereport"]["name"]);

    // Move uploaded files to the "uploads" directory
    move_uploaded_file($_FILES["ecopy"]["tmp_name"], $ecopy);
    move_uploaded_file($_FILES["fundstatement"]["tmp_name"], $fundstatement);
    move_uploaded_file($_FILES["activereport"]["tmp_name"], $activereport);

    // Insert data into the "researchwork" table
    $sql = "INSERT INTO researchwork (proid, instid, deptname, fid, proname, picopi, fundagent, award, duration, ecopy, fundstatement, activereport, addinfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssiissss", $proid, $inst_id, $selectedDepartment, $selectedFaculty, $proname, $picopi, $fundagent, $award, $duration, $ecopy, $fundstatement, $activereport, $addinfo);

    if ($stmt->execute()) {
        echo "Research paper details have been successfully submitted.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Retrieve and display all research work projects
$sql = "SELECT * FROM researchwork WHERE instid = ?";
$result = $conn->prepare($sql);
$result->bind_param("s", $inst_id);
$result->execute();
$researchwork_result = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Research Paper Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #88c8f7;
            /* Light blue background */
        }

        .container {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .table {
            margin-top: 20px;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 3;
            top: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.5);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            padding: 10px 15px;
            border: none;
        }

        .openbtn:hover {
            background-color: #88c8f7;
        }

        .navbar {
            z-index: 0;
            height: 50px;
        }

        .btn {
            margin: 5px;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidebar {
                padding-top: 15px;
            }

            .sidebar a {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="../../_cc75286c-bd8d-4890-8939-6e30c7565dfa.jpg" alt="UNI-RECORD" class="img-fluid mr-2" width=40px>
                UNI-RECORD
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">Button</span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../WSPage/aboutus.html">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../WSPage/contactus.html">Contact Us</a>
                    </li>
                    <!-- Add more navigation links as needed -->

                    <!-- Sidebar Toggle Button -->
                    <li class="nav-item">
                        <button class="nav-link btn btn-outline-primary openbtn" id="toggle-btn" onclick="openNav()">&#9776;</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <button class="btn btn-primary" onclick="location.href='../Admin/adminreg.php'">New Registration for Institution</button>
        <button class="btn btn-primary" onclick="location.href='../Admin/adminlogin.php'">Login as Institution's Admin</button>
        <button class="btn btn-primary" onclick="location.href='../WSPage/helpdesk.html'">Help Desk</button>
        <!-- Logout Button -->
        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

    </div>


    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>

    <br>
    <br>

    <div class="container">
        <h2 class="text-center text-primary">Research Paper Details Form</h2>
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="proname">Name of Project:</label>
                <input type="text" name="proname" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="picopi">Name of Project Instructor:</label>
                <input type="text" name="picopi" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="faculty_department">Select Department:</label>
                <select name="faculty_department" class="form-select" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php
                    while ($dept_row = $dept_result->fetch_assoc()) {
                        echo '<option value="' . $dept_row['fdept'] . '">' . $dept_row['fdept'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Additional form groups for other inputs -->
            <div class="form-group">
                <label for="faculty_name">Select Faculty Name:</label>
                <select name="faculty_name" class="form-select" required>
                    <option value="" disabled selected>Select Faculty Name</option>
                    <?php
                    while ($faculty_row = $faculty_result->fetch_assoc()) {
                        echo '<option value="' . $faculty_row['fid'] . '">' . $faculty_row['fname'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fundagent">Funding Agency:</label>
                <input type="text" name="fundagent" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="award">Year of Award:</label>
                <input type="number" name="award" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="duration">Duration of Project (in months):</label>
                <input type="number" name="duration" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="ecopy">E-Copy (PDF):</label>
                <input type="file" name="ecopy" class="form-control" accept=".pdf" required>
            </div>

            <div class="form-group">
                <label for="fundstatement">Fund Release Statement (PDF):</label>
                <input type="file" name="fundstatement" class="form-control" accept=".pdf" required>
            </div>

            <div class="form-group">
                <label for="activereport">Activity Report (PDF):</label>
                <input type="file" name="activereport" class="form-control" accept=".pdf" required>
            </div>

            <div class="form-group">
                <label for="addinfo">Additional Information:</label>
                <textarea name="addinfo" class="form-control" rows="4" required></textarea>
            </div>


            <input type="submit" class="btn btn-primary" value="Submit">
        </form>

        <h2 class="text-center text-primary">Research Work Details</h2>
        <?php if ($researchwork_result->num_rows > 0) : ?>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Name of Project</th>
                        <th>Name of Project Instructor</th>
                        <th>Funding Agency</th>
                        <th>Year of Award</th>
                        <th>Duration of Project</th>
                        <th>E-Copy</th>
                        <th>Fund Release Statement</th>
                        <th>Activity Report</th>
                        <th>Additional Information</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $researchwork_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row["proname"]; ?></td>
                            <td><?php echo $row["picopi"]; ?></td>
                            <td><?php echo $row["fundagent"]; ?></td>
                            <td><?php echo $row["award"]; ?></td>
                            <td><?php echo $row["duration"]; ?></td>
                            <td>
                                <?php
                                $ecopy = $row["ecopy"];
                                echo "<a href='$ecopy' target='_blank'>View E-Copy</a>";
                                ?>
                            </td>
                            <td>
                                <?php
                                $fundstatement = $row["fundstatement"];
                                echo "<a href='$fundstatement' target='_blank'>View Fund Release Statement</a>";
                                ?>
                            </td>
                            <td>
                                <?php
                                $activereport = $row["activereport"];
                                echo "<a href='$activereport' target='_blank'>View Activity Report</a>";
                                ?>
                            </td>
                            <td><?php echo $row["addinfo"]; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">No research work projects are available.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>