<?php

function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    // Convert latitude and longitude from degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);
    
    // Earth's radius in kilometers
    $radius = 6371; // in kilometers
    
    // Calculate the differences
    $deltaLat = $lat2 - $lat1;
    $deltaLon = $lon2 - $lon1;
    
    // Haversine formula
    $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    // Calculate the distance
    $distance = $radius * $c;
    
    return $distance; // Distance in kilometers
}

// Example usage
$latitude1 = 40.7128;
$longitude1 = -74.0060;
$latitude2 = 34.0522;
$longitude2 = -118.2437;

$distance_km = haversineDistance($latitude1, $longitude1, $latitude2, $longitude2);
$distance_miles = $distance_km * 0.621371; // Convert to miles

echo "Distance between the two points is " . round($distance_km, 2) . " kilometers or " . round($distance_miles, 2) . " miles.";
