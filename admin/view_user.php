<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: login.html");
    exit();
}

$svname = "localhost";
$dbname = "real_estate";
$username = "root";
$password = "";

// Database Connection
try {
    $pdo = new PDO("mysql:host=$svname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid property ID");
}

// Fetch property by ID
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$id]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    die("Property not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User Details</title>
    <style>
        body {
            background-color: #0F172A;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #1E293B;
            padding: 30px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #3B82F6;
        }
        .property-detail {
            margin: 20px 0;
            padding: 15px;
            background-color: #111827;
            border-radius: 8px;
        }
        .label {
            font-weight: bold;
            color: #FACC15;
        }
        .value {
            margin-left: 10px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #3B82F6;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #2563EB;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Details</h2>

        <div class="property-detail"><span class="label">NAME:</span><span class="value"><?= htmlspecialchars($property['name']) ?></span></div>
        <div class="property-detail"><span class="label">EMAIL ADDRESS:</span><span class="value"><?= htmlspecialchars($property['email']) ?></span></div>
        <div class="property-detail"><span class="label">Location:</span><span class="value"><?= htmlspecialchars($property['address']) ?></span></div>
        <div class="property-detail"><span class="label">CONTACT NUMBER:</span><span class="value"><?= $property['phone'] ? htmlspecialchars($property['phone']):'N/A'; ?></span></div>
      

        <a href="../users.php" class="back-button">← Back to Users</a>
    </div>
</body>
</html>
