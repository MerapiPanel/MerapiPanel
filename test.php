<?php

// Latitude and longitude coordinates
$latitude = 0.4751823;
$longitude = 101.4570514;

// OpenStreetMap Nominatim API endpoint
$apiEndpoint = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={$latitude}&lon={$longitude}";

// Create a stream context with a User-Agent header
$context = stream_context_create([
    'http' => [
        'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36'
    ]
]);

// Make a request to the API using the stream context
$response = file_get_contents($apiEndpoint, false, $context);

// Check if the response is successful
if ($response !== false) {
    // Decode the JSON response
    $data = json_decode($response, true);
    print_r($data);

} else {
    echo "Failed to fetch data from the API.";
}