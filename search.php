<?php
// Database configuration
$host = 'localhost';
$dbName = 'properties_db';
$username = 'root';
$password = '';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch search query
$query = isset($_GET['q']) ? $_GET['q'] : '';

if (!empty($query)) {
    // SQL query to search for properties by name or address
    $sql = $conn->prepare("SELECT name, address, price, lat, lng FROM properties WHERE name LIKE ? OR address LIKE ?");
    $searchParam = "%{$query}%";
    $sql->bind_param("ss", $searchParam, $searchParam);
    $sql->execute();
    $result = $sql->get_result();

    $properties = [];

    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }

    echo json_encode($properties);
} else {
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>
