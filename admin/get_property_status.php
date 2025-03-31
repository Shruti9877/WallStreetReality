<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "real_estate";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$response = [];

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get property status based on type
$property_type = isset($_GET['property_type']) ? $_GET['property_type'] : 'Apartment';

$sql = "SELECT 
            SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) AS available,
            SUM(CASE WHEN status = 'Sold' THEN 1 ELSE 0 END) AS sold,
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending
        FROM properties
        WHERE property_type = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $property_type);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $response = [
        'available' => $data['available'],
        'sold' => $data['sold'],
        'pending' => $data['pending']
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
    