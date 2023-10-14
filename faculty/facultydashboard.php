<?php
require '../connect.php';

// Handle the submission of new research work
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proname = $_POST["proname"];
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
    $sql = "INSERT INTO researchwork (proname, picopi, fundagent, award, duration, ecopy, fundstatement, activereport) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiisss", $proname, $picopi, $fundagent, $award, $duration, $ecopy, $fundstatement, $activereport);

    if ($stmt->execute()) {
        echo "Research paper details have been successfully submitted.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
}

// Retrieve and display all research work projects
$sql = "SELECT * FROM researchwork";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Research Paper Details</title>
    <link rel="stylesheet" type="text/css" href="fdash.css">
</head>

<body>
    <h2>Research Paper Details Form</h2>
    <form action="#" method="post" enctype="multipart/form-data">
        <label for="proname">Name of Project:</label>
        <input type="text" name="proname" required><br>

        <label for="picopi">Name of Project Instructor:</label>
        <input type="text" name="picopi" required><br>

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
    <?php if ($result->num_rows > 0) : ?>
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
            <?php while ($row = $result->fetch_assoc()) : ?>
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