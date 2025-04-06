<?php
header('Content-Type: application/json');

$dsn = 'mysql:host=localhost;dbname=real_estate;charset=utf8mb4';
$username = 'root';
$password = '';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'Missing ID']);
    exit;
}

$propertyId = intval($_GET['id']);

try {
    $pdo = new PDO($dsn, $username, $password);
    $stmt = $pdo->prepare("SELECT image FROM properties WHERE id = ?");
    $stmt->execute([$propertyId]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($property && !empty($property['image'])) {
        $imagePath = 'uploads/' . $property['image'];
        if (file_exists($imagePath)) {
            echo json_encode([
                'success' => true,
                'image' => $imagePath
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Image file not found on server']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Image not found in database']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
