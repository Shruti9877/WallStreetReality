<?php
session_start();

$enteredOtp = $_POST['otp'];
$currentTime = time();

if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiry'])) {
    if ($currentTime <= $_SESSION['otp_expiry'] && $enteredOtp == $_SESSION['otp']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'OTP expired or incorrect.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No OTP generated.']);
}
?>
