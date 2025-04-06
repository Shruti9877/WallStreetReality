<?php
session_start();
include 'db_connection.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and assign form inputs
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    // Validate input fields
    if (empty($name) || empty($phone) || empty($address)) {
        $error_message = "All fields are required.";
    } else {
        // Handle file upload
        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
        } else {
            $target_file = $user['avatar']; // Keep existing avatar if no new file is uploaded
        }

        // Check if 'phone' column exists in the database
        $check_columns = $conn->query("SHOW COLUMNS FROM user LIKE 'phone'");
        if ($check_columns->num_rows === 0) {
            $error_message = "Error: 'phone' column does not exist in the database.";
        } else {
            // Update user details (excluding email)
            $update_query = "UPDATE user SET name = ?, phone = ?, address = ?, avatar = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ssssi", $name, $phone, $address, $target_file, $user_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Profile updated successfully!";
                header("Location: profile.php?updated=" . time()); // Force refresh
                exit();
            } else {
                $error_message = "Error updating profile. Please try again.";
            }
            
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #f7f6f6, #f2d7d5);
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .edit-form {
            background: #ffffff;
            padding: 25px;
            width: 400px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        input, button {
            width: 90%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background: #00094d;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #333399;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

    <div class="edit-form">
        <h2>Edit Profile</h2>
        
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <?php if (isset($_SESSION['success_message'])) {
            echo "<p class='success'>".$_SESSION['success_message']."</p>";
            unset($_SESSION['success_message']);
        } ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label>Profile Image:</label><br>
            <input type="file" name="avatar"><br>
            <img src="<?php echo $user['avatar'] ? $user['avatar'] : 'uploads/'; ?>" width="100" height="100" style="border-radius:50%;"><br>

            <label>Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required><br>

            <label>Email (cannot be changed):</label><br>
            <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled><br>

            <label>Phone:</label><br>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required><br>

            <label>Address:</label><br>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required><br>
            
            <button type="submit">Save Changes</button>
            
        </form>
    </div>

</body>
</html>
