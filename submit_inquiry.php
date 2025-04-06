<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'C:\wamp64\www\wallStreetReality-1\PHPMailer-PHPMailer-2128d99\src\Exception.php';
require 'C:\wamp64\www\wallStreetReality-1\PHPMailer-PHPMailer-2128d99\src\PHPMailer.php';
require 'C:\wamp64\www\wallStreetReality-1\PHPMailer-PHPMailer-2128d99\src\SMTP.php';

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $property_id = intval($_POST['property_id']);
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($phone) || empty($property_id) || empty($message)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Database connection
    $dsn = 'mysql:host=localhost;dbname=real_estate;charset=utf8mb4';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch title and location
        $stmt = $pdo->prepare("SELECT title, location FROM properties WHERE id = ?");
        $stmt->execute([$property_id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$property) {
            echo "Invalid property selected.";
            exit;
        }

        $property_title = $property['title'];
        $property_address = $property['location'];

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'darkspiritt13@gmail.com'; // Your Gmail
        $mail->Password   = 'pixs xmqw dynb nuya';      // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($email, $name);
        $mail->addAddress('devpatel472005@gmail.com', 'Wall Street Reality');

        $mail->isHTML(true);
        $mail->Subject = "New Property Inquiry from $name";

        $mail->Body = "
        <html>
        <head>
          <style>
            .email-container {
              font-family: Arial, sans-serif;
              max-width: 600px;
              margin: auto;
              border: 1px solid #ddd;
              padding: 20px;
              border-radius: 10px;
              background-color: #f9f9f9;
            }
            .email-header {
              background-color: #2c3e50;
              color: #fff;
              padding: 15px;
              text-align: center;
              font-size: 18px;
              border-radius: 8px 8px 0 0;
            }
            .email-body {
              padding: 20px;
              line-height: 1.6;
              color: #333;
            }
            .email-footer {
              margin-top: 30px;
              font-size: 12px;
              color: #888;
              text-align: center;
            }
            .label {
              font-weight: bold;
            }
          </style>
        </head>
        <body>
          <div class='email-container'>
            <div class='email-header'>New Property Inquiry</div>
            <div class='email-body'>
              <p><span class='label'>Name:</span> {$name}</p>
              <p><span class='label'>Email:</span> {$email}</p>
              <p><span class='label'>Phone:</span> {$phone}</p>
              <p><span class='label'>Property:</span> {$property_title} (ID: {$property_id})</p>
              <p><span class='label'>Location:</span> {$property_address}</p>
              <p><span class='label'>Message:</span><br>{$message}</p>
            </div>
            <div class='email-footer'>
              This message was sent via the Wall Street Reality inquiry form.
            </div>
          </div>
        </body>
        </html>
        ";

        $mail->send();
        echo "Thank you for your inquiry. We will contact you soon.";
    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
?>
