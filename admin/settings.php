<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
header("Location: login.html");
    echo json_encode(["error" => "User not logged in"]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Panel</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
        .settings-container {
            background: #1E293B;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .settings-container h3 {
            margin-bottom: 10px;
            border-bottom: 1px solid #374151;
            padding-bottom: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
            background: #374151;
            color: white;
        }
        .save-btn {
            background: #3B82F6;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .save-btn:hover {
            background: #2563EB;
        }
        .dark-mode-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: #374151;
            border-radius: 5px;
            cursor: pointer;
        }
        .dark-mode-toggle span {
            margin-left: 10px;
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
        <a href="logout.html"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">Settings</div>

        <div class="settings-container">
            <!-- General Settings -->
            <h3>General Settings</h3>
            <div class="form-group">
                <label for="site-name">Website Name</label>
                <input type="text" id="site-name" value="My Real Estate">
            </div>
            <div class="form-group">
                <label for="logo-upload">Upload Logo</label>
                <input type="file" id="logo-upload">
            </div>

            <!-- Admin Account Settings -->
            <h3>Admin Account</h3>
            <div class="form-group">
                <label for="admin-email">Admin Email</label>
                <input type="email" id="admin-email" value="admin@example.com">
            </div>
            <div class="form-group">
                <label for="admin-password">New Password</label>
                <input type="password" id="admin-password">
            </div>

            <!-- Property Settings -->
            <h3>Property Settings</h3>
            <div class="form-group">
                <label for="property-listing">Property Listing</label>
                <select id="property-listing">
                    <option value="enabled">Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>

            <!-- Dark Mode Toggle -->
            <h3>Appearance</h3>
            <div class="dark-mode-toggle" onclick="toggleDarkMode()">
                <span>Dark Mode</span>
                <i class="fas fa-moon"></i>
            </div>

            <br>
            <button class="save-btn" onclick="saveSettings()">Save Changes</button>
        </div>
    </div>

    <script>
        function saveSettings() {
            const siteName = document.getElementById("site-name").value;
            const adminEmail = document.getElementById("admin-email").value;
            const propertyListing = document.getElementById("property-listing").value;
            
            alert("Settings Saved:\n" +
                  "Website Name: " + siteName + "\n" +
                  "Admin Email: " + adminEmail + "\n" +
                  "Property Listing: " + propertyListing);
        }

        function toggleDarkMode() {
            document.body.classList.toggle("dark-theme");
        }
    </script>
</body>
</html>
