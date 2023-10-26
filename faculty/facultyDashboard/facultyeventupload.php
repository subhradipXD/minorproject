<?php
require '../../connect.php';

// Include your database connection script here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a random event id (adjust the range as needed)
    $eventid = rand(1000, 9999);

    // Retrieve other form data
    $eventname = $_POST["eventname"];
    $eventyear = $_POST["eventyear"];
    $c_a_n_govt = $_POST["c_a_n_govt"];
    $c_a_n_govt_contact = $_POST["c_a_n_govt_contact"];
    $c_a_ind = $_POST["c_a_ind"];
    $c_a_ind_contact = $_POST["c_a_ind_contact"];
    $c_a_ngo = $_POST["c_a_ngo"];
    $c_a_ngo_contact = $_POST["c_a_ngo_contact"];
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

    // Insert data into the "event" table (each report will be stored in a separate column)
    $sql = "INSERT INTO event (eventid, eventname, eventyear, c_a_n_govt, c_a_n_govt_contact, c_a_ind, c_a_ind_contact, c_a_ngo, c_a_ngo_contact, reports, addinfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $reports_str = implode(", ", $reports);

    $stmt->bind_param("issssssssss", $eventid, $eventname, $eventyear, $c_a_n_govt, $c_a_n_govt_contact, $c_a_ind, $c_a_ind_contact, $c_a_ngo, $c_a_ngo_contact, $reports_str, $addinfo);

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
        <label for="eventname">Event Name:</label>
        <input type="text" name="eventname" required><br>

        <label for="eventyear">Event Year:</label>
        <input type="text" name="eventyear" required><br>

        <label for="c_a_n_govt">Collaborated Non-Government Agency:</label>
        <input type="text" name="c_a_n_govt" required><br>

        <label for="c_a_n_govt_contact">Non-Government Agency Contact Details:</label>
        <input type="text" name="c_a_n_govt_contact" required><br>

        <label for="c_a_ind">Collaborated Industry:</label>
        <input type text" name="c_a_ind" required><br>

        <label for="c_a_ind_contact">Industry Contact Details:</label>
        <input type="text" name="c_a_ind_contact" required><br>

        <label for="c_a_ngo">Collaborated NGOs:</label>
        <input type="text" name="c_a_ngo" required><br>

        <label for="c_a_ngo_contact">NGOs Contact Details:</label>
        <input type="text" name="c_a_ngo_contact" required><br>

        <label for="reports[]">Upload PDF Reports:</label>
        <input type="file" name="reports[]" accept=".pdf" multiple required><br>

        <label for="addinfo">Additional Information:</label>
        <textarea name="addinfo" rows="4" required></textarea><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
