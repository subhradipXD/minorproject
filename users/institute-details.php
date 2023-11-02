<?php
require '../connect.php'; // Include your database connection script

// Check if the 'id' query parameter is set and is a valid integer
if (isset($_GET['id'])) {
    $instid = $_GET['id'];
    echo "$instid";


    // Query to fetch institute details based on instid
    $sql = "SELECT * FROM admin WHERE instid = $instid";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];

        echo "<h2>Name: $name</h2>";
    } else {
        echo "Institute not found.";
    }
} else {
    echo "Invalid or missing 'id' parameter.";
}

?>
