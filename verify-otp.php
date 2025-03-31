<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enteredOtp = $_POST['otp'];
    $email = $_POST['buyer_email'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp && $_SESSION['buyer_email'] == $email) {
        echo json_encode(["status" => "verified"]);
        unset($_SESSION['otp']); // Clear OTP after successful verification
    } else {
        echo json_encode(["status" => "invalid"]);
    }
}
?>
