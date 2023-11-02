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
    <link rel="stylesheet" type="text/css" href="frupload.css">
</head>

<body>
    <h2>Event Details Form</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <label for="eventname">Event Name:</label>
        <input type="text" name="eventname" required><br>

        <label for="eventyear">Event Year:</label>
        <input type="text" name="eventyear" required><br>

        <label for="faculty_department">Select Department:</label>
        <select name="faculty_department" required>
            <option value="" disabled selected>Select Department</option>
            <?php
            while ($dept_row = $dept_result->fetch_assoc()) {
                echo '<option value="' . $dept_row['fdept'] . '">' . $dept_row['fdept'] . '</option>';
            }
            ?>
        </select>

        <label for="faculty_name">Select Faculty Name:</label>
        <select name="faculty_name" required>
            <option value="" disabled selected>Select Faculty Name</option>
            <?php
            while ($faculty_row = $faculty_result->fetch_assoc()) {
                echo '<option value="' . $faculty_row['fid'] . '">' . $faculty_row['fname'] . '</option>';
            }
            ?>
        </select>

        <label for="scheme">Scheme:</label>
        <textarea type="text" rows="4" name="scheme" required></textarea><br>

        <label for="c_a_n_govt">Collaborated Non-Government Agency:</label>
        <input type="text" name="c_a_n_govt" required><br>

        <label for="c_a_n_govt_contact">Non-Government Agency Contact Details:</label>
        <input type="text" name="c_a_n_govt_contact" required><br>

        <label for="c_a_ind">Collaborated Industry:</label>
        <input type="text" name="c_a_ind" required><br>

        <label for="c_a_ind_contact">Industry Contact Details:</label>
        <input type="text" name="c_a_ind_contact" required><br>

        <label for="c_a_ngo">Collaborated NGOs:</label>
        <input type="text" name="c_a_ngo" required><br>

        <label for="c_a_ngo_contact">NGOs Contact Details:</label>
        <input type="text" name="c_a_ngo_contact" required><br>

        <label for="avgstu">Number of Students Participated:</label>
        <input type="number" name="avgstu" required><br>

        <label for="addinfo">Additional Information:</label>
        <textarea name="addinfo" rows="4" required></textarea><br>

        <label for="images[]">Upload Event Images:</label>
        <input type="file" name="images[]" accept="image/*" multiple><br>

        <label for="reports[]">Upload PDF Reports:</label>
        <input type="file" name="reports[]" accept=".pdf" multiple required><br>

        <input type="submit" value="Submit">
    </form>

    <h2>Event Details</h2>
    <?php if ($event_result->num_rows > 0) : ?>
        <table>
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
        </table>
    <?php else : ?>
        <p>No event details are available.</p>
    <?php endif; ?>
</body>

</html>