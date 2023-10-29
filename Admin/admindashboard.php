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

                // Check for duplicate entries by fid or femail
                // OR femail = ?
                // , $femail
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

// Close the database connection
// $conn = null;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Upload and Process CSV Data</title>
    <link rel="stylesheet" type="text/css" href="csv.css">
</head>

<body>
    <h1>Upload and Process CSV Data</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
        <input type="submit" name="upload" value="Upload">
    </form>

    <h2>Manual Faculty Entry</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="fid">Faculty ID:</label>
        <input type="text" name="fid" required><br>

        <label for="fname">Faculty Name:</label>
        <input type="text" name="fname" required><br>

        <label for="fdept">Faculty Department:</label>
        <input type="text" name="fdept" required><br>

        <label for="femail">Faculty Email:</label>
        <input type="email" name="femail" required><br>

        <label for="fphno">Faculty Phone Number:</label>
        <input type="text" name="fphno" required><br>

        <input type="submit" name="add-faculty" value="Add Faculty">
    </form>

    <h2>Faculty Details</h2>
    <table>
        <thead>
            <tr>
                <th>Faculty ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Actions</th> <!-- Added a new column for actions -->
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
                    // Adding update and delete buttons with hidden inputs for faculty ID
                    echo "<td>
                            <form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">
                                <input type=\"hidden\" name=\"update-fid\" value=\"" . $row["fid"] . "\">
                                <input type=\"submit\" name=\"update-faculty\" value=\"Update\">
                            </form>
                            <form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">
                                <input type=\"hidden\" name=\"delete-fid\" value=\"" . $row["fid"] . "\">
                                <input type=\"submit\" name=\"delete-faculty\" value=\"Delete\">
                            </form>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No faculty details found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Update and Delete Faculty sections go here -->
    <h2>Update Faculty Details</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="update-fid">Faculty ID:</label>
        <input type="text" name="update-fid" required>
        <input type="submit" name="update-faculty" value="Update Faculty">
    </form>

    <h2>Delete Faculty</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="delete-fid">Faculty ID:</label>
        <input type="text" name="delete-fid" required>
        <input type="submit" name="delete-faculty" value="Delete Faculty">
    </form>

    <!-- Update Faculty Details Section -->
    <?php if (isset($_POST['update-faculty'])) {
        $update_fid = $_POST['update-fid'];
        // You can add code here to fetch the existing faculty details for update, or just display a form for updating details.
        // If you display a form for updating, make sure to pre-fill the existing details.

        // For example:
        echo "<h2>Update Faculty Details for Faculty ID: $update_fid</h2>";
        echo "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">";
        echo "<label for=\"new-fname\">New Name:</label>";
        echo "<input type=\"text\" name=\"new-fname\">";
        echo "<label for=\'new-fdept'>New Department:</label>";
        echo "<input type='text' name='new-fdept'>";
        echo "<label for='new-femail'>New Email:</label>";
        echo "<input type='email' name='new-femail'>";
        echo "<label for='new-fphno'>New Phone Number:</label>";
        echo "<input type='text' name='new-fphno'>";
        echo "<input type='hidden' name='update-faculty-id' value='$update_fid'>";
        echo "<input type='submit' name='confirm-update-faculty' value='Confirm Update'>";
        echo "</form>";
    }
    ?>

    <!-- Confirm Update Faculty Details Section -->
    <?php if (isset($_POST['confirm-update-faculty'])) {
        $new_fid = $_POST['update-faculty-id'];
        $new_fname = $_POST['new-fname'];
        $new_fdept = $_POST['new-fdept'];
        $new_femail = $_POST['new-femail'];
        $new_fphno = $_POST['new-fphno'];

        // You can add code here to update the faculty details in the database for the specified faculty ID ($new_fid).
        // Perform an SQL UPDATE query to modify the faculty details with the new values.

        $update_sql = "UPDATE faculty SET fname=?, fdept=?, femail=?, fphno=? WHERE fid=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $new_fname, $new_fdept, $new_femail, $new_fphno, $new_fid);
        $stmt->execute();

        // After the update is performed, you can display a success message.
        echo "Faculty details for Faculty ID $new_fid have been updated successfully.";
    }
    ?>

    <!-- Delete Faculty Section -->
    <?php if (isset($_POST['delete-faculty'])) {
        $delete_fid = $_POST['delete-fid'];

        // You can add code here to delete the faculty with the specified faculty ID ($delete_fid) from the database.

        $delete_sql = "DELETE FROM faculty WHERE fid=?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $delete_fid);
        $stmt->execute();

        // After the deletion is performed, you can display a success message.
        echo "Faculty with Faculty ID $delete_fid has been deleted from the database.";
    }
    ?>

</body>

</html>