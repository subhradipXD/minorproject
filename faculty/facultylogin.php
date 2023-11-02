<?php
require '../connect.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["mailuid"];
    $password = $_POST["pwd"];
    $instituteId = $_POST["instituteid"];

    // SQL query to fetch faculty data based on email, password, and Institute ID
    $sql = "SELECT * FROM faculty WHERE femail = '$email' AND fpass = '$password' AND instid = '$instituteId'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Faculty login successful, start a session with institution ID, email, and faculty ID
        $facultyData = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['instid'] = $instituteId;
        $_SESSION['femail'] = $email;
        $_SESSION['fid'] = $facultyData['fid'];

        // Redirect to the faculty dashboard
        header("location: facultyDashboard/facultydashboard.php");
        exit;
    } else {
        echo "<p class='error'>Invalid email, password, or Institute ID. Please try again.</p>";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Log In | College Accreditation</title>
    <link rel="stylesheet" type="text/css" href="stylelogin.css">
</head>

<body>
    <div class="loginbox">
        <img src="avatar.png" class="avatar">
        <h1>Login Here</h1>
        <form action="#" method="POST">
            <p>Institute ID</p>
            <input type="text" name="instituteid" placeholder="Enter Institute ID" required="required">
            <p>Email</p>
            <input type="text" name="mailuid" placeholder="Enter Email Address" required="required">
            <p>Password</p>
            <input type="password" name="pwd" placeholder="Enter Password" required="required">
            <input type="submit" name="login-submit" value="Login">
        </form>
    </div>
</body>

</html>