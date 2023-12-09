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
        $error_message = "Invalid email, password, or Institute ID. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log In | College Accreditation</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #88c8f7;
            color: #000;
        }

        .container {
            padding-top: 5%;
        }

        .loginbox {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .avatar {
            max-width: 100%;
            height: auto;
        }

        .btn-primary {
            background-color: #88c8f7;
            border-color: #88c8f7;
        }

        .btn-primary:hover {
            background-color: #6eadd4;
            border-color: #6eadd4;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="loginbox col-md-4 mx-auto">
            <!-- <img src="avatar.png" class="avatar"> -->
            <h1 class="mb-4">Login Here</h1>

            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="instituteid" class="form-label">Institute ID</label>
                    <input type="text" class="form-control" name="instituteid" placeholder="Enter Institute ID" required="required">
                </div>
                <div class="mb-3">
                    <label for="mailuid" class="form-label">Faculty Email</label>
                    <input type="text" class="form-control" name="mailuid" placeholder="Enter Email Address" required="required">
                </div>
                <div class="mb-3">
                    <label for="pwd" class="form-label">Password</label>
                    <input type="password" class="form-control" name="pwd" placeholder="Enter Password" required="required">
                </div>
                <button type="submit" name="login-submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
