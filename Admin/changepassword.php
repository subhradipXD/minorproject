<?php
require '../connect.php'; // Include the database connection file

// You can customize this part to get the admin's ID from the session or a query parameter
$adminid = "hitk4825Subh4066"; // Replace with the actual admin's ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    // Fetch the admin's password from the database
    $sql = "SELECT pass FROM admin WHERE adminid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $adminid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["pass"];

        // Verify the current password
        if (password_verify($currentPassword, $hashedPassword)) {
            // Validate the new password
            if (strlen($newPassword) >= 6) {
                if ($newPassword === $confirmPassword) {
                    // Hash the new password
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                    // Update the password in the database
                    $updateSql = "UPDATE admin SET pass = ? WHERE adminid = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("ss", $hashedNewPassword, $adminid);

                    if ($updateStmt->execute()) {
                        echo "Password changed successfully.";
                    } else {
                        echo "Error: " . $updateStmt->error;
                    }
                } else {
                    echo "New password and confirmation password do not match.";
                }
            } else {
                echo "New password should be at least 6 characters long.";
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "Admin not found.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>
    <form action="#" method="post">
        <label for="currentPassword">Current Password:</label>
        <input type="password" name="currentPassword" required><br>

        <label for="newPassword">New Password (min 6 characters):</label>
        <input type="password" name="newPassword" minlength="6" required><br>

        <label for="confirmPassword">Confirm New Password:</label>
        <input type="password" name="confirmPassword" minlength="6" required><br>

        <input type="submit" value="Change Password">
    </form>
</body>
</html>
