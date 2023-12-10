<?php
require '../connect.php'; // Include the database connection file

// Function to check password complexity
function isPasswordValid($password)
{
    // Add your password complexity rules here
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $password);
}

// Define the target directory for file uploads
$uploadDirectory = 'uploads/';
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instname = $_POST["instname"];
    $instadd = $_POST["instadd"];
    $instemail = $_POST["instemail"];
    $instphno = $_POST["instphno"];
    $name = $_POST["name"];
    $adminemail = $_POST["adminemail"];
    $adminphno = $_POST["adminphno"];
    $pass = $_POST["pass"];
    $confirmpass = isset($_POST["confirmpass"]) ? $_POST["confirmpass"] : null;

    // Check if passwords match
    if ($pass !== $confirmpass) {
        $msg = "Error: Passwords do not match.";
    } else {
        // Check if the password meets complexity requirements
        if (!isPasswordValid($pass)) {
            $msg = "Error: Password must contain at least 1 capital letter, 1 small letter, 1 special character, 1 numerical, and be at least 6 characters long.";
        } else {
            // Continue with the rest of your code
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
                echo '<script>';
                echo 'swal({
            title: "Success!",
            text: "Admin registration has been successfully completed. Institution ID: ' . $instid . ' | Admin ID: ' . $adminid . '",
            icon: "success",
            button: "OK"
        }).then(() => {
            window.location.href = "admindashboard.php?adminid=' . $adminid . '&instid=' . $instid . '";
        });';
                echo '</script>';
            } else {
                $msg = "Error: " . $stmt->error;
            }
        }
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
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                        <a class="nav-link active" href="#">Home</a>
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

    <h2 class="mb-4">Welcome to Uni-Record</h2>
    <h3 class="mb-4">Register Here</h3>
    
    <form action="#" method="post" enctype="multipart/form-data">
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
            <label for="pass">Confirm Password:</label>
            <input type="password" class="form-control" name="pass" minlength="6" required>
        </div>

        <div class="mb-3">
            <label for="adminimage">Admin Image:</label>
            <input type="file" class="form-control" name="adminimage" accept="image/*" required>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>

        <div style=color:red>
        <?php
        echo $msg;
        ?>
        </div>
    </form>

        
    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>