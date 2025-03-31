<?php
// chat.php
header('Content-Type: application/json');

// Get the POST data
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

// For demonstration, we respond with a fixed message. You can integrate AI or other logic here.
$response = '';

if (strtolower($message) === 'hello') {
    $response = 'Hi there! How can I help you today?';
} elseif (strtolower($message) === 'price') {
    $response = 'Please specify the property you are interested in.';
} else {
    $response = 'Sorry, I didn\'t understand that.';
}

// Return JSON response
echo json_encode(['response' => $response]);
?>
