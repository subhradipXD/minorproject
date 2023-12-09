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
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center text-primary">Welcome to the Platform</h2>

        <div class="d-grid gap-2">
            <button class="btn btn-primary" onclick="location.href='../Admin/adminreg.php'">New Registration for Institution</button>
            <button class="btn btn-primary" onclick="location.href='../Admin/adminlogin.php'">Login as Institution's Admin</button>
            <button class="btn btn-primary" onclick="location.href='../Faculty/facultylogin.php'">Login as Institution's Faculty</button>
        </div>

        <h3 class="text-center mt-4">Search</h3>

        <!-- Create a search form -->
        <form class="d-flex justify-content-center" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="keywords" class="form-control" placeholder="Enter keywords..." aria-label="Search">
            <input type="submit" class="btn btn-primary" value="Search">
        </form>

        <?php
        if (isset($_GET['keywords'])) {
            $keywords = $_GET['keywords'];

            // Initialize arrays to store search results
            $institutionResults = [];
            $researchWorkResults = [];
            $eventResults = [];

            // Search in the admin table for institution names
            $adminQuery = "SELECT instid FROM admin WHERE instname LIKE ?";
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
            $researchWorkQuery = "SELECT proid FROM researchwork WHERE proname LIKE ? OR addinfo LIKE ? OR picopi LIKE ?";
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

            if (!empty($institutionResults)) {
                // If institution results are found, show related research work and event details
                echo "<h4 class='text-primary'>Institutions:</h4>";
                foreach ($institutionResults as $instid) {
                    echo "<div class='mb-3'>";

                    // Display related research work
                    $relatedResearchWorkQuery = "SELECT * FROM researchwork WHERE instid = ?";
                    $relatedResearchWorkStmt = $conn->prepare($relatedResearchWorkQuery);
                    $relatedResearchWorkStmt->bind_param("s", $instid);
                    $relatedResearchWorkStmt->execute();
                    $relatedResearchWorkResult = $relatedResearchWorkStmt->get_result();

                    $relatedproid = "SELECT proid FROM researchwork WHERE instid = ?";
                    $relatedproidStmt = $conn->prepare($relatedproid);
                    $relatedproidStmt->bind_param("s", $instid);
                    $relatedproidStmt->execute();
                    $relatedproidResult = $relatedproidStmt->get_result();

                    echo "<h5 class='text-secondary'>Related Research Work:</h5>";
                    if ($relatedResearchWorkResult->num_rows > 0) {
                        while ($researchWorkRow = $relatedResearchWorkResult->fetch_assoc()) {
                            $proidres = $relatedproidResult->fetch_assoc();
                            $proid = $proidres['proid'];
                            echo "<p><strong>Project Name: <a href='details.php?proid=$proid&eventid=null'></strong>" . $researchWorkRow['proname'] . "</a></p>";
                            echo "<p><strong>Project Instructor:</strong> " . $researchWorkRow['picopi'] . "</p>";
                            echo "<p><strong>Funding Agency:</strong> " . $researchWorkRow['fundagent'] . "</p>";
                            echo "<p><strong>Year of Award:</strong> " . $researchWorkRow['award'] . "</p>";
                            echo "<p><strong>Duration of Project:</strong> " . $researchWorkRow['duration'] . " months</p>";
                            echo "<p><strong>Additional Information:</strong> " . $researchWorkRow['addinfo'] . "</p>";
                        }
                    } else {
                        echo "<p>No related research work found.</p>";
                    }

                    // Display related events
                    $relatedEventQuery = "SELECT * FROM event WHERE instid = ?";
                    $relatedEventStmt = $conn->prepare($relatedEventQuery);
                    $relatedEventStmt->bind_param("s", $instid);
                    $relatedEventStmt->execute();
                    $relatedEventResult = $relatedEventStmt->get_result();

                    $relatedEveid = "SELECT eventid FROM event WHERE instid = ?";
                    $relatedEveidStmt = $conn->prepare($relatedEveid);
                    $relatedEveidStmt->bind_param("s", $instid);
                    $relatedEveidStmt->execute();
                    $relatedEveidResult = $relatedEveidStmt->get_result();

                    echo "<h5 class='text-secondary'>Related Events:</h5>";
                    if ($relatedEventResult->num_rows > 0) {
                        while ($eventRow = $relatedEventResult->fetch_assoc()) {
                            $eveidres = $relatedEveidResult->fetch_assoc();
                            $eventid = $eveidres['eventid'];
                            echo "<div class='mb-3'>";
                            echo "<p><strong>Event Name: <a href='details.php?eventid=$eventid&proid=null'></strong>" . $eventRow['eventname'] . "</a></p>";
                            echo "<p><strong>Event Year:</strong> " . $eventRow['eventyear'] . "</p>";
                            echo "<p><strong>Scheme:</strong> " . $eventRow['scheme'] . "</p>";
                            echo "<p><strong>Collaborative Govt. Agency:</strong> " . $eventRow['c_a_n_govt'] . "</p>";
                            echo "<p><strong>Collaborative Industry Agency:</strong> " . $eventRow['c_a_ind'] . "</p>";
                            echo "<p><strong>Collaborative NGO Agency:</strong> " . $eventRow['c_a_ngo'] . "</p>";
                            echo "<p><strong>Number of Students Participated:</strong> " . $eventRow['avgstu'] . "</p>";
                            echo "<p><strong>Additional Information:</strong> " . $eventRow['addinfo'] . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No related events found.</p>";
                    }
                    echo "</div>";

                    if (!empty($researchWorkResults)) {
                        // If research work results are found, display them
                        echo "<h4> class='text-primary'>Research Works:</h4>";
                        foreach ($researchWorkResults as $proid) {
                            $researchWorkQuery = "SELECT proname, picopi, fundagent, award, duration, addinfo FROM researchwork WHERE proid = ?";
                            $researchWorkStmt = $conn->prepare($researchWorkQuery);
                            $researchWorkStmt->bind_param("s", $proid);
                            $researchWorkStmt->execute();
                            $researchWorkResult = $researchWorkStmt->get_result();

                            if ($researchWorkResult->num_rows > 0) {
                                while ($researchWorkRow = $researchWorkResult->fetch_assoc()) {
                                    echo "<div class='mb-3'>";
                                    echo "<p><strong>Project Name: <a href='details.php?proid=$proid&eventid=null'></strong>" . $researchWorkRow['proname'] . "</a></p>";
                                    echo "<p><strong>Project Instructor:</strong> " . $researchWorkRow['picopi'] . "</p>";
                                    echo "<p><strong>Funding Agency:</strong> " . $researchWorkRow['fundagent'] . "</p>";
                                    echo "<p><strong>Year of Award:</strong> " . $researchWorkRow['award'] . "</p>";
                                    echo "<p><strong>Duration of Project:</strong> " . $researchWorkRow['duration'] . " months</p>";
                                    echo "<p><strong>Additional Information:</strong> " . $researchWorkRow['addinfo'] . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No research work found for this ID.</p>";
                            }
                        }
                    }

                    if (empty($institutionResults) && empty($researchWorkResults) && empty($eventResults)) {
                        echo "<p class='text-center'>No results found.</p>";
                    }
                }
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>