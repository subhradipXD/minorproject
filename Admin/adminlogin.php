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
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Admin Login</h2>

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
