<?php
// Database connection
$servername = "localhost";
$username = "root"; // Database username
$password = "";     // Database password
$dbname = "real_estate"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data (assuming the user is logged in and their email is stored in a session)
session_start();

if (!isset($_SESSION['user_email'])) {
    echo "Please log in first.";
    exit;
}

$user_email = $_SESSION['user_email'];

// Query to fetch user details
$sql = "SELECT name, email, phone, profile_pic FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$stmt->close();
$conn->close();
?>