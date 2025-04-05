<?php
// Database configuration
$host = 'localhost';
$db   = 'real_estate';
$user = 'root';
$pass = ''; // Add password if needed
$charset = 'utf8mb4';

// Create a PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Collect form data
$title = $_POST['title'];
$price = $_POST['price'];
$location = $_POST['location'];
$type = $_POST['type'];
$bedrooms = $_POST['bedrooms'];
$bathrooms = $_POST['bathrooms'];
$area = $_POST['area'];
$description = $_POST['description'];
$status = $_POST['status'];
$agent_name = $_POST['agent_name'];
$agent_contact = $_POST['agent_contact'];

// Image upload handling
$image_paths = [];
if (!empty($_FILES['images']['name'][0])) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    foreach ($_FILES['images']['name'] as $key => $name) {
        $tmp_name = $_FILES['images']['tmp_name'][$key];
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $new_name = uniqid('property_', true) . '.' . $ext;
        $target_file = $upload_dir . $new_name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $image_paths[] = $target_file;
        }
    }
}

$images_string = implode(',', $image_paths);

// Insert into DB
$sql = "INSERT INTO properties 
        (title, price, location, type, bedrooms, bathrooms, area, description, status, agent_name, agent_contact, images) 
        VALUES 
        (:title, :price, :location, :type, :bedrooms, :bathrooms, :area, :description, :status, :agent_name, :agent_contact, :images)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':title' => $title,
    ':price' => $price,
    ':location' => $location,
    ':type' => $type,
    ':bedrooms' => $bedrooms,
    ':bathrooms' => $bathrooms,
    ':area' => $area,
    ':description' => $description,
    ':status' => $status,
    ':agent_name' => $agent_name,
    ':agent_contact' => $agent_contact,
    ':images' => $images_string
]);

echo "<script>alert('Property added successfully!'); window.location.href='add_form.html';</script>";
?>
