<?php
/*
Using a nonce is a great way to ensure the security of your webhook. A nonce is a random number that is generated for each request, and it helps to prevent replay attacks. 
Here's a simple example of how to create a secure webhook using PHP and the nonce feature:
*/

// Constants
define('SECRET_KEY', 'your-secret-key'); // Replace 'your-secret-key' with a strong secret key

// Check if the request contains the necessary headers
if (!isset($_SERVER['HTTP_X_SIGNATURE']) || !isset($_SERVER['HTTP_X_NONCE'])) {
    http_response_code(400);
    die('Missing required headers');
}

// Read the request body
$requestBody = file_get_contents('php://input');

// Verify the request signature
$expectedSignature = hash_hmac('sha256', $requestBody . $_SERVER['HTTP_X_NONCE'], SECRET_KEY);
if ($_SERVER['HTTP_X_SIGNATURE'] !== $expectedSignature) {
    http_response_code(401);
    die('Invalid signature');
}

// Process the request (e.g., parse the JSON data, and perform any desired actions)
$data = json_decode($requestBody, true);
// ... Perform your desired actions using the $data variable ...

// Send a success response
http_response_code(200);
echo 'Webhook processed successfully';
?>
