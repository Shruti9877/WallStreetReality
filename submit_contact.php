<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\wamp64\www\wallStreetReality-1\PHPMailer-PHPMailer-2128d99\src\Exception.php';
require 'C:\wamp64\www\wallStreetReality-1\PHPMailer-PHPMailer-2128d99\src\PHPMailer.php';
require 'C:\wamp64\www\wallStreetReality-1\PHPMailer-PHPMailer-2128d99\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars(trim($_POST["phone"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($name) && !empty($email) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'darkspiritt13@gmail.com'; // 🔁 Replace with your Gmail
            $mail->Password   = 'pixs xmqw dynb nuya';   // 🔁 Use Gmail App Password (see below)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Sender & Recipient
            $mail->setFrom($email, $name);
            $mail->addAddress('darkspiritt13@gmail.com', 'Admin'); // 🔁 Your admin email

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Submission from $name";
            $mail->Body    = "
                <strong>Name:</strong> $name <br>
                <strong>Email:</strong> $email <br>
                <strong>Phone:</strong> $phone <br><br>
                <strong>Message:</strong><br> $message
            ";

            $mail->send();
            echo "<h2>Thank you! Your message has been sent successfully.</h2>";
        } catch (Exception $e) {
            echo "<h2>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h2>";
        }
    } else {
        echo "<h2>Please fill out all required fields.</h2>";
    }
} else {
    echo "<h2>Invalid request.</h2>";
}
?>
