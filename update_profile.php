<?php
session_start();
include 'config.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Handle file upload
    if (!empty($_FILES['avatar']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
    } else {
        $target_file = $user['avatar']; // Keep existing avatar if no new file is uploaded
    }

    // Update user details (excluding email)
    $update_query = "UPDATE users SET name = ?, phone = ?, address = ?, avatar = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssi", $name, $phone, $address, $target_file, $user_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: profile.php"); // Redirect back to profile page
        exit();
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}
?>
