<?php
$svname = "localhost";
$dbname = "real_estate";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$svname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Show only Villas
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
    <title>Plot Listings</title>
    <style>
          * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: rgb(240, 240, 240);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: white;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .logo-container {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .logo-container a {
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            color: #2c3e50;
        }

        .nav-links {
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 0;
            gap: 30px;
        }

        .nav-links li a {
            text-decoration: none;
            color: rgb(8, 0, 0);
            font-weight: 500;
            font-size: 16px;
            transition: color 0.2s ease;
        }

        .nav-links li a:hover {
            color: rgb(215, 73, 73);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin: 30px 0 20px;
        }

        .property-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 1600px;
            margin: auto;
            padding: 20px;
        }

        .property-box {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0,0,0,0.07);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .property-box:hover {
            transform: translateY(-5px);
        }

        .property-images img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid #eee;
        }

        .property-content {
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .property-box h3 {
            margin: 0 0 10px;
            color: #2c3e50;
            font-size: 20px;
        }

        .property-details p {
            margin: 6px 0;
            font-size: 14px;
            color: #555;
        }

        .price {
            font-size: 18px;
            color: #27ae60;
            font-weight: bold;
            margin-top: 10px;
        }

        .agent {
            margin-top: 10px;
            font-style: italic;
            color: #888;
        }

        .status {
            display: inline-block;
            background: #3498db;
            color: white;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-group button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .buy-btn {
            background-color: #27ae60;
            color: white;
        }

        .buy-btn:hover {
            background-color: #219150;
        }

        .map-btn {
            background-color: #2980b9;
            color: white;
        }

        .map-btn:hover {
            background-color: #216a94;
        }

        .wishlist-btn {
            background-color: #e67e22;
            color: white;
        }

        .wishlist-btn:hover {
            background-color: #cf6a17;
        }

        @media screen and (max-width: 1200px) {
            .property-list {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media screen and (max-width: 900px) {
            .property-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 600px) {
            .property-list {
                grid-template-columns: 1fr;
            }

            .property-images img {
                height: 180px;
            }

            .property-box h3 {
                font-size: 16px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group button {
                font-size: 13px;
            }

            .nav-container {
                flex-direction: column;
            }

            .logo-container {
                position: static;
                transform: none;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">
        <div class="logo-container">
            <a href="#">
                <img src="photos/es4.jpg" alt="Logo" class="logo" />
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="propertis.html">Property</a></li>
            <li><a href="profile.html">Profile</a></li>
            <li><a href="wishlist.html">Wishlist</a></li>
        </ul>
    </div>
</nav>

<h2>Plot for Sale </h2>

<div class="property-list">
    <?php foreach ($properties as $prop): ?>
        <div class="property-box">
            <div class="property-images">
                <?php
                $imgs = explode(",", $prop['images']);
                foreach ($imgs as $img) {
                    $img = trim($img);
                    if (!empty($img)) {
                        echo "<img src='admin/{$img}' alt='Property Image'>";
                    }
                }
                ?>
            </div>
            <div class="property-content">
                <h3><?= htmlspecialchars($prop['title']) ?> (<?= htmlspecialchars($prop['type']) ?>)</h3>
                <div class="property-details">
                    <p><strong>Location:</strong> <?= htmlspecialchars($prop['location']) ?> |
                       <strong>Price:</strong> <?= htmlspecialchars($prop['price']) ?></p>
                    <p><strong>Bedrooms:</strong> <?= $prop['bedrooms'] ?> |
                       <strong>Bathrooms:</strong> <?= $prop['bathrooms'] ?> |
                       <strong>Area:</strong> <?= $prop['area'] ?> sqft</p>
                    <p><strong>Agent:</strong> <?= htmlspecialchars($prop['agent_name']) ?> |
                       <strong>Status:</strong> <?= htmlspecialchars($prop['status']) ?></p>
                </div>
                <div class="btn-group">
                    <button class="buy-btn" onclick="window.open('inquiry.php')">Inquiry</button>
                    <button class="map-btn" onclick="window.open('https://www.google.com/maps/search/<?= urlencode($prop['location']) ?>', '_blank')">View on Map</button>
                    <button class="wishlist-btn" onclick='addToWishlist(<?= json_encode($prop, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Wishlist</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Wishlist Logic -->
<script>
  function addToWishlist(property) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

    const exists = wishlist.some(item => item.name === property.title);
    if (!exists) {
      wishlist.push({
        name: property.title,
        price: property.price,
        image: 'admin/' + (property.images.split(',')[0] || '').trim()
      });
      localStorage.setItem('wishlist', JSON.stringify(wishlist));
      alert("Added to wishlist!");
    } else {
      alert("Already in wishlist!");
    }
  }
</script>

</body>
</html>
