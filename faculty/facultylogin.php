<?php
require '../connect.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["mailuid"];
    $password = $_POST["pwd"];

    // SQL query to fetch faculty data based on email and password
    $sql = "SELECT * FROM faculty WHERE femail = '$email' AND fpass = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Login successful, redirect to the faculty dashboard
        header("location: facultyDashboard/facultydasboard.php");
        exit;
    } else {
        echo "<p class='error'>Invalid email or password. Please try again.</p>";
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
    <header>
        <nav>
            <h1>College Accreditation</h1>
            <ul id="navli">
                <li><a class="homeblack" href="../index.html">HOME</a></li>
                <li><a class="homered" href="faculty/flogin.php">Faculty Login</a></li>
            </ul>
        </nav>
    </header>
    <div class="divider"></div>

    <div class="loginbox">
        <img src="avatar.png" class="avatar">
        <h1>Login Here</h1>
        <form action="#" method="POST">
            <p>Email</p>
            <input type="text" name="mailuid" placeholder="Enter Email Address" required="required">
            <p>Password</p>
            <input type="password" name="pwd" placeholder="Enter Password" required="required">
            <input type="submit" name="login-submit" value="Login">
        </form>
    </div>
</body>

</html>