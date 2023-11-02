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

    // File upload paths
    $ecopy = "uploads/" . basename($_FILES["ecopy"]["name"]);
    $fundstatement = "uploads/" . basename($_FILES["fundstatement"]["name"]);
    $activereport = "uploads/" . basename($_FILES["activereport"]["name"]);

    // Move uploaded files to the "uploads" directory
    move_uploaded_file($_FILES["ecopy"]["tmp_name"], $ecopy);
    move_uploaded_file($_FILES["fundstatement"]["tmp_name"], $fundstatement);
    move_uploaded_file($_FILES["activereport"]["tmp_name"], $activereport);

    // Insert data into the "researchwork" table
    $sql = "INSERT INTO researchwork (proid, instid, deptname, fid, proname, picopi, fundagent, award, duration, ecopy, fundstatement, activereport) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssiisss", $proid, $inst_id, $selectedDepartment, $selectedFaculty, $proname, $picopi, $fundagent, $award, $duration, $ecopy, $fundstatement, $activereport);

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
    <link rel="stylesheet" type="text/css" href="frupload.css">
</head>

<body>
    <h2>Research Paper Details Form</h2>
    <form action="#" method="post" enctype="multipart/form-data">
        <label for="proname">Name of Project:</label>
        <input type="text" name="proname" required><br>

        <label for="picopi">Name of Project Instructor:</label>
        <input type="text" name="picopi" required><br>

        <label for ="faculty_department">Select Department:</label>
        <select name="faculty_department" required>
            <option value="" disabled selected>Select Department</option>
            <?php
            while ($dept_row = $dept_result->fetch_assoc()) {
                echo '<option value="' . $dept_row['fdept'] . '">' . $dept_row['fdept'] . '</option>';
            }
            ?>
        </select>

        <label for ="faculty_name">Select Faculty Name:</label>
        <select name="faculty_name" required>
            <option value="" disabled selected>Select Faculty Name</option>
            <?php
            while ($faculty_row = $faculty_result->fetch_assoc()) {
                echo '<option value="' . $faculty_row['fid'] . '">' . $faculty_row['fname'] . '</option>';
            }
            ?>
        </select>

        <label for="fundagent">Funding Agency:</label>
        <input type="text" name="fundagent" required><br>

        <label for="award">Year of Award:</label>
        <input type="number" name="award" required><br>

        <label for="duration">Duration of Project (in months):</label>
        <input type="number" name="duration" required><br>

        <label for="ecopy">E-Copy (PDF):</label>
        <input type="file" name="ecopy" accept=".pdf" required><br>

        <label for="fundstatement">Fund Release Statement (PDF):</label>
        <input type="file" name="fundstatement" accept=".pdf" required><br>

        <label for="activereport">Activity Report (PDF):</label>
        <input type="file" name="activereport" accept=".pdf" required><br>

        <input type="submit" value="Submit">
    </form>

    <h2>Research Work Details</h2>
    <?php if ($researchwork_result->num_rows > 0) : ?>
        <table>
            <tr>
                <th>Name of Project</th>
                <th>Name of Project Instructor</th>
                <th>Funding Agency</th>
                <th>Year of Award</th>
                <th>Duration of Project</th>
                <th>E-Copy</th>
                <th>Fund Release Statement</th>
                <th>Activity Report</th>
            </tr>
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
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No research work projects are available.</p>
    <?php endif; ?>
</body>

</html>
