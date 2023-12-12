<?php
require '../connect.php'; // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Platform</title>
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

        .btn {
            margin: 5px;
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
                <img src="../_cc75286c-bd8d-4890-8939-6e30c7565dfa.jpg" alt="UNI-RECORD" class="img-fluid mr-2" width=40px>
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
                        <a class="nav-link active" href="#">Home</a>
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
        <button class="btn btn-primary" onclick="location.href='../Faculty/facultylogin.php'">Login as Institution's Faculty</button>
        <button class="btn btn-primary" onclick="location.href='../WSPage/helpdesk.html'">Help Desk</button>
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
    <br>

    <div class="container">
        <h2 class="text-center text-primary">Welcome to the UNI-RECORD</h2>
        <br>

        <!-- Create a search form -->
        <form class="d-flex justify-content-center" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="keywords" class="form-control" placeholder="Search in Uni-Record..." aria-label="Search">
            <input type="submit" class="btn btn-primary" value="Search">
        </form>

        <?php
        if (isset($_GET['keywords'])) {
            $keywords = $_GET['keywords'];

            // Initialize arrays to store search results
            $institutionResults = [];
            $researchWorkResults = [];
            $eventResults = [];

            // Search in the faculty table for faculty names
            $facultyQuery = "SELECT * FROM researchwork WHERE fid IN (SELECT fid FROM faculty WHERE fname LIKE ? );";
            $facultyStmt = $conn->prepare($facultyQuery);
            $facultyStmt->bind_param("s", $searchKeyword);
            $facultyStmt->execute();
            $facultyResult = $facultyStmt->get_result();

            while ($row = $facultyResult->fetch_assoc()) {
                $fid = $row['fid'];
                // Store the faculty results
                $facultyResults[] = $fid;
            }


            // Search in the admin table for institution names
            $adminQuery = "SELECT instid FROM admin WHERE instname LIKE ? ";
            $adminStmt = $conn->prepare($adminQuery);
            $searchKeyword = '%' . $keywords . '%';
            $adminStmt->bind_param("s", $searchKeyword);
            $adminStmt->execute();
            $adminResult = $adminStmt->get_result();

            while ($row = $adminResult->fetch_assoc()) {
                $instid = $row['instid'];
                // Store the institution results
                $institutionResults[] = $instid;
            }

            
            // Search in the researchwork table for project names and additional info
            $researchWorkQuery = "SELECT proid FROM researchwork WHERE proname LIKE ? OR addinfo LIKE ? or picopi like ?";
            $researchWorkStmt = $conn->prepare($researchWorkQuery);
            $researchWorkStmt->bind_param("sss", $searchKeyword, $searchKeyword, $searchKeyword);
            $researchWorkStmt->execute();
            $researchWorkResult = $researchWorkStmt->get_result();

            while ($row = $researchWorkResult->fetch_assoc()) {
                $proid = $row['proid'];
                // Store the research work results
                $researchWorkResults[] = $proid;
            }

            // Search in the event table for event names, schemes, and additional info
            $eventQuery = "SELECT eventid FROM event WHERE eventname LIKE ? OR scheme LIKE ? OR addinfo LIKE ?";
            $eventStmt = $conn->prepare($eventQuery);
            $eventStmt->bind_param("sss", $searchKeyword, $searchKeyword, $searchKeyword);
            $eventStmt->execute();
            $eventResult = $eventStmt->get_result();

            while ($row = $eventResult->fetch_assoc()) {
                $eventid = $row['eventid'];
                // Store the event results
                $eventResults[] = $eventid;
            }

            //---------------------------------------------------------------
            // Search in the researchwork table for projects with the specified year
            $researchWorkQuery = "SELECT proid FROM researchwork WHERE award = ?";
            $researchWorkStmt = $conn->prepare($researchWorkQuery);
            $researchWorkStmt->bind_param("s", $searchKeyword);
            $researchWorkStmt->execute();
            $researchWorkResult = $researchWorkStmt->get_result();

            // Initialize an array to store proids found in research work
            $researchWorkProids = [];

            while ($row = $researchWorkResult->fetch_assoc()) {
                $researchWorkProids[] = $row['proid'];
            }

            // Search in the event table for events with the specified year
            $eventQuery = "SELECT eventid FROM event WHERE eventyear = ?";
            $eventStmt = $conn->prepare($eventQuery);
            $eventStmt->bind_param("s", $searchKeyword);
            $eventStmt->execute();
            $eventResult = $eventStmt->get_result();

            // Initialize an array to store eventids found in events
            $eventEventids = [];

            while ($row = $eventResult->fetch_assoc()) {
                $eventEventids[] = $row['eventid'];
            }

            // Display search results
            if (!empty($institutionResults)) {
                // If institution results are found, show related research work and event details
                foreach ($institutionResults as $instid) {
                    // Display related research work
                    displayResearchWorkDetails($instid);

                    // Display related events
                    displayEventDetails($instid);
                }
            }

            if (!empty($facultyResults)) {
                // If faculty results are found, show related research work and event details
                echo "<h4 class='text-primary'>Faculty:</h4>";
                foreach ($facultyResults as $fid) {
                    // Display related research work
                    displayResearchWorkDetailsByFaculty($fid);
                    // Display related events
                    displayEventDetailsByFaculty($fid);
                }
            }

            if (!empty($researchWorkResults)) {
                // If research work results are found, display them
                echo "<h4 class='text-primary'>Research Works:</h4>";
                foreach ($researchWorkResults as $proid) {
                    displayResearchWorkDetailsById($proid);
                }
            }

            if (!empty($eventResults)) {
                // If event results are found, display them
                echo "<h4 class='text-primary'>Events:</h4>";
                foreach ($eventResults as $eventid) {
                    displayEventDetailsById($eventid);
                }
            }

            if (empty($institutionResults) && empty($researchWorkResults) && empty($eventResults) && empty($facultyResults)) {
                echo "<p class='text-center'>No results found.</p>";
            }
        }

        // Function to display related research work details by institution ID
        function displayResearchWorkDetails($instid)
        {
            global $conn;

            $instNameQuery = "SELECT instname FROM admin WHERE instid = ?";
            $instNameStmt = $conn->prepare($instNameQuery);
            $instNameStmt->bind_param("s", $instid);
            $instNameStmt->execute();
            $instNameResult = $instNameStmt->get_result();
            $instNameRow = $instNameResult->fetch_assoc();
            $instName = $instNameRow['instname'];

            $researchWorkQuery = "SELECT * FROM researchwork WHERE instid = ?";
            $researchWorkStmt = $conn->prepare($researchWorkQuery);
            $researchWorkStmt->bind_param("s", $instid);
            $researchWorkStmt->execute();
            $researchWorkResult = $researchWorkStmt->get_result();

            if ($researchWorkResult->num_rows > 0) {
                echo "<h5 class='text-secondary'>Research Works in $instName:</h5>";
                echo "<ul>";
                while ($row = $researchWorkResult->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>Project Name:</strong> <a href='details.php?proid={$row['proid']}&eventid=null'>" . $row['proname'] . "</a><br>";
                    echo "<strong>Funded by:</strong> " . $row['fundagent'] . "<br>";
                    echo "<strong>Award:</strong> " . $row['award'] . "<br>";
                    // Add more fields as needed
                    echo "</li>";
                }
                echo "</ul>";
            }
        }

        // Function to display related event details by institution ID
        function displayEventDetails($instid)
        {
            global $conn;

            $instNameQuery = "SELECT instname FROM admin WHERE instid = ?";
            $instNameStmt = $conn->prepare($instNameQuery);
            $instNameStmt->bind_param("s", $instid);
            $instNameStmt->execute();
            $instNameResult = $instNameStmt->get_result();
            $instNameRow = $instNameResult->fetch_assoc();
            $instName = $instNameRow['instname'];

            $eventQuery = "SELECT * FROM event WHERE instid = ?";
            $eventStmt = $conn->prepare($eventQuery);
            $eventStmt->bind_param("s", $instid);
            $eventStmt->execute();
            $eventResult = $eventStmt->get_result();

            if ($eventResult->num_rows > 0) {
                echo "<h5 class='text-secondary'>Events in $instName:</h5>";
                echo "<ul>";
                while ($row = $eventResult->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>Event Name:</strong> <a href='details.php?proid=null&eventid={$row['eventid']}'>" . $row['eventname'] . "</a><br>";
                    echo "<strong>Scheme:</strong> " . $row['scheme'] . "<br>";
                    echo "<strong>Additional Information:</strong> " . $row['addinfo'] . "<br>";
                    // Add more fields as needed
                    echo "</li>";
                }
                echo "</ul>";
            }
        }

        // Function to display related research work details by faculty ID
        function displayResearchWorkDetailsByFaculty($fid)
        {
            global $conn;

            $facultyNameQuery = "SELECT * FROM event WHERE fid = ?";
            $facultyNameStmt = $conn->prepare($facultyNameQuery);
            $facultyNameStmt->bind_param("s", $fid);
            $facultyNameStmt->execute();
            $facultyNameResult = $facultyNameStmt->get_result();
            $facultyNameRow = $facultyNameResult->fetch_assoc();
            $facultyName = $facultyNameRow['fname'];

            $researchWorkQuery = "SELECT * FROM researchwork WHERE fid = ?";
            $researchWorkStmt = $conn->prepare($researchWorkQuery);
            $researchWorkStmt->bind_param("s", $fid);
            $researchWorkStmt->execute();
            $researchWorkResult = $researchWorkStmt->get_result();

            if ($researchWorkResult->num_rows > 0) {
                echo "<h5 class='text-secondary'>Research Works for Faculty $facultyName:</h5>";
                echo "<ul>";
                while ($row = $researchWorkResult->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>Project Name:</strong> <a href='details.php?proid={$row['proid']}&eventid=null'>" . $row['proname'] . "</a><br>";
                    echo "<strong>Funded by:</strong> " . $row['fundagent'] . "<br>";
                    echo "<strong>Award:</strong> " . $row['award'] . "<br>";
                    // Add more fields as needed
                    echo "</li>";
                }
                echo "</ul>";
            }
        }

        // Function to display related event details by faculty ID
        function displayEventDetailsByFaculty($fid)
        {
            global $conn;

            $facultyNameQuery = "SELECT fname FROM faculty WHERE fid = ?";
            $facultyNameStmt = $conn->prepare($facultyNameQuery);
            $facultyNameStmt->bind_param("s", $fid);
            $facultyNameStmt->execute();
            $facultyNameResult = $facultyNameStmt->get_result();
            $facultyNameRow = $facultyNameResult->fetch_assoc();
            $facultyName = $facultyNameRow['fname'];

            $eventQuery = "SELECT * FROM event WHERE fid = ?";
            $eventStmt = $conn->prepare($eventQuery);
            $eventStmt->bind_param("s", $fid);
            $eventStmt->execute();
            $eventResult = $eventStmt->get_result();

            if ($eventResult->num_rows > 0) {
                echo "<h5 class='text-secondary'>Events for Faculty $facultyName:</h5>";
                echo "<ul>";
                while ($row = $eventResult->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>Event Name:</strong> <a href='details.php?proid=null&eventid={$row['eventid']}'>" . $row['eventname'] . "</a><br>";
                    echo "<strong>Scheme:</strong> " . $row['scheme'] . "<br>";
                    echo "<strong>Additional Information:</strong> " . $row['addinfo'] . "<br>";
                    // Add more fields as needed
                    echo "</li>";
                }
                echo "</ul>";
            }
        }

        // Function to display research work details by project ID
        function displayResearchWorkDetailsById($proid)
        {
            global $conn;

            $researchWorkQuery = "SELECT * FROM researchwork WHERE proid = ?";
            $researchWorkStmt = $conn->prepare($researchWorkQuery);
            $researchWorkStmt->bind_param("s", $proid);
            $researchWorkStmt->execute();
            $researchWorkResult = $researchWorkStmt->get_result();

            if ($researchWorkResult->num_rows > 0) {
                echo "<h5 class='text-secondary'>Research Work Details for Project $proid:</h5>";
                while ($row = $researchWorkResult->fetch_assoc()) {
                    echo "<p>";
                    echo "<strong>Project Name:</strong> <a href='details.php?eventid=null&proid={$row['proid']}'>" . $row['proname'] . "</a><br>";
                    echo "<strong>Funded by:</strong> " . $row['fundagent'] . "<br>";
                    echo "<strong>Award:</strong> " . $row['award'] . "<br>";
                    // Add more fields as needed
                    echo "</p>";
                }
            }
        }

        // Function to display event details by event ID
        function displayEventDetailsById($eventid)
        {
            global $conn;

            $eventQuery = "SELECT * FROM event WHERE eventid = ?";
            $eventStmt = $conn->prepare($eventQuery);
            $eventStmt->bind_param("s", $eventid);
            $eventStmt->execute();
            $eventResult = $eventStmt->get_result();

            if ($eventResult->num_rows > 0) {
                echo "<h5 class='text-secondary'>Event Details for Event $eventid:</h5>";
                while ($row = $eventResult->fetch_assoc()) {
                    echo "<p>";
                    echo "<strong>Event Name:</strong> <a href='details.php?proid=null&eventid={$row['eventid']}'>" . $row['eventname'] . "</a><br>";
                    echo "<strong>Scheme:</strong> " . $row['scheme'] . "<br>";
                    echo "<strong>Contact (Govt):</strong> " . $row['c_a_n_govt_contact'] . "<br>";
                    echo "<strong>Contact (Ind):</strong> " . $row['c_a_ind_contact'] . "<br>";
                    echo "<strong>Contact (NGO):</strong> " . $row['c_a_ngo_contact'] . "<br>";
                    // Add more fields as needed
                    echo "</p>";
                }
            }
        }

        function displayResultsByYear($year)
        {
            global $conn;

            // // Search in the researchwork table for projects with the specified year
            // $researchWorkQuery = "SELECT proid FROM researchwork WHERE award = ?";
            // $researchWorkStmt = $conn->prepare($researchWorkQuery);
            // $researchWorkStmt->bind_param("s", $searchKeyword);
            // $researchWorkStmt->execute();
            // $researchWorkResult = $researchWorkStmt->get_result();

            // // Initialize an array to store proids found in research work
            // $researchWorkProids = [];

            // while ($row = $researchWorkResult->fetch_assoc()) {
            //     $researchWorkProids[] = $row['proid'];
            // }

            // // Search in the event table for events with the specified year
            // $eventQuery = "SELECT eventid FROM event WHERE eventyear = ?";
            // $eventStmt = $conn->prepare($eventQuery);
            // $eventStmt->bind_param("s", $searchKeyword);
            // $eventStmt->execute();
            // $eventResult = $eventStmt->get_result();

            // // Initialize an array to store eventids found in events
            // $eventEventids = [];

            // while ($row = $eventResult->fetch_assoc()) {
            //     $eventEventids[] = $row['eventid'];
            // }

            // Display results based on research work proids
            if (!empty($researchWorkProids)) {
                echo "<h4 class='text-primary'>Research Works:</h4>";
                foreach ($researchWorkProids as $proid) {
                    displayResearchWorkDetailsById($proid);
                }
            }

            // Display results based on event eventids
            if (!empty($eventEventids)) {
                echo "<h4 class='text-primary'>Events:</h4>";
                foreach ($eventEventids as $eventid) {
                    displayEventDetailsById($eventid);
                }
            }
        }

        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>