<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];  
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Default avatar
    $imagePath = "uploads/"; // Default image path

    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == 0) {
        // Get the uploaded file details
        $image = $_FILES["avatar"];
        $target_dir = "uploads/";

        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Define the target file path
        $image_type = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        if (in_array($image_type, $allowed_types)) {
            $target_file = $target_dir . basename($image["name"]);

            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $imagePath = $target_file; // Store the uploaded image path
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Insert user into database
    $query = "INSERT INTO user (name, email, address, password, avatar) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $email, $address, $password, $imagePath);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION['user_id'] = $conn->insert_id;
        header("Location: home.php");
        exit();
    } else {
        echo "Signup failed!";
    }

    $stmt->close();
}
$conn->close();

?>
