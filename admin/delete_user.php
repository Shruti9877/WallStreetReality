<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    echo "unauthorized";
    exit();
}

$svname = "localhost";
$dbname = "real_estate";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$svname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "error";
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
    $result = $stmt->execute([$id]);

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // AJAX request
        echo $result ? "success" : "fail";
    } else {
        // Normal browser request
        header("Location: users.php");
    }
} else {
    echo "invalid";
}
