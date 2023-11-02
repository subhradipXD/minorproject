<?php
require '../connect.php'; // Include your database connection script
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Your custom CSS styles */
        .institution {
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .custom-class-1 {
            /* Your custom styles for this class */
            font-weight: bold;
            color: #333;
        }

        .custom-class-2 {
            /* Your custom styles for this class */
            background-color: #f0f0f0;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        /* Grid container styles */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Adjust the number of columns as needed */
            gap: 30px;
            margin: 30px auto;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <div id="institutions">
        <!-- PHP loop to generate institutions -->
        <?php
        // Query to fetch institutions data (modify as per your database structure)
        $sql = "SELECT instid, instname, instlogo FROM admin";
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result->num_rows > 0) {
            $institutions = [];

            // Fetch data and store it in the $institutions array
            while ($row = $result->fetch_assoc()) {
                $institutions[] = $row;
            }
        }
        // Assuming you have an array $institutions with institute data
        foreach ($institutions as $institution) {
            echo '<div class="institution">';
            echo '<img src="/minorproject/admin/' . $institution['instlogo'] . '" alt="' . $institution['instname'] . '" width="100">';
            echo '<p>' . $institution['instname'] . '</p>';
            echo "<button onclick=\"redirectToInstitute('$institution[instid]')\">View Details</button>";
            // echo $institution['instid'];
            echo '</div>';
        }
        ?>
    </div>

    <script>
        function redirectToInstitute(id) {
        // Here you can use JavaScript to redirect to the institute's details page.
            // Construct the URL dynamically based on the instituteId.
            // For example, redirect to a page like "institute-details.php?id=XXX"
            // where XXX is the institute's identifier.

            window.location.href = 'institute-details.php?id=' + id;
        }
    </script>
</body>

</html>