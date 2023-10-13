<?php
require '../connect.php';

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

?>


<!DOCTYPE html>
<html>
<head>
    <title>Research Paper Details</title>
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
</body>
</html>
