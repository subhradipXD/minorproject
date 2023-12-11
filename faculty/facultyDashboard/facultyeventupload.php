<?php
require '../../connect.php';

// Check if the faculty is logged in with the sessions
if (!isset($_SESSION['fid']) || !isset($_SESSION['instid'])) {
    header("Location: ../facultylogin.php");
    exit;
}

$faculty_id = $_SESSION['fid'];
$inst_id = $_SESSION['instid'];

// Fetch department names for the dropdown
$dept_query = "SELECT DISTINCT fdept FROM faculty WHERE instid = ?";
$dept_stmt = $conn->prepare($dept_query);
$dept_stmt->bind_param("s", $inst_id);
$dept_stmt->execute();
$dept_result = $dept_stmt->get_result();

// Fetch faculty names for the dropdown
$faculty_query = "SELECT fname, fid FROM faculty WHERE instid = ?";
$faculty_stmt = $conn->prepare($faculty_query);
$faculty_stmt->bind_param("s", $inst_id);
$faculty_stmt->execute();
$faculty_result = $faculty_stmt->get_result();

// Handle the submission of event details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected department and faculty name from the form
    $selectedDepartment = $_POST["faculty_department"];
    $selectedFaculty = $_POST["faculty_name"];

    $eventname = $_POST["eventname"];
    $eventyear = $_POST["eventyear"];
    $scheme = $_POST["scheme"];
    $c_a_n_govt = $_POST["c_a_n_govt"];
    $c_a_n_govt_contact = $_POST["c_a_n_govt_contact"];
    $c_a_ind = $_POST["c_a_ind"];
    $c_a_ind_contact = $_POST["c_a_ind_contact"];
    $c_a_ngo = $_POST["c_a_ngo"];
    $c_a_ngo_contact = $_POST["c_a_ngo_contact"];
    $avgstu = $_POST["avgstu"];
    $addinfo = $_POST["addinfo"];

    // File upload paths
    $imagePaths = [];
    $reportPaths = [];

    // Handle image uploads
    if (isset($_FILES['images'])) {
        $imageCount = count($_FILES['images']['name']);
        for ($i = 0; $i < $imageCount; $i++) {
            $imageName = $_FILES['images']['name'][$i];
            $imagePath = "uploads/" . $imageName;
            move_uploaded_file($_FILES['images']['tmp_name'][$i], $imagePath);
            $imagePaths[] = $imagePath;
        }
    }

    // Handle PDF report uploads
    if (isset($_FILES['reports'])) {
        $reportCount = count($_FILES['reports']['name']);
        for ($i = 0; $i < $reportCount; $i++) {
            $reportName = $_FILES['reports']['name'][$i];
            $reportPath = "uploads/" . $reportName;
            move_uploaded_file($_FILES['reports']['tmp_name'][$i], $reportPath);
            $reportPaths[] = $reportPath;
        }
    }

    // Generate a random 4-digit number
    $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    // Get the first 4 letters of the event name
    $eventnameFirst4Letters = substr(strtoupper($eventname), 0, 4);

    // Concatenate the first 4 letters and the random number to create the event ID
    $eventID = $eventnameFirst4Letters . $randomNumber;

    // Insert data into the "event" table, including the event ID
    $sql = "INSERT INTO event (instid, deptid, fid, eventid, eventname, eventyear, scheme, c_a_n_govt, c_a_n_govt_contact, c_a_ind, c_a_ind_contact, c_a_ngo, c_a_ngo_contact, avgstu, addinfo, images, reports) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $imagePathsStr = implode(',', $imagePaths);
    $reportPathsStr = implode(',', $reportPaths);

    $stmt->bind_param("sssssssssssssssss", $inst_id, $selectedDepartment, $selectedFaculty, $eventID, $eventname, $eventyear, $scheme, $c_a_n_govt, $c_a_n_govt_contact, $c_a_ind, $c_a_ind_contact, $c_a_ngo, $c_a_ngo_contact, $avgstu, $addinfo, $imagePathsStr, $reportPathsStr);



    if ($stmt->execute()) {
        echo "Event details have been successfully submitted.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Retrieve and display all event details
$sql = "SELECT * FROM event WHERE instid = ?";
$result = $conn->prepare($sql);
$result->bind_param("s", $inst_id);
$result->execute();
$event_result = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Details Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #88c8f7;
            /* Specific blue shade */
        }

        .container {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control,
        .form-select {
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
        <h2 class="text-center text-primary">Event Details Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <!-- Form groups for inputs -->
            <div class="form-group">
                <label for="eventname">Event Name:</label>
                <input type="text" name="eventname" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="eventyear">Event Year:</label>
                <input type="text" name="eventyear" class="form-control" required>
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
                <label for="scheme">Scheme:</label>
                <textarea name="scheme" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="c_a_n_govt">Collaborated Non-Government Agency:</label>
                <input type="text" name="c_a_n_govt" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="c_a_n_govt_contact">Non-Government Agency Contact Details:</label>
                <input type="text" name="c_a_n_govt_contact" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="c_a_ind">Collaborated Industry:</label>
                <input type="text" name="c_a_ind" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="c_a_ind_contact">Industry Contact Details:</label>
                <input type="text" name="c_a_ind_contact" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="c_a_ngo">Collaborated NGOs:</label>
                <input type="text" name="c_a_ngo" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="c_a_ngo_contact">NGOs Contact Details:</label>
                <input type="text" name="c_a_ngo_contact" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="avgstu">Number of Students Participated:</label>
                <input type="number" name="avgstu" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="addinfo">Additional Information:</label>
                <textarea name="addinfo" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="images[]">Upload Event Images:</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            </div>

            <div class="form-group">
                <label for="reports[]">Upload PDF Reports:</label>
                <input type="file" name="reports[]" class="form-control" accept=".pdf" multiple required>
            </div>

            <input type="submit" class="btn btn-primary" value="Submit">
        </form>

        <h2 class="text-center text-primary">Event Details</h2>
        <?php if ($event_result->num_rows > 0) : ?>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Event Name</th>
                        <th>Event Year</th>
                        <th>Department</th>
                        <th>Faculty Name</th>
                        <th>Scheme</th>
                        <th>Collaborated NGOs</th>
                        <th>Number of Students Participated</th>
                        <th>Additional Information</th>
                        <th>Images</th>
                        <th>Reports</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $event_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row["eventname"]; ?></td>
                            <td><?php echo $row["eventyear"]; ?></td>
                            <td><?php echo $row["deptid"]; ?></td>
                            <td><?php echo $row["fid"]; ?></td>
                            <td><?php echo $row["scheme"]; ?></td>
                            <td><?php echo $row["c_a_ngo"]; ?></td>
                            <td><?php echo $row["avgstu"]; ?></td>
                            <td><?php echo $row["addinfo"]; ?></td>
                            <td>
                                <?php
                                $images = explode(',', $row["images"]);
                                foreach ($images as $image) {
                                    echo "<a href='$image' target='_blank'>View Image</a><br>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $reports = explode(',', $row["reports"]);
                                foreach ($reports as $report) {
                                    echo "<a href='$report' target='_blank'>View Report</a><br>";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">No event details are available.</p>
        <?php endif; ?>

        <!-- Bootstrap Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>