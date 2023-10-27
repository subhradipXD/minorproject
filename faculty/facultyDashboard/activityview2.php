<?php
require '../../connect.php'; // Include your database connection script here
require '../../tcpdf/tcpdf.php'; // Include the TCPDF library

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
    <form method="post">
        <button type="submit" name="generate_pdf">Download Event Details as PDF</button>
    </form>
    <?php
    // Fetch and display event details
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="event-card">';
            echo '<h3>' . $row["activityname"] . '</h3>';
            echo '<p>Description: ' . $row["scheme"] . '</p>';
            echo '<p>Number of Students Participated: ' . $row["avgstu"] . '</p>';
            echo '<p>Additional Information: ' . $row["addinfo"] . '</p>';
            
            $reports = explode(", ", $row["reports"]);
            if (!empty($reports[0])) {
                echo '<h4>Reports:</h4>';
                foreach ($reports as $report) {
                    echo '<p><a href="' . $report . '" target="_blank">Download Report</a></p>';
                }
            }
            
            $images = explode(", ", $row["images"]);
            if (!empty($images[0])) {
                echo '<h4>Images:</h4>';
                foreach ($images as $image) {
                    echo '<img src="' . $image . '" alt="Event Image">';
                }
            }
            
            echo '</div>';
        }
    } else {
        echo "No event details available.";
    }
    ?>
</body>
</html>

<?php
if (isset($_POST['generate_pdf'])) {
    // Create a new PDF document
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // Fetch event details again to generate PDF
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $eventname = $row["activityname"];
        $description = $row["scheme"];
        $participation = $row["avgstu"];
        $addinfo = $row["addinfo"];
        $reports = explode(", ", $row["reports"]);
        $images = explode(", ", $row["images"]);

        // Write event details to the PDF
        $pdf->SetFont('times', 'B', 16);
        $pdf->Cell(0, 10, $eventname, 0, 1, 'C');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 10, "Description: $description", 0, 1);
        $pdf->Cell(0, 10, "Number of Students Participated: $participation", 0, 1);
        $pdf->Cell(0, 10, "Additional Information: $addinfo", 0, 1);

        if (!empty($reports[0])) {
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(0, 10, 'Reports:', 0, 1);
            foreach ($reports as $report) {
                $pdf->Cell(0, 10, "Download Report: $report", 0, 1);
            }
        }

        if (!empty($images[0])) {
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(0, 10, 'Images:', 0, 1);
            foreach ($images as $image) {
                $pdf->Image($image, 15, null, 0, 60);
                $pdf->AddPage(); // Start a new page for each image
            }
        }
    }

    // Set file properties
    $pdf->SetTitle('Event_Details');
    $pdf->SetAuthor('Your Name');
    $pdf->SetCreator('Your Application');
    $pdf->Output('Event_Details.pdf', 'D'); // Output the PDF as a download
    exit;
}
?>
