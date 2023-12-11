<?php
require '../connect.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form data
    $instname = mysqli_real_escape_string($conn, $_POST['instname']);
    $instlogo = mysqli_real_escape_string($conn, $_FILES['instlogo']['name']); // Note: Handle file uploads properly in production code
    $instadd = mysqli_real_escape_string($conn, $_POST['instadd']);
    $instemail = mysqli_real_escape_string($conn, $_POST['instemail']);
    $instphno = mysqli_real_escape_string($conn, $_POST['instphno']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $adminemail = mysqli_real_escape_string($conn, $_POST['adminemail']);
    $adminphno = mysqli_real_escape_string($conn, $_POST['adminphno']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $adminimage = mysqli_real_escape_string($conn, $_FILES['adminimage']['name']); // Note: Handle file uploads properly in production code

    // Password validation
    $confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpass']);

    if ($password !== $confirmpassword) {
        die("Passwords do not match.");
    }

    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/';
    if (!preg_match($passwordRegex, $password)) {
        die("Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character, and be at least 6 characters long.");
    }

    // Generate institution ID
    $instid = substr($instname, 0, 4) . rand(1000, 9999);

    // Generate admin ID
    $adminid = $instid . substr($name, 0, 4) . rand(1000, 9999);

    // Upload images to the 'uploads' folder (Make sure the folder has the correct permissions)
    move_uploaded_file($_FILES['instlogo']['tmp_name'], 'uploads/' . $instlogo);
    move_uploaded_file($_FILES['adminimage']['tmp_name'], 'uploads/' . $adminimage);

    // Insert data into the database
    $sql = "INSERT INTO admin (instid, instname, instlogo, instadd, instemail, instphno, adminid, name, email, phno, pass, adminimage)
            VALUES ('$instid', '$instname', '$instlogo', '$instadd', '$instemail', '$instphno', '$adminid', '$name', '$adminemail', '$adminphno', '$password', '$adminimage')";

    if (mysqli_query($conn, $sql)) {
        $msg = "Registration successful!";
        // Assuming successful login, set session variables
        $_SESSION['instid'] = $instid; // replace $instid with the actual instid value
        $_SESSION['adminid'] = $adminid; // replace $adminid with the actual adminid value

        // Redirect to the admin dashboard
        header("Location: admindashboard.php");
    } else {
        $msg = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>

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
            max-width: 600px;
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
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        input[type="file"] {
            padding: 0;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
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

        .btn {
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

    <script>
        function validatePassword() {
            var password = document.getElementById("pass").value;
            var confirmPassword = document.getElementById("confirmpass").value;

            // Password validation criteria
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

            if (!password.match(passwordRegex)) {
                alert("Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character, and be at least 6 characters long.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }
    </script>


    <br>
    <br>
    <h2 class="mb-4">Welcome to Uni-Record</h2>
    <h3 class="mb-4">Register Here</h3>
    <br>
    <?php
    echo $msg;
    ?>

    <form action="#" method="post" enctype="multipart/form-data" onsubmit="return validatePassword()">
        <div class="mb-3">
            <label for="instname">Institution Name:</label>
            <input type="text" class="form-control" name="instname" required>
        </div>

        <div class="mb-3">
            <label for="instlogo">Institution Logo:</label>
            <input type="file" class="form-control" name="instlogo" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="instadd">Institution Address:</label>
            <input type="text" class="form-control" name="instadd" required>
        </div>

        <div class="mb-3">
            <label for="instemail">Institution Email:</label>
            <input type="email" class="form-control" name="instemail" required>
        </div>

        <div class="mb-3">
            <label for="instphno">Institution Phone Number:</label>
            <input type="text" class="form-control" name="instphno" required>
        </div>

        <div class="mb-3">
            <label for="name">Admin Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label for="adminemail">Admin Email:</label>
            <input type="email" class="form-control" name="adminemail" required>
        </div>

        <div class="mb-3">
            <label for="adminphno">Admin Phone Number:</label>
            <input type="text" class="form-control" name="adminphno" required>
        </div>

        <div class="mb-3">
            <label for="pass">Password (min 6 characters):</label>
            <input type="password" class="form-control" name="pass" minlength="6" required>
        </div>

        <div class="mb-3">
            <label for="confirmpass">Confirm Password:</label>
            <input type="password" class="form-control" name="confirmpass" minlength="6" required>
        </div>

        <div class="mb-3">
            <label for="adminimage">Admin Image:</label>
            <input type="file" class="form-control" name="adminimage" accept="image/*" required>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>
    </form>
    </div>
</body>

</html>