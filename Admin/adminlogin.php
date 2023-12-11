<?php
require '../connect.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instid = trim($_POST["instid"]); // Trim to remove leading/trailing spaces
    $adminid = trim($_POST["adminid"]); // Trim to remove leading/trailing spaces
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE instid = ? AND adminid = ? AND pass = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $instid, $adminid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Admin login successful, store adminid and instid in the session
        session_start(); // Start the session
        $_SESSION['adminid'] = $adminid;
        $_SESSION['instid'] = $instid;

        // Redirect to the admin dashboard or another page
        header("location: admindashboard.php");
        exit;
    } else {
        $error_message = "Invalid login credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        body {
            background-color: #88c8f7;
            color: #000;
            padding: 20px;
        }

        h2 {
            color: #fff;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-bottom: 0.5rem;
            display: block;
        }

        input {
            margin-bottom: 1rem;
        }

        .error-message {
            color: red;
            margin-top: 1rem;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 3;
            top: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.5);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            padding: 10px 15px;
            border: none;
        }

        .openbtn:hover {
            background-color: #88c8f7;
        }

        .navbar {
            z-index: 0;
            height: 50px;
        }

        .btn{
            margin: 5px;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidebar {
                padding-top: 15px;
            }

            .sidebar a {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="../_cc75286c-bd8d-4890-8939-6e30c7565dfa.jpg" alt="UNI-RECORD" class="img-fluid mr-2" width=40px>
                UNI-RECORD
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">Button</span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../WSPage/aboutus.html">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../WSPage/contactus.html">Contact Us</a>
                    </li>
                    <!-- Add more navigation links as needed -->

                    <!-- Sidebar Toggle Button -->
                    <li class="nav-item">
                        <button class="nav-link btn btn-outline-primary openbtn" id="toggle-btn" onclick="openNav()">&#9776;</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <button class="btn btn-primary" onclick="location.href='../Admin/adminreg.php'">New Registration for Institution</button>
        <button class="btn btn-primary" onclick="location.href='../Admin/adminlogin.php'">Login as Institution's Admin</button>
        <button class="btn btn-primary" onclick="location.href='../Faculty/facultylogin.php'">Login as Institution's Faculty</button>
        <button class="btn btn-primary" onclick="location.href='../WSPage/helpdesk.html'">Help Desk</button>
    </div>


    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
    <br>
    <br>
    <div class="container">
        <h2 class="text-center">Admin Login</h2>
        <br>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="#" method="post">
            <div class="mb-3">
                <label for="instid" class="form-label">Institution ID:</label>
                <input type="text" class="form-control" name="instid" required>
            </div>

            <div class="mb-3">
                <label for="adminid" class="form-label">Admin ID:</label>
                <input type="text" class="form-control" name="adminid" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
