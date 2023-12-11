<?php
require '../connect.php'; // Include the database connection file
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            background-color: #fff;
            /* Set background color for details block */
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
                    <p id="eventName"><strong>Event Name:</strong> <?php echo $row["eventname"]; ?></p>
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
                            // echo "<li><a href='$imagePath' target='_blank'>View Image</a></li>";
                            echo '<img src="' . $imagePath . '" style="width: 200px;">';
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
    <button class="btn btn-primary" id="print_btn">Print</button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


    <script>
        // import { jsPDF } from "jspdf";
        //  const jsPDF = require("jspdf");


        const printBtn = document.getElementById("print_btn");

        printBtn.addEventListener('click', generatePDF);

        function generatePDF() {
            const pdf = new jspdf.jsPDF();
            const elements = document.querySelectorAll('.details-block');


            const singleElement = elements[0].children;

            let y = 20;

            pdf.text(elements[0].innerText, 10, y);


            // elements.forEach(element => {
            //     console.log(element)

            //     pdf.text(element.innerText, 10, y);
            //     y +=10;
            // });

            pdf.save('details.pdf');
        }
    </script>
</body>

</html>