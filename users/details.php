<?php
require '../connect.php'; // Include the database connection file
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        body {
            background-color: #88c8f7;
            padding: 20px;
        }

        .details-block {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #fff; /* Set background color for details block */
        }

        .image-list,
        .report-list {
            list-style-type: none;
            padding: 0;
        }

        .image-list li,
        .report-list li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php
    $proid = $_GET['proid'];
    $eventid = $_GET['eventid'];

    if ($proid !== 'null') {
        $sql = "SELECT * FROM researchwork WHERE proid = ?";
        $result = $conn->prepare($sql);
        $result->bind_param("s", $proid);
        $result->execute();
        $researchwork_result = $result->get_result();
        ?>
        <h2>Research Work Details</h2>
        <?php if ($researchwork_result->num_rows > 0) : ?>
            <?php while ($row = $researchwork_result->fetch_assoc()) : ?>
                <div class="details-block">
                    <p><strong>Name of Project:</strong> <?php echo $row["proname"]; ?></p>
                    <p><strong>Name of Project Instructor:</strong> <?php echo $row["picopi"]; ?></p>
                    <p><strong>Funding Agency:</strong> <?php echo $row["fundagent"]; ?></p>
                    <p><strong>Year of Award:</strong> <?php echo $row["award"]; ?></p>
                    <p><strong>Duration of Project:</strong> <?php echo $row["duration"]; ?></p>
                    <p><strong>E-Copy:</strong> <a href="../faculty/facultyDashboard/<?php echo $row["ecopy"]; ?>" target="_blank">View E-Copy</a></p>
                    <p><strong>Fund Release Statement:</strong> <a href="../faculty/facultyDashboard/<?php echo $row["fundstatement"]; ?>" target="_blank">View Fund Release Statement</a></p>
                    <p><strong>Activity Report:</strong> <a href="../faculty/facultyDashboard/<?php echo $row["activereport"]; ?>" target="_blank">View Activity Report</a></p>
                    <p><strong>Additional Information:</strong> <?php echo $row["addinfo"]; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No research work projects are available.</p>
        <?php endif;
    } else {
        $sql = "SELECT * FROM event WHERE eventid = ?";
        $result = $conn->prepare($sql);
        $result->bind_param("s", $eventid);
        $result->execute();
        $event_result = $result->get_result();
        ?>
        <h2>Event Details</h2>
        <?php if ($event_result->num_rows > 0) : ?>
            <?php while ($row = $event_result->fetch_assoc()) : ?>
                <div class="details-block">
                    <p><strong>Event Name:</strong> <?php echo $row["eventname"]; ?></p>
                    <p><strong>Event Year:</strong> <?php echo $row["eventyear"]; ?></p>
                    <p><strong>Department:</strong> <?php echo $row["deptid"]; ?></p>
                    <p><strong>Faculty Name:</strong> <?php echo $row["fid"]; ?></p>
                    <p><strong>Scheme:</strong> <?php echo $row["scheme"]; ?></p>
                    <p><strong>Collaborated NGOs:</strong> <?php echo $row["c_a_ngo"]; ?></p>
                    <p><strong>Number of Students Participated:</strong> <?php echo $row["avgstu"]; ?></p>
                    <p><strong>Additional Information:</strong> <?php echo $row["addinfo"]; ?></p>
                    <p><strong>Images:</strong></p>
                    <ul class="image-list">
                        <?php
                        $images = explode(',', $row["images"]);
                        foreach ($images as $image) {
                            $imagePath = "../faculty/facultyDashboard/" . $image;
                            echo "<li><a href='$imagePath' target='_blank'>View Image</a></li>";
                        }
                        ?>
                    </ul>
                    <p><strong>Reports:</strong></p>
                    <ul class="report-list">
                        <?php
                        $reports = explode(',', $row["reports"]);
                        foreach ($reports as $report) {
                            $reportPath = "../faculty/facultyDashboard/" . $report;
                            echo "<li><a href='$reportPath' target='_blank'>View Report</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No event details are available.</p>
        <?php endif;
    }
    ?>

    <!-- Add a button to trigger the PDF generation -->
    <button class="btn btn-primary" onclick="generatePDF()">Print</button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Include the jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        import { jsPDF } from "jspdf";
        function generatePDF() {
            const pdf = new jsPDF();
            const elements = document.querySelectorAll('.details-block');

            elements.forEach(element => {
                pdf.text(element.innerText, 10, pdf.previousAutoTable.finalY + 10);
            });

            pdf.save('details.pdf');
        }
    </script>
</body>

</html>
