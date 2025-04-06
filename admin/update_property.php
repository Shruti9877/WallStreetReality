<?php
$host = 'localhost';
$db   = 'real_estate';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

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

if (isset($_POST['update'])) {
    $id = $_POST['property_id'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $area = $_POST['area'];
    $description = $_POST['description'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $target = 'uploads/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Update including image
        // Don't do this unless you intentionally don't want to use images
$stmt = $pdo->prepare("UPDATE properties SET title=?, type=?, price=?, bedrooms=?, bathrooms=?, area=?, description=? WHERE id=?");
$stmt->execute([$title, $type, $price, $bedrooms, $bathrooms, $area, $description, $id]);

    } else {
        // Update without changing image
        $stmt = $pdo->prepare("UPDATE properties SET title=?, type=?, price=?, bedrooms=?, bathrooms=?, area=?, description=? WHERE id=?");
        $stmt->execute([$title, $type, $price, $bedrooms, $bathrooms, $area, $description, $id]);
    }

    header("Location: dashboard.php?msg=Property+Updated");
    exit;
} else {
    echo "Invalid access.";
}
