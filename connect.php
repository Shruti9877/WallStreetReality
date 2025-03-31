<?php
$servername = "localhost"; // change with your database server
$username = "root"; // change with your database username
$password = ""; // change with your database password
$dbname = "wal_street"; // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
