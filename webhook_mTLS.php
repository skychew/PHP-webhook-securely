<?php
// Constants
define('SECRET_KEY', 'your-secret-key'); // Replace 'your-secret-key' with the same secret key used in your webhook
define('WEBHOOK_URL', 'https://yourserver.com/path/to/webhook.php'); // Replace with the URL to your webhook
define('CLIENT_CERT_PATH', '/path/to/client.crt'); // Replace with the path to your client certificate file
define('CLIENT_KEY_PATH', '/path/to/client.key'); // Replace with the path to your client private key file

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
curl_setopt($ch, CURLOPT_SSLCERT, CLIENT_CERT_PATH);
curl_setopt($ch, CURLOPT_SSLKEY, CLIENT_KEY_PATH);

$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

curl_close($ch);
?>
