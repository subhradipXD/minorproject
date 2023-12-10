<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the index.php page after logout
header("location: ../users/index.php");
exit;
?>
