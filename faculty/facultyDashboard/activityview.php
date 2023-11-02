<?php
require '../../connect.php';

// Fetch event details from the database
$sql = "SELECT * FROM event";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
    <style>
        .event-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 300px;
            float: left;
        }

        .event-card img {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <h2>Event Details</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $eventname = $row["eventname"];
            $eventyear = $row["eventyear"];
            $scheme = $row["scheme"];
            $c_a_n_govt = $row["c_a_n_govt"];
            $c_a_n_govt_contact = $row["c_a_n_govt_contact"];
            $c_a_ind = $row["c_a_ind"];
            $c_a_ind_contact = $row["c_a_ind_contact"];
            $c_a_ngo = $row["c_a_ngo"];
            $c_a_ngo_contact = $row["c_a_ngo_contact"];
            $avgstu = $row["avgstu"];
            $addinfo = $row["addinfo"];
            $reports = json_decode($row["reports"]);
            $images = json_decode($row["images"]);

            echo '<div class="event-card">';
            echo "<h3>$eventname</h3>";
            echo "<p>Event Year: $eventyear</p>";
            echo "<p>Scheme: $scheme</p>";
            echo "<p>Collaborated Non-Government Agency: $c_a_n_govt</p>";
            echo "<p>Non-Government Agency Contact Details: $c_a_n_govt_contact</p>";
            echo "<p>Collaborated Industry: $c_a_ind</p>";
            echo "<p>Industry Contact Details: $c_a_ind_contact</p>";
            echo "<p>Collaborated NGOs: $c_a_ngo</p>";
            echo "<p>NGOs Contact Details: $c_a_ngo_contact</p>";
            echo "<p>Number of Students Participated: $avgstu</p>";
            echo "<p>Additional Information: $addinfo</p>";

            echo "<h4>Reports:</h4>";
            foreach ($reports as $report) {
                echo "<a href='uploads/$report' target='_blank'>Download Report</a><br>";
            }

            echo "<h4>Images:</h4>";
            foreach ($images as $image) {
                echo "<img src='uploads/$image' alt='Event Image'><br>";
            }

            echo '</div>';
        }
    } else {
        echo "No event details available.";
    }
    ?>
</body>
</html>
