<?php
require '../connect.php'; // Include the database connection file


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h2>Welcome to the Platform</h2>

    <button onclick="location.href='../Admin/adminreg.php'">New Registration for Institution</button>
    <button onclick="location.href='../Admin/adminlogin.php'">Login as Institution's Admin</button>
    <button onclick="location.href='../Faculty/facultylogin.php'">Login as Institution's Faculty</button>

    <h3>Search</h3>

    <button onclick="location.href='allinst.php'">Search for All Institutions</button>
    <button onclick="location.href='alleventlist.php'">Search by Events</button>

</body>

</html>