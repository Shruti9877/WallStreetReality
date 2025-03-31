<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes manually
require 'C:\wamp64\www\final\PHPMailer-PHPMailer-2128d99\src\Exception.php';
require 'C:\wamp64\www\final\PHPMailer-PHPMailer-2128d99\src\PHPMailer.php';
require 'C:\wamp64\www\final\PHPMailer-PHPMailer-2128d99\src\SMTP.php';

// Retrieve customer email and other details from POST data
$customerName = isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : '';
$customerEmail = isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : '';
$homeDetails = isset($_POST['home-details']) ? htmlspecialchars($_POST['home-details']) : '';
$homeId = isset($_POST['home-id']) ? htmlspecialchars($_POST['home-id']) : '';
$propertyId = isset($_POST['property_id']) ? htmlspecialchars($_POST['property_id']) : '';

if (empty($customerEmail)) {
    echo 'No email address provided.';
    exit;
}

// Generate a 6-digit OTP
$otp = mt_rand(100000, 999999);

// Store the OTP and expiration time in the session
session_start();
$_SESSION['otp'] = $otp;
$_SESSION['otp_expiration'] = time() + 60 ; // OTP expires in 5 minutes

// Create a new PHPMailer object
$mail = new PHPMailer(true);

try {
    // Configure SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'darkspiritt13@gmail.com';
    $mail->Password = 'pixs xmqw dynb nuya'; // Ensure this is the correct app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Set email format and recipients
    $mail->setFrom('confirmation@registered-domain.com', 'FashionFlaire');
    $mail->addAddress($customerEmail, $customerName);
    $mail->Subject = 'Thanks for choosing Our home - OTP Confirmation';
    $mail->isHTML(true);

    // Email content with CSS for the table layout
    $mail->Body = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
            }
            .email-container {
                max-width: 600px;
                margin: auto;
                padding: 20px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
            }
            .email-header {
                text-align: center;
                background-color: #788d90;
                color: #9cbec2;
                padding: 10px 0;
                font-size: 24px;
                font-weight: bold;
            }
            .email-body {
                padding: 20px;
            }
            .email-body p {
                font-size: 16px;
                line-height: 1.6;
            }
            .email-table {
                width: 100%;
                border-collapse: collapse;
            }
            .email-table th, .email-table td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            .email-table th {
                background-color: #f2f2f2;
                text-align: left;
            }
            .otp-box {
                background-color: #fb9c9c;
                padding: 10px;
                font-size: 18px;
                font-weight: bold;
                text-align: center;
                margin: 20px 0;
                color: #fa5858; /* Peach color */
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                Thanks for Choosing Our Home
            </div>
            <div class='email-body'>
                <p>Hi {$customerName},</p>
                <p>We are happy to confirm your booking. Please check the document in the attachment.</p>
                
                <div class='otp-box'>
                    Your One-Time Password (OTP) is: <strong>{$otp}</strong>
                </div>

                <table class='email-table'>
                    <tr>
                        <th>Home Details</th>
                        <td>{$homeDetails}</td>
                    </tr>
                    <tr>
                        <th>Home ID</th>
                        <td>{$homeId}</td>
                    </tr>
                    <tr>
                        <th>Property ID</th>
                        <td>{$propertyId}</td>
                    </tr>
                </table>

                <p>Thank you for choosing us.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->AltBody = "Hi {$customerName},\n\nWe are happy to confirm your booking. Please check the document in the attachment.\n\nYour OTP is: {$otp}\nHome Details: {$homeDetails}\nHome ID: {$homeId}\nProperty ID: {$propertyId}";

    // Add attachment
    $attachmentPath = 'C:\wamp64\www\final\photos\bill.pdf';  // Adjust the path as necessary
    if (file_exists($attachmentPath)) {
        $mail->addAttachment($attachmentPath, 'bill.pdf');
    } else {
        echo 'Attachment not found: ' . $attachmentPath;
        exit;
    }

    // Send the message
    $mail->send();
    echo 'Message has been sent with OTP';
    header("Location: thanks.php");
    exit;

} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
}

// OTP validation logic (usually handled on a different page when the user submits the OTP)
if (isset($_POST['submitted_otp'])) {
    session_start();
    $submittedOtp = isset($_POST['submitted_otp']) ? htmlspecialchars($_POST['submitted_otp']) : '';
        
    
    if (time() > $_SESSION['otp_expiration']) {
        echo 'OTP has expired.';
        exit;
    }

    if ($submittedOtp == $_SESSION['otp']) {
        echo 'OTP is valid.';
        // Proceed with the next steps, e.g., confirm booking
    } else {
        echo 'Invalid OTP.';
    }
}

?>
