<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "real_estate";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        // Debugging - Check what is being fetched
        error_log("Fetched password: " . $hashedPassword);
        error_log("Entered password: " . $password);

        if (password_verify($password, $hashedPassword)) {
            // Debugging - Successful login
            error_log("Login successful for user: " . $email);
            
            // Redirect to dashboard on successful login
            header("Location: dashboard.html");
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email not found"]);
    }

    $stmt->close();
}

$conn->close();
?>
