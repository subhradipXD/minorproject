<?php
require '../connect.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instid = $_POST["instid"];
    $adminid = $_POST["adminid"];
    $password = $_POST["password"];

    // Check if the provided institution ID, admin ID, and password are correct
    $sql = "SELECT * FROM admin WHERE instid = ? AND adminid = ? AND pass = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $instid, $adminid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Admin login successful, store adminid and instid in the session
        $_SESSION['adminid'] = $adminid;
        $_SESSION['instid'] = $instid;
        
        // Redirect to the admin dashboard or another page
        header("location: admindashboard.php");
        exit;
    } else {
        echo "Invalid login credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="#" method="post">
        <label for="instid">Institution ID:</label>
        <input type="text" name="instid" required><br>

        <label for="adminid">Admin ID:</label>
        <input type="text" name="adminid" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
