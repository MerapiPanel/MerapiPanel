<?php

class GeoLocation
{
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $theta = $lon1 - $lon2;
        $distance = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        if ($unit == 'km') {
            $distance = $distance * 1.609344;
        } else if ($unit == 'mi') {
            $distance = $distance * 0.8684;
        }
        return $distance;
    }

    public static function isWithinRange($lat1, $lon1, $lat2, $lon2, $range)
    {
        // Calculate the distance between the two points
        $distance = self::calculateDistance($lat1, $lon1, $lat2, $lon2);

        // Check if the distance is within the specified range
        return $distance <= $range;
    }
}

// Example usage:
$lastLatitude = 40.7128; // Last known latitude
$lastLongitude = -74.0060; // Last known longitude
$currentLatitude = 40.7306; // Current latitude
$currentLongitude = -73.9352; // Current longitude
$range = 10; // Range in kilometers

// Check if the current location is within the specified range of the last known location
$isWithinRange = GeoLocation::isWithinRange($currentLatitude, $currentLongitude, $lastLatitude, $lastLongitude, $range);
echo $isWithinRange ? "Within Range" : "Outside Range";
