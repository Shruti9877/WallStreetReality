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
    <title>Real Estate Analytics - Admin Panel</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .stat-box {
            background: #1E293B;
            padding: 20px;
            border-radius: 10px;
            width: 23%;
            text-align: center;
        }
        .stat-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .stat-box p {
            font-size: 22px;
            font-weight: bold;
        }
        .charts-container {
            margin-top: 20px;
            background: #1E293B;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
        }
        .chart-box {
            width: 48%;
        }
        .chart-row {
            display: flex;
            gap: 20px;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .chart-container {
            flex: 1 1 45%;
            min-width: 300px;
            background: #1E293B;
        }

        canvas {
            width: 100% !important;
            height: auto !important;
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
        <div class="header">Property Analytics</div>
        <script>
            async function updateStats() {
                propertyBarChart.data.datasets[0].data = [data.available, data.sold, data.pending];
                propertyBarChart.update();

            }
        
            // Refresh stats every 5 seconds
            setInterval(updateStats, 5000);
        </script>
        

        <!-- Stats Section -->
        <div class="stats-container">
            <div class="card">
                <h3>Total Properties</h3>
                <p id="total_properties"><?php echo count($properties); ?></p>
            </div>
            <div class="card">
                <h3>Sold Properties</h3>
                <p id="sold_properties"><?php echo count($soldProperties); ?></p>
            </div>
            <div class="card">
                <h3>Available Properties</h3>
                <p id="available_properties"><?php echo count($availableProperties); ?></p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="chart-row">
            <div class="chart-container">
                <h4>Property Status</h4>
                <canvas id="propertyChart"></canvas>
            </div>

            <div class="chart-container">
                <h4>Revenue</h4>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <script>
    // Fetching the data directly from the DOM
    const available = parseInt(document.getElementById("available_properties").innerText);
    const sold = parseInt(document.getElementById("sold_properties").innerText);
    const total = parseInt(document.getElementById("total_properties").innerText);

    // Calculating pending properties
    const pending = total - (available + sold);

    // Initialize Pie Chart (Property Status Distribution)
    const pieCtx = document.getElementById('propertyChart').getContext('2d');
    const propertyChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Sold', 'Pending'],
            datasets: [{
                label: 'Properties',
                data: [available, sold, pending],
                backgroundColor: ['#009900', '#EF4444', '#ff9900'],
                borderColor: ['#ffffff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: false
                }
            }
        }
    });

    // Initialize Line Chart (Dummy Revenue Trend Over Time)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue ($)',
                data: [800000, 850000, 900000, 950000, 1000000, 1100000],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

    
</body>
</html>
