<?php
/*
To send a request to your webhook, you'll need to generate a nonce, sign the request using the secret key, and include the signature and nonce in the headers. Here's a simple example using PHP and cURL:
*/
// Constants
define('SECRET_KEY', 'your-secret-key'); // Replace 'your-secret-key' with the same secret key used in your webhook
define('WEBHOOK_URL', 'https://yourserver.com/path/to/webhook.php'); // Replace with the URL to your webhook

// Generate a nonce
$nonce = bin2hex(random_bytes(16));

// Create a payload
$payload = [
    'event' => 'example',
    'data' => [
        'key' => 'value',
    ],
];

// Encode the payload as JSON
$jsonPayload = json_encode($payload);

// Calculate the signature
$signature = hash_hmac('sha256', $jsonPayload . $nonce, SECRET_KEY);

// Send the request using cURL
$ch = curl_init(WEBHOOK_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Signature: ' . $signature,
    'X-Nonce: ' . $nonce,
]);

$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

curl_close($ch);
?>
