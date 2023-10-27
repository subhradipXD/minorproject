<?php
require '../connect.php'; // Include the database connection file

// You can customize this part to get the admin's ID from the session or a query parameter
$adminid = "hitk4825Subh4066"; // Replace with the actual admin's ID

// Fetch admin details from the database
$sql = "SELECT * FROM admin WHERE adminid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    // Handle the case where the admin ID is not found
    echo "Admin not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Profile</title>
</head>

<body>
    <h2>Admin Profile</h2>
    <table>
        <tr>
            <td>Institution ID:</td>
            <td><?php echo $admin["instid"]; ?></td>
        </tr>
        <tr>
            <td>Institution Name:</td>
            <td><?php echo $admin["instname"]; ?></td>
        </tr>
        <tr>
            <td>Institution Logo:</td>
            <td><img src="<?php echo $admin["instlogo"]; ?>" alt="Institution Logo" width="100"></td>
        </tr>
        <tr>
            <td>Institution Address:</td>
            <td><?php echo $admin["instadd"]; ?></td>
        </tr>
        <tr>
            <td>Institution Email:</td>
            <td><?php echo $admin["instemail"]; ?></td>
        </tr>
        <tr>
            <td>Institution Phone Number:</td>
            <td><?php echo $admin["instphno"]; ?></td>
        </tr>
        <tr>
            <td>Admin ID:</td>
            <td><?php echo $admin["adminid"]; ?></td>
        </tr>
        <tr>
            <td>Name:</td>
            <td><?php echo $admin["name"]; ?></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?php echo $admin["email"]; ?></td>
        </tr>
        <tr>
            <td>Phone Number:</td>
            <td><?php echo $admin["phno"]; ?></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td>********</td>
        </tr>
        <tr>
            <td>Admin Image:</td>
            <td><img src="<?php echo $admin["adminimage"]; ?>" alt="Admin Image" width="100"></td>
        </tr>
    </table>

    <!-- Add a button to change the password -->
    <form action="changepassword.php" method="post">
        <input type="submit" value="Change Password">
    </form>
</body>

</html>