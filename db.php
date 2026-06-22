<?php

$servername = "database-1.csj86e8qy90h.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "admin123";
$dbname = "userdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>