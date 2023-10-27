<?php
require '../connect.php'; // Include the database connection file

// Define the target directory for file uploads
$uploadDirectory = 'uploads/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instname = $_POST["instname"];
    $instadd = $_POST["instadd"];
    $instemail = $_POST["instemail"];
    $instphno = $_POST["instphno"];
    $name = $_POST["name"];
    $adminemail = $_POST["adminemail"];
    $adminphno = $_POST["adminphno"];
    $pass = $_POST["pass"];

    // Generate institution and admin IDs
    $instid = substr($instname, 0, 4) . rand(1000, 9999);
    $adminid = $instid . substr($name, 0, 4) . rand(1000, 9999);

    // Process institution logo upload
    $instlogo = $uploadDirectory . basename($_FILES["instlogo"]["name"]);
    move_uploaded_file($_FILES["instlogo"]["tmp_name"], $instlogo);

    // Process admin image upload
    $adminimage = $uploadDirectory . basename($_FILES["adminimage"]["name"]);
    move_uploaded_file($_FILES["adminimage"]["tmp_name"], $adminimage);

    // Insert data into the "admin" table
    $sql = "INSERT INTO admin (instid, instname, instlogo, instadd, instemail, instphno, adminid, name, email, phno, pass, adminimage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $instid, $instname, $instlogo, $instadd, $instemail, $instphno, $adminid, $name, $adminemail, $adminphno, $pass, $adminimage);

    if ($stmt->execute()) {
        echo "Admin registration has been successfully completed.";
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
    <title>Admin Registration</title>
</head>
<body>
    <h2>Admin Registration Form</h2>
    <form action="#" method="post" enctype="multipart/form-data">
        <label for="instname">Institution Name:</label>
        <input type="text" name="instname" required><br>

        <label for="instlogo">Institution Logo:</label>
        <input type="file" name="instlogo" accept="image/*" required><br>

        <label for="instadd">Institution Address:</label>
        <input type="text" name="instadd" required><br>

        <label for="instemail">Institution Email:</label>
        <input type="email" name="instemail" required><br>

        <label for="instphno">Institution Phone Number:</label>
        <input type="text" name="instphno" required><br>

        <label for="name">Admin Name:</label>
        <input type="text" name="name" required><br>

        <label for="adminemail">Admin Email:</label>
        <input type="email" name="adminemail" required><br>

        <label for="adminphno">Admin Phone Number:</label>
        <input type="text" name="adminphno" required><br>

        <label for="pass">Password (min 6 characters):</label>
        <input type="password" name="pass" minlength="6" required><br>

        <label for="adminimage">Admin Image:</label>
        <input type="file" name="adminimage" accept="image/*" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
