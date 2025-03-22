<?php

// Test script for blog webhook
// Usage: php test_blog_webhook.php [api_key]

// Get API key from command line argument or prompt for it
$apiKey = $argv[1] ?? null;
if (!$apiKey) {
    echo "Please enter the API key: ";
    $apiKey = trim(fgets(STDIN));
}

// Base URL - change this to match your local environment
$baseUrl = 'http://localhost:8080';

// Sample blog post data
$blogPost = [
    'title' => 'Test Blog Post from Webhook1',
    'content' => "## This is a test post\n\nThis post was created via the webhook API. It supports **markdown** formatting.\n\n### Features\n\n- Automatic slug generation\n- Markdown support\n- API authentication",
    'excerpt' => 'This is a test post created via the webhook API to demonstrate the functionality.',
    'tags' => ['test', 'webhook', 'api'],
    'youtube_url' => 'https://www.youtube.com/watch?v=xfXf3jwNbr4'
];

// Convert to JSON
$jsonData = json_encode($blogPost);

// Initialize cURL
$ch = curl_init("$baseUrl/api/webhook/blog");

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData),
    'X-API-Key: ' . $apiKey
]);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL
curl_close($ch);

// Output results
echo "Response Code: $httpCode\n";
echo "Response Body:\n";
echo $response ? json_encode(json_decode($response), JSON_PRETTY_PRINT) : "No response";
echo "\n";
