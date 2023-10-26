<?php
require '../../connect.php';

// Fetch event details from the database
$sql = "SELECT * FROM activity";
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
            $eventname = $row["activityname"];
            $description = $row["scheme"];
            $participation = $row["avgstu"];
            $addinfo = $row["addinfo"];
            $reports = explode(", ", $row["reports"]);
            $images = explode(", ", $row["images"]);

            echo '<div class="event-card">';
            echo "<h3>$eventname</h3>";
            echo "<p>Description: $description</p>";
            echo "<p>Number of Students Participated: $participation</p>";
            echo "<p>Additional Information: $addinfo</p>";

            echo "<h4>Reports:</h4>";
            foreach ($reports as $report) {
                echo "<a href='$report' target='_blank'>Download Report</a><br>";
            }

            echo "<h4>Images:</h4>";
            foreach ($images as $image) {
                echo "<img src='$image' alt='Event Image'><br>";
            }

            echo '</div>';
        }
    } else {
        echo "No event details available.";
    }
    ?>
</body>
</html>
