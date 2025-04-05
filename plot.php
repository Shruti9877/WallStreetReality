<?php

$svname = "localhost";      // Server name
$dbname = "real_estate";    // Database name
$username = "root";         // DB username
$password = "";             // DB password (keep empty if using XAMPP)

try {
    $pdo = new PDO("mysql:host=$svname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Always show only Villa properties
$type = 'Plot';

$query = "SELECT * FROM properties WHERE type = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$type]);
$properties = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Villa Listings</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .property-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .property-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .property-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .property-details {
            margin: 10px 0;
            color: #555;
        }

        .property-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .property-images img {
            width: 160px;
            height: auto;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        @media screen and (max-width: 600px) {
            .property-images img {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h2>Villas for Sale</h2>

<div class="property-list">
    <!-- This block will repeat for each property (dynamically rendered with PHP) -->
    <?php foreach ($properties as $prop): ?>
    <div class="property-box">
        <h3><?= $prop['title'] ?> (<?= $prop['type'] ?>)</h3>
        <div class="property-details">
            <p><strong>Location:</strong> <?= $prop['location'] ?> | <strong>Price:</strong> <?= $prop['price'] ?></p>
            <p><strong>Bedrooms:</strong> <?= $prop['bedrooms'] ?> | <strong>Bathrooms:</strong> <?= $prop['bathrooms'] ?> | <strong>Area:</strong> <?= $prop['area'] ?> sqft</p>
            <p><strong>Agent:</strong> <?= $prop['agent_name'] ?> | <strong>Status:</strong> <?= $prop['status'] ?></p>
        </div>
        <div class="property-images">
<?php
$imgs = explode(",", $prop['images']);
foreach ($imgs as $img) {
    $img = trim($img); // remove whitespace
    if (!empty($img)) {
        echo "<img src='admin/{$img}' alt='Property Image'>";
    }
}
?>
</div>


    </div>
    <?php endforeach; ?>
</div>

<!-- JavaScript: Placeholder for future use -->
<script>
    // You can add filtering or dynamic interaction here later
</script>

</body>
</html>
