<?php
// database.php - Database connection
$host = 'localhost';
$dbname = 'real_estate';
$username = 'root'; // Change if needed
$password = ''; // Change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]));
}

// signup.php - Admin Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['PHP_SELF'], 'signup.php') !== false) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already registered!']);
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $password])) {
        echo json_encode(['status' => 'success', 'message' => 'Signup successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Signup failed!']);
    }
    exit;
}

// login.php - Admin Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['PHP_SELF'], 'login.php') !== false) {
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password!']);
    }
    exit;
}