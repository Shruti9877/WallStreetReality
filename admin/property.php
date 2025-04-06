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

// Pagination setup
$limit = 10; // properties per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Total rows
$totalStmt = $pdo->query("SELECT COUNT(*) FROM properties");
$totalProperties = $totalStmt->fetchColumn();
$totalPages = ceil($totalProperties / $limit);

// Fetch paginated data
$stmt = $pdo->prepare("SELECT * FROM properties ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Properties - Admin Panel</title>
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
        .properties-container {
            margin-top: 20px;
            background: #1E293B;
            padding: 20px;
            border-radius: 10px;
        }
        .add-button {
            background: #3B82F6;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .add-button:hover {
            background: #2563EB;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #374151;
        }
        th {
            background: #374151;
        }
        .actions i {
            cursor: pointer;
            margin: 0 5px;
        }
        .edit {
            color: #FACC15;
        }
        .delete {
            color: #EF4444;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background: #374151;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .pagination a:hover {
            background: #4B5563;
        }
        .pagination a.active {
            background: #3B82F6;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="property.php"><i class="fas fa-building"></i> Manage Properties</a>
        <a href="users.php"><i class="fas fa-users"></i> Manage Users</a>
        <a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">Manage Properties</div>

        <div class="properties-container">
            <a href="add_proparty.html" class="add-button"><i class="fas fa-plus"></i> Add New Property</a>
            
            <table>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= htmlspecialchars($property['id']) ?></td>
                    <td><?= htmlspecialchars($property['title']) ?></td>
                    <td><?= htmlspecialchars($property['location']) ?></td>
                    <td><?= htmlspecialchars($property['price']) ?></td>
                    <td><?= htmlspecialchars($property['status']) ?></td>
                                
                    <td class="actions">
                    <a href="edit_property.php?id=<?= $property['id'] ?>">Edit</a>

                


                    </td>
                </tr>
            <?php endforeach; ?>

            </table>
        </div>
        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
