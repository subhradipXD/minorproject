<?php
require '../connect.php'; // Include the database connection file

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
                $stmt = $conn->prepare("SELECT COUNT(*) FROM faculty WHERE fid = ? OR femail = ?");
                $stmt->bind_param("ss", $fid, $femail);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();

                if ($count > 0) {
                    $duplicateEntries[] = $fid;
                } else {
                    // Generate a random password
                    $data[] = generateRandomPassword();

                    $dataArray[] = $data;
                }
            }

            fclose($handle);

            if (!empty($duplicateEntries)) {
                echo "Duplicate entries found for the following faculty IDs: " . implode(', ', $duplicateEntries);
            }

            if (!empty($dataArray)) {
                try {
                    $sql = "INSERT INTO faculty (fid, fname, fdept, femail, fphno, fpass) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    foreach ($dataArray as $row) {
                        $stmt->execute($row);
                    }

                    echo "CSV data has been successfully inserted into the database.";
                } catch (PDOException $e) {
                    echo "Database Error: " . $e->getMessage();
                }
            }
        } else {
            echo "Please upload a valid CSV file.";
        }
    } else {
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

            try {
                $sql = "INSERT INTO faculty (fid, fname, fdept, femail, fphno, fpass) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$fid, $fname, $fdept, $femail, $fphno, $fpass]);

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
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch faculty details from the database
            $sql = "SELECT fid, fname, fdept, femail, fphno FROM faculty";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["fid"] . "</td>";
                    echo "<td>" . $row["fname"] . "</td>";
                    echo "<td>" . $row["fdept"] . "</td>";
                    echo "<td>" . $row["femail"] . "</td>";
                    echo "<td>" . $row["fphno"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No faculty details found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>
