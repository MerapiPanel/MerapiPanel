<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;
use PDO;

class Service extends __Fragment
{

    protected Module $module;
    function onCreate(Module $module)
    {
        $this->module = $module;
    }




    static function ON_CONFIG_SET($key, $value)
    {
        if ($key == "session_time" && $value < 1) {
            throw new \Exception("Session time cannot be 0");
        }
        if ($key == "cookie_name" && strlen($value) < 4) {
            throw new \Exception("Cookie name cannot be less than 4 characters");
        }
    }



    function forceLogout($user_id)
    {
        $session_time = $this->module->getConfig()->get("session_time");
        $SQL = "UPDATE `session` SET post_date = :time WHERE user_id = :id AND post_date > :time";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute([
            'id' => $user_id,
            'time' => date("Y-m-d H:i:s", time() - ($session_time * 60 * 60)),
        ]);
    }



    public function isLogedin($user_id)
    {
        $session_time = $this->module->getConfig()->get("session_time"); // in hours
        $SQL = "SELECT post_date FROM `session` WHERE user_id = :id AND post_date > :time ORDER BY post_date DESC LIMIT 1";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['id' => $user_id, 'time' => date("Y-m-d H:i:s", time() - ($session_time * 60 * 60))]);
        return $stmt->rowCount() > 0;
    }





    // Module Api check isAdmin
    public function isAdmin()
    {

        $config = $this->module->getConfig();
        $session_time = $config->get("session_time"); // in hours
        if ($session = $this->module->Session->getCurrent()) {

            if (strtotime($session['post_date']) + ($session_time * 60 * 60) > time()) {
                // if login
                return $this->module->Session->getUser() != null;
            }
            // if not login
        }

        return false;
    }

}


class GeoLocation
{
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Vincenty formula for distance calculation
        $a = 6378137; // Earth radius in meters
        $f = 1 / 298.257223563; // Flattening of the ellipsoid

        $deltaLon = $lon2 - $lon1;

        $U1 = atan((1 - $f) * tan($lat1));
        $U2 = atan((1 - $f) * tan($lat2));
        $sinU1 = sin($U1);
        $cosU1 = cos($U1);
        $sinU2 = sin($U2);
        $cosU2 = cos($U2);

        $lambda = $deltaLon;
        $lambdaP = 2 * M_PI;
        $iterLimit = 20;
        $sinLambda = sin($lambda);
        $cosLambda = cos($lambda);
        $sinSigma = 0;
        $cosSigma = 0;
        $sigma = 0;
        $sinAlpha = 0;
        $cosSqAlpha = 0;
        $cos2SigmaM = 0;

        while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0) {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);
            $sinSigma = sqrt(($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
                ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) * ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda));

            if ($sinSigma == 0) {
                return 0;  // Co-incident points
            }

            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - $sinAlpha * $sinAlpha;
            $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha;

            $C = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
            $lambdaP = $lambda;
            $lambda = $deltaLon + (1 - $C) * $f * $sinAlpha *
                ($sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM)));
        }

        if ($iterLimit == 0) {
            return null;  // Formula failed to converge
        }

        $uSq = $cosSqAlpha * ($a * $a - 6378137 * 6378137) / (6378137 * 6378137);
        $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
        $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
        $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) - $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));

        $distance = $a * $A * ($sigma - $deltaSigma);

        // Convert distance to the desired unit
        if ($unit == 'km') {
            $distance /= 1000; // Convert meters to kilometers
        } else if ($unit == 'mi') {
            $distance /= 1609.34; // Convert meters to miles
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

    public static function getGeoLocation($latitude, $longitude)
    {

        if (empty($latitude) && empty($longitude)) {
            return false;
        }

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
            return json_decode($response, true);
        }
        return false;
    }
}
