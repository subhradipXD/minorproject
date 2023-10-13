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

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue;
            }

            // Generate a random password
            $data[] = generateRandomPassword();

            $dataArray[] = $data;
        }

        fclose($handle);

        try {
            $sql = "INSERT INTO faculty (fid, fname, fdept, femail, fphno, fpass) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            foreach ($dataArray as $row) {
                $stmt->execute($row);
            }

            echo "CSV data has been successfully inserted into the database.";

            // echo "<pre>";
            // print_r($dataArray);
            // echo "</pre>";
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }
    } else {
        echo "Please upload a valid CSV file.";
    }
}

// Close the database connection
$conn = null;
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
</body>

</html>