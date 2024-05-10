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


    /**
     * Login Method
     * @guest true  - Allow login method for guest
     * @admin false - Deny login method for admin
     */
    public function login($email, $password, $longitude, $latitude)
    {

        if (empty($email) || empty($password)) {
            throw new \Exception("Missing required parameter: email or password");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email address");
        }
        if (!$user = Box::module("User")->fetch(["id", "name", "password", "email", "role", "status", "post_date", "update_date"], ["email" => $email])) {
            throw new \Exception("User not found");
        }
        if (!in_array($user['status'], [0, 1, 2]) && $user['status'] != 2) {
            throw new \Exception("User is not active");
        }
        if (!password_verify($password, $user['password'])) {
            throw new \Exception("Invalid credentials");
        }


        $config = $this->module->getConfig();
        $cookie_name = $config->get("cookie_name");
        $session_time = $config->get("session_time");
        $geo = $config->get("geo");


        $session = [
            'token' => Util::uniq(32),
            'user_id' => $user['id'],
            'ip' => Request::getClientIP(),
            'user_agent' => Request::getUserAgent(),
            'logitudelatitude' => $longitude . "," . $latitude,
            'post_date' => date("Y-m-d H:i:s"),
            'data' => []
        ];

        if ($geo) {

            if (!filter_var($longitude, FILTER_VALIDATE_FLOAT) || !filter_var($latitude, FILTER_VALIDATE_FLOAT)) {
                throw new \Exception("Invalid coordinates");
            }
            // toggle check geo location
            $this->checkGeoOptions($user['id'], $longitude, $latitude);
            if ($geoLocation = GeoLocation::getGeoLocation($latitude, $longitude)) {
                if (isset($geoLocation['display_name'], $geoLocation['address'])) {
                    $session['data'] = [
                        ...$session['data'],
                        'display_name' => $geoLocation['display_name'],
                        'address' => $geoLocation['address'],
                    ];
                }
            }
        }

        if (
            DB::table("session")->insert([
                'token' => $session['token'],
                'user_id' => $session['user_id'],
                'ip' => $session['ip'],
                'user_agent' => $session['user_agent'],
                'logitudelatitude' => $session['logitudelatitude'],
                'post_date' => $session['post_date'],
                'data' => !is_string($session['data']) ? json_encode($session['data']) : $session['data'],
            ])->execute()
        ) {
            if (!setcookie($cookie_name, AES::encrypt($session['token']), strtotime($session['expire']), "/")) {
                throw new \Exception("Failed to set cookie");
            }

            return $session;
        }

        throw new \Exception("Failed to login");
    }



    private function checkGeoOptions($user_id, $longitude, $latitude): ?bool
    {

        $cookie_name = $this->module->getConfig()->get("cookie_name");
        // check config for geo location
        if (empty($latitude) && empty($longitude)) {
            setcookie($cookie_name, "", time() - 3600, "/");
            throw new \Exception("Required to verify your location");
        }

        if (!$this->geoInRange($user_id, $latitude, $longitude)) {
            setcookie($cookie_name, "", time() - 3600, "/");
            throw new \Exception("Your location is out of range");
        }
        return true;
    }



    private function geoInRange($user_id, $latitude, $longitude): bool
    {

        $range = $this->module->getConfig()->get("geo.range");
        if ($range == 0 || empty($range)) {
            return true;
        }
        if (empty($latitude) && empty($longitude)) {
            return false;
        }

        $SQL = "SELECT logitudelatitude FROM `session` WHERE user_id = :id ORDER BY post_date DESC LIMIT 1";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['id' => $user_id]);
        $session_geo = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($session_geo) {
            try {
                [$lastLongitude, $lastLatitude] = explode(",", $session_geo['logitudelatitude'], 2);
                return GeoLocation::isWithinRange($latitude, $longitude, $lastLatitude, $lastLongitude, intval($range));
            } catch (\Throwable $e) {
                return false;
            }
        }

        return true;
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
