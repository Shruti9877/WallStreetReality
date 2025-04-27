<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHMailer\Exception;

// Include PHPMailer classes manually
require 'C:\wamp64\www\final\PHPMailer-PHPMailer-2128d99\src\Exception.php';
require 'C:\wamp64\www\final\PHPMailer-PHPMailer-2128d99\src\PHPMailer.php';
require 'C:\wamp64\www\final\PHPMailer-PHPMailer-2128d99\src\SMTP.php';

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "real_estate";

try {
    // Establishing database connection
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Collect form data and sanitize it
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$mobile = htmlspecialchars(trim($_POST['mobile']));
$date = $_POST['date'];
$time = $_POST['time'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email address.'); window.location.href='index.html';</script>";
    exit();
}

// Validate mobile number format (simple validation, adjust regex for more strict checks)
if (!preg_match("/^\d{10}$/", $mobile)) {
    echo "<script>alert('Invalid mobile number.'); window.location.href='index.html';</script>";
    exit();
}

// Save to the database
try {
    $stmt = $pdo->prepare("INSERT INTO appointments (name, email, mobile, date, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $mobile, $date, $time]);
} catch (PDOException $e) {
    die("Error saving appointment: " . $e->getMessage());
}

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'darkspiritt13@gmail.com'; // Your Gmail ID
    $mail->Password   = 'pixs xmqw dynb nuya';     // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender and recipient
    $mail->setFrom('darkspiritt13@gmail.com', 'Real Estate Appointment');
    $mail->addAddress($email, $name);

    // Email content with CSS
    $mail->isHTML(true);
    $mail->Subject = 'Appointment Confirmation';
    $mail->Body    = '
        <html>
        <head>
            <style>
                .container {
                    max-width: 600px;
                    margin: auto;
                    background: #f9f9f9;
                    padding: 20px;
                    border-radius: 10px;
                    font-family: Arial, sans-serif;
                    color: #333;
                    box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
                }
                .header {
                    background-color: #4CAF50;
                    padding: 10px;
                    border-radius: 10px 10px 0 0;
                    text-align: center;
                    color: white;
                }
                .content {
                    margin-top: 20px;
                    text-align: center;
                }
                .footer {
                    margin-top: 30px;
                    font-size: 12px;
                    text-align: center;
                    color: #888;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Appointment Confirmed</h2>
                </div>
                <div class="content">
                    <p>Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                    <p>Your appointment is successfully confirmed!</p>
                    <p><strong>Date:</strong> ' . htmlspecialchars($date) . '<br>
                    <strong>Time:</strong> ' . htmlspecialchars($time) . '</p>
                    <p>Thank you for choosing us. We look forward to seeing you!</p>
                </div>
                <div class="footer">
                    &copy; ' . date('Y') . ' Real Estate Company. All rights reserved.
                </div>
            </div>
        </body>
        </html>
    ';

    $mail->send();
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
    exit();
}

// Redirect or show confirmation
echo "<script>alert('Appointment booked successfully!'); window.location.href='index.html';</script>";
?>
