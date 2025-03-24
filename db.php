<?php
$host = "localhost";
$user = "root"; // Change if you have a different username
$pass = ""; // Change if you have a password
$db = "user_dashboard";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
