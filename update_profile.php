<?php
session_start();
header('Content-Type: application/json');
require 'dbconnection.php';

// Simulate user_id for testing if session is not active
$user_id = $_SESSION['user_id'] ?? 1; // Make sure to replace this with real session usage in production

// Validate inputs
if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['address'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$avatarPath = null;

// Handle profile image if uploaded
if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileType = mime_content_type($fileTmpPath);

    if (in_array($fileType, $allowedTypes)) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $filename = uniqid('avatar_') . '.' . $ext;
        $uploadDir = 'uploads/';
        $avatarPath = $uploadDir . $filename;

        if (!move_uploaded_file($fileTmpPath, $avatarPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, and GIF images are allowed.']);
        exit;
    }
}

try {
    if ($avatarPath) {
        $sql = "UPDATE user SET name = ?, phone = ?, address = ?, avatar = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $phone, $address, $avatarPath, $user_id]);
    } else {
        $sql = "UPDATE user SET name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $phone, $address, $user_id]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
