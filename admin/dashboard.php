<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
header("Location: login.html");
    echo json_encode(["error" => "User not logged in"]);
    exit();
}
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

// Fetch all properties
$stmt = $pdo->query("SELECT * FROM properties ORDER BY id DESC");
$user = $pdo->query("SELECT * FROM user ORDER BY id DESC");
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
$users = $user->fetchAll(PDO::FETCH_ASSOC);
$soldProperties = array_filter($properties, function($property) {
    return isset($property['status']) && $property['status'] === 'Sold';
});
$availableProperties = array_filter($properties, function($property) {
    return isset($property['status']) && $property['status'] === 'Available';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wall Street Reality</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background: #0F172A;
            color: white;
        }
        .sidebar {
            width: 250px;
            background: #1E293B;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #374151;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .header {
            background: #111827;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            border-bottom: 2px solid #374151;
        }
        .dashboard-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .card {
            background: #1E293B;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .card h3 {
            margin: 0;
            font-size: 18px;
            color: #93C5FD;
        }
        .card p {
            font-size: 22px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="property.php"><i class="fas fa-building"></i> Manage Properties</a>
        <a href="users.php"><i class="fas fa-users"></i> User Management</a>
        <a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main-content">
        <div class="header">Welcome Back, Admin!</div>
        <div class="dashboard-container">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo count($users); ?></p>
            </div>
            <div class="card">
                <h3>Total Properties</h3>
                <p><?php echo count($properties); ?></p>
            </div>
            <div class="card">
                <h3>Sold Properties</h3>
                <p><?php echo count($soldProperties); ?></p>
            </div>
            <div class="card">
                <h3>Available Properties</h3>
                <p><?php echo count($availableProperties); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
