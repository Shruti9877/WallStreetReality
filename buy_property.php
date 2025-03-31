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
    $property_id = $_POST['property_id'];
    $buyer_name = $_POST['buyer_name'];
    $buyer_email = $_POST['buyer_email'];

    // Validate input
    if (!empty($property_id) && !empty($buyer_name) && !empty($buyer_email)) {
        // Insert into purchases table
        $stmt = $conn->prepare("INSERT INTO purchases (property_id, buyer_name, buyer_email) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $property_id, $buyer_name, $buyer_email);

        if ($stmt->execute()) {
            // ✅ Update property status to SOLD after purchase
            $update = $conn->prepare("UPDATE properties SET status = 'Sold' WHERE id = ?");
            $update->bind_param("i", $property_id);
            $update->execute();

            echo json_encode(["status" => "success", "message" => "Property purchased successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to purchase property"]);
        }

        $stmt->close();
        $update->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
    }
}

$conn->close();
?>
