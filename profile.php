<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION["email"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$email = $_SESSION["email"];
$query = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    exit();
}

echo json_encode($user);
?>
