<?php
require '../../connect.php'; // Include your database connection script here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activityid = rand(1000, 9999);
    // $institute = $_POST["institute"];
    $eventname = $_POST["eventname"];
    $description = $_POST["description"];
    $participation = $_POST["participation"];
    $addinfo = $_POST["addinfo"];

    $reports = array();
    if (!empty($_FILES["reports"]["name"][0])) {
        foreach ($_FILES["reports"]["tmp_name"] as $key => $tmp_name) {
            $tmp_file = $_FILES["reports"]["tmp_name"][$key];
            $file_name = basename($_FILES["reports"]["name"][$key]);
            $reports[] = "uploads/" . $file_name;
            move_uploaded_file($tmp_file, "uploads/" . $file_name);
        }
    }

    $images = array();
    if (!empty($_FILES["images"]["name"][0])) {
        foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
            $tmp_file = $_FILES["images"]["tmp_name"][$key];
            $file_name = basename($_FILES["images"]["name"][$key]);
            $images[] = "uploads/" . $file_name;
            move_uploaded_file($tmp_file, "uploads/" . $file_name);
        }
    }

    // Insert data into the "activity" table
    $sql = "INSERT INTO activity (activityid, activityname, scheme, reports, images, avgstu, addinfo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $reports_str = implode(", ", $reports);
    $images_str = implode(", ", $images);

    $stmt->bind_param("issssss", $activityid, $eventname, $description, $reports_str, $images_str, $participation, $addinfo);

    if ($stmt->execute()) {
        echo "Event details have been successfully submitted.";
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
    <title>Event Details Form</title>
</head>
<body>
    <h2>Event Details Form</h2>
    <form action="#" method="post" enctype="multipart/form-data">
        <!-- <label for="institute">Institute Name:</label>
        <input type="text" name="institute" required><br> -->

        <label for="eventname">Event Name:</label>
        <input type="text" name="eventname" required><br>

        <label for="description">Event Description:</label>
        <textarea name="description" rows="4" required></textarea><br>

        <label for="participation">Number of Students Participated:</label>
        <input type="number" name="participation" required><br>

        <label for="reports[]">Upload PDF Reports:</label>
        <input type="file" name="reports[]" accept=".pdf" multiple><br>

        <label for="images[]">Upload Event Images:</label>
        <input type="file" name="images[]" accept="image/*" multiple><br>

        <label for="addinfo">Additional Information:</label>
        <textarea name="addinfo" rows="4"></textarea><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
