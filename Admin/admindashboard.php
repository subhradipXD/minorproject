<?php
require '../connect.php'; // Include the database connection file
if (!isset($_SESSION['adminid']) || !isset($_SESSION['instid'])) {
    // Redirect to the login page if not logged in
    header("location: adminlogin.php");
    exit;
}
function generateRandomPassword($length = 8)
{
    $uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
    $numericChars = '0123456789';
    $requiredChars = [];
    $requiredChars[] = $uppercaseChars[rand(0, strlen($uppercaseChars) - 1)];
    $requiredChars[] = $lowercaseChars[rand(0, strlen($lowercaseChars) - 1)];
    $requiredChars[] = $numericChars[rand(0, strlen($numericChars) - 1)];
    $allChars = $uppercaseChars . $lowercaseChars . $numericChars;
    $remainingLength = $length - 3;
    $randomPassword = $requiredChars[0] . $requiredChars[1] . $requiredChars[2];
    for ($i = 0; $i < $remainingLength; $i++) {
        $randomPassword .= $allChars[rand(0, strlen($allChars) - 1)];
    }
    $randomPassword = str_shuffle($randomPassword);
    return $randomPassword;
}
$instid = $_SESSION['instid'];
$adminid = $_SESSION['adminid'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file']['tmp_name'];
        $fileType = $_FILES['file']['type'];
        if (
            $fileType === 'text/csv' ||
            $fileType === 'application/vnd.ms-excel' ||
            $fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ) {
            $handle = fopen($file, "r");
            $dataArray = array();
            $skipFirstRow = true;
            $duplicateEntries = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($skipFirstRow) {
                    $skipFirstRow = false;
                    continue;
                }
                $fid = $data[0];
                $femail = $data[3];
                $stmt = $conn->prepare("SELECT COUNT(*) FROM faculty WHERE fid = ? ");
                $stmt->bind_param("s", $fid);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                if ($count > 0) {
                    $duplicateEntries[] = $fid;
                } else {
                    // Generate a random password
                    $data[] = generateRandomPassword();
                    // Add instid and adminid from the session
                    $data[] = $_SESSION['instid'];
                    $data[] = $_SESSION['adminid'];
                    $dataArray[] = $data;
                }
            }
            fclose($handle);
            if (!empty($duplicateEntries)) {
                echo "Duplicate entries found for the following faculty IDs: " . implode(', ', $duplicateEntries);
            }
            if (!empty($dataArray)) {
                try {
                    $sql = "INSERT INTO faculty (fid, fname, fdept, femail, fphno, fpass, instid, adminid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    foreach ($dataArray as $row) {
                        $stmt->bind_param("ssssssss", ...$row); // Bind parameters correctly
                        $stmt->execute();
                    }
                    echo "CSV data has been successfully inserted into the database.";
                } catch (PDOException $e) {
                    echo "Database Error: " . $e->getMessage();
                }
            }
        } else {
            echo "Please upload a valid CSV file.";
        }
    } elseif (isset($_POST['fid']) && isset($_POST['fname']) && isset($_POST['fdept']) && isset($_POST['femail']) && isset($_POST['fphno'])) {
        // Handle manual faculty entry
        $fid = $_POST['fid'];
        $fname = $_POST['fname'];
        $fdept = $_POST['fdept'];
        $femail = $_POST['femail'];
        $fphno = $_POST['fphno'];
        // Check for duplicate entries by fid or femail
        $stmt = $conn->prepare("SELECT COUNT(*) FROM faculty WHERE fid = ? OR femail = ?");
        $stmt->bind_param("ss", $fid, $femail);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if ($count > 0) {
            echo "Duplicate entry found for the provided faculty ID or email.";
        } else {
            $fpass = generateRandomPassword();
            // Add instid and adminid from the session
            $instid = $_SESSION['instid'];
            $adminid = $_SESSION['adminid'];
            try {
                $sql = "INSERT INTO faculty (fid, fname, fdept, femail, fphno, fpass, instid, adminid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $fid, $fname, $fdept, $femail, $fphno, $fpass, $instid, $adminid);
                $stmt->execute();

                echo "Faculty details added to the database with a random password.";
            } catch (PDOException $e) {
                echo "Database Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload and Process CSV Data</title>
    <link rel="stylesheet" type="text/css" href="csv.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #88c8f7;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Upload and Process CSV Data</h1>

        <!-- Upload CSV Form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
                <label for="file" class="form-label">Choose CSV file:</label>
                <input type="file" name="file" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="form-control">
            </div>
            <button type="submit" name="upload" class="btn btn-primary">Upload</button>
        </form>

        <!-- Manual Faculty Entry Form -->
        <h2 class="mt-4">Manual Faculty Entry</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
            <div class="mb-3">
                <label for="fid" class="form-label">Faculty ID:</label>
                <input type="text" name="fid" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fname" class="form-label">Faculty Name:</label>
                <input type="text" name="fname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fdept" class="form-label">Faculty Department:</label>
                <input type="text" name="fdept" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="femail" class="form-label">Faculty Email:</label>
                <input type="email" name="femail" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fphno" class="form-label">Faculty Phone Number:</label>
                <input type="text" name="fphno" class="form-control" required>
            </div>
            <button type="submit" name="add-faculty" class="btn btn-primary">Add Faculty</button>
        </form>

        <!-- Faculty Details Table -->
        <h2>Faculty Details</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Faculty ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch faculty details from the database
                $sql = "SELECT fid, fname, fdept, femail, fphno FROM faculty WHERE instid = '$instid' AND adminid = '$adminid'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["fid"] . "</td>";
                        echo "<td>" . $row["fname"] . "</td>";
                        echo "<td>" . $row["fdept"] . "</td>";
                        echo "<td>" . $row["femail"] . "</td>";
                        echo "<td>" . $row["fphno"] . "</td>";
                        echo "<td>
                <form action='{$_SERVER['PHP_SELF']}' method='post' style='display:inline;'>
                    <input type='hidden' name='fid' value='{$row["fid"]}'>
                    <input type='submit' name='update-faculty' value='Update'>
                </form>
                <form action='{$_SERVER['PHP_SELF']}' method='post' style='display:inline;'>
                    <input type='hidden' name='fid' value='{$row["fid"]}'>
                    <input type='submit' name='delete-faculty' value='Delete'>
                </form>
            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No faculty details found.</td></tr>";
                }
                ?>

            </tbody>
        </table>
        <?php
        // Define $updateMode at the beginning
        $updateMode = false;

        // Check if the update-faculty button is clicked on the main page
        if (isset($_POST['update-faculty'])) {
            $fidToUpdate = $_POST['fid'];
            $updateMode = true;
            // Fetch existing faculty details for the selected Faculty ID
            $stmt = $conn->prepare("SELECT * FROM faculty WHERE fid = ?");
            $stmt->bind_param("s", $fidToUpdate);
            $stmt->execute();
            $result = $stmt->get_result();
            $facultyDetails = $result->fetch_assoc();
            $stmt->close();
        }
        ?>

        <!-- Update Faculty Form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
            <div class="mb-3">
                <label for="fname" class="form-label">Faculty Name:</label>
                <input type="text" name="fname" value="<?php echo $updateMode ? $facultyDetails['fname'] : ''; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fdept" class="form-label">Faculty Department:</label>
                <input type="text" name="fdept" value="<?php echo $updateMode ? $facultyDetails['fdept'] : ''; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="femail" class="form-label">Faculty Email:</label>
                <input type="email" name="femail" value="<?php echo $updateMode ? $facultyDetails['femail'] : ''; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fphno" class="form-label">Faculty Phone Number:</label>
                <input type="text" name="fphno" value="<?php echo $updateMode ? $facultyDetails['fphno'] : ''; ?>" class="form-control" required>
            </div>
            <?php if ($updateMode) : ?>
                <input type="hidden" name="fid" value="<?php echo $fidToUpdate; ?>">
                <button type="submit" name="update-faculty" class="btn btn-primary">Update Faculty</button>
            <?php else : ?>
                <button type="submit" name="add-faculty" class="btn btn-primary">Add Faculty</button>
            <?php endif; ?>
        </form>

        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Handle manual faculty entry or update
            if (isset($_POST['fid']) && isset($_POST['fname']) && isset($_POST['fdept']) && isset($_POST['femail']) && isset($_POST['fphno'])) {
                if ($updateMode) {
                    // Update faculty details in the database
                    $updateStmt = $conn->prepare("UPDATE faculty SET fname = ?, fdept = ?, femail = ?, fphno = ? WHERE fid = ?");
                    $updateStmt->bind_param("sssss", $fname, $fdept, $femail, $fphno, $fidToUpdate);
                    $updateStmt->execute();
                    $updateStmt->close();
                    echo "Faculty details updated successfully.";
                    $updateMode = false; // Reset update mode after update
                } else {
                    // Insert new faculty details into the database
                    $fpass = generateRandomPassword();
                    echo "Faculty details added to the database with a random password.";
                }
            }
        }
        if (isset($_POST['delete-faculty'])) {
            $fidToDelete = $_POST['fid'];
            // Perform deletion from the database
            $deleteStmt = $conn->prepare("DELETE FROM faculty WHERE fid = ?");
            $deleteStmt->bind_param("s", $fidToDelete);
            $deleteStmt->execute();
            $deleteStmt->close();
        }
        ?>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>