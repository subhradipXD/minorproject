<?php
require '../connect.php'; // Include your database connection script

// Query to select institution name and logo
$sql = "SELECT instname, instlogo FROM admin";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>Institution Name</th><th>Institution Logo</th></tr>";

    // Output data from rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['instname'] . "</td>";
        echo "<td><img src='" . $row['instlogo'] . "' alt='Institution Logo' width='100'></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No institutions found.";
}

$conn->close(); // Close the database connection
?>
