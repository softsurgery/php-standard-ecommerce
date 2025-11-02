<?php
// Tell the browser this is JSON
header('Content-Type: application/json');

//....

// Create an associative array (like an object in JS)
$response = [
    'status' => 'success',
    'message' => 'Data fetched successfully',
    'user' => [
        'id' => 101,
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ],
    'timestamp' => date('Y-m-d H:i:s')
];

// Convert the PHP array into JSON and output it
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
