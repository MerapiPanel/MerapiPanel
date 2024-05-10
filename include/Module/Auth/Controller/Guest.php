<?php

namespace MerapiPanel\Module\Auth\Controller;

use Google_Client;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\AES;
use MerapiPanel\Views\View;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use PDO;
use Throwable;

class Guest extends __Fragment
{

    protected $module;
    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function register()
    {

        if (isset($_ENV["__MP_ADMIN__"]['prefix'])) {

            Router::POST("/auth/api/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"), "GoogleAuth", self::class);
            Router::POST("/auth/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"), "Login", self::class);
        }
    }


    function GoogleAuth(Request $req)
    {

        $referer = $req->http("referer");
        try {

            if (!$referer) {
                $referer = $_ENV['__MP_' . strtoupper($_ENV["__MP_ACCESS__"]) . '__']['prefix'] . "/";
            }

            require_once __DIR__ . "/../vendor/autoload.php";

            $config = $this->module->getConfig();
            $credential = $req->credential();
            $g_csrf_token = $req->g_csrf_token();

            $client = new Google_Client([
                "client_id" => $config->get('google_oauth_id')
            ]);


            $payload = $client->verifyIdToken($credential);
            if ($payload) {

                if (!$payload['email_verified']) {
                    return View::render("response.html.twig", [
                        "status" => "error",
                        "message" => "Your email is not verified",
                        "redirect" => $referer
                    ]);
                }

                $email = $payload['email'];

                if ($user = DB::table("users")->select("*")->where("email")->equals($email)->execute()->fetch(PDO::FETCH_ASSOC)) {

                    // $config = $this->module->getConfig();
                    // $cookie_name  = $config->get("cookie_name");
                    // $session_time = $config->get("session_time");
                    // $geo          = $config->get("geo");
                    // $geo_range    = $config->get("geo.range");

                    // // check geo location
                    // if ($geo) {

                    //     // check config for geo location
                    //     if (empty($latitude) && empty($logitude)) {
                    //         setcookie($cookie_name, "", time() - 3600, "/");
                    //         return [
                    //             "code" => 400,
                    //             "message" => "Required to verify your location",
                    //         ];
                    //     }

                    //     if (!$this->geoInRange($user['id'], $latitude, $logitude)) {
                    //         setcookie($cookie_name, "", time() - 3600, "/");
                    //         return [
                    //             "code" => 400,
                    //             "message" => "Can't verify your location"
                    //         ];
                    //     }

                    //     if (!self::insertGeo($user['id'], $latitude, $logitude)) {
                    //         setcookie($cookie_name, "", time() - 3600, "/");
                    //         return [
                    //             "code" => 400,
                    //             "message" => "Failed to insert geo location"
                    //         ];
                    //     }
                    // }

                    $token = bin2hex(random_bytes(4));
                    $last_session = DB::table("session_token")
                        ->select("*")
                        ->where("expires")->greaterThan(date("Y-m-d H:i:s"))
                        ->where("user_id")->equals($user["id"])
                        ->execute();

                    if ($last_session->rowCount() > 0) {

                        $token = $last_session->fetch(PDO::FETCH_ASSOC)["token"];

                    } else {

                        DB::table("session_token")->insert([
                            "token" => $token,
                            "expires" => date("Y-m-d H:i:s", strtotime(" + " . $config->get("session_time") . " hours")),
                            "client_ip" => self::get_client_ip(),
                            "user_id" => $user["id"]
                        ])->execute();
                    }

                    if (!setcookie($config->get("cookie_name"), AES::encrypt($token), strtotime(date("Y-m-d H:i:s", strtotime(" + " . $config->get("session_time") . " hours"))), "/")) {

                        return View::render("response.html.twig", [
                            "status" => "danger",
                            "message" => "Error while setting cookie",
                            "redirect" => $referer
                        ]);
                    }


                    if (!$referer) {

                        $referer = $_ENV['__MP_' . strtoupper($_ENV["__MP_ACCESS__"]) . '__']['prefix'] . "/";
                    }

                    return View::render("response.html.twig", [
                        "status" => "success",
                        "message" => "Login successful.",
                        "redirect" => $referer
                    ]);
                }


                return View::render("response.html.twig", [
                    "status" => "warning",
                    "message" => "User with this email not found.",
                    "redirect" => $referer
                ]);

            }


            return View::render("response.html.twig", [
                "status" => "warning",
                "message" => "Invalid credentials.",
                "redirect" => $referer
            ]);

        } catch (Throwable $e) {

            return View::render("response.html.twig", [
                "status" => "danger",
                "message" => $e->getMessage(),
                "redirect" => $referer ?? "/"
            ]);
        }
    }



    public function Login($req, $res)
    {


        // get request payload
        $email    = $req->email();
        $password = $req->password();
        $latitude = $req->latitude();
        $logitude = $req->longitude();

        if (!$email || !$password) {
            return [
                "code" => 200,
                "message" => "Please provide email and password.",
            ];
        }

        // get user from database based on email, status = 2 (active)
        if ($user = DB::table("users")->select("*")->where("email")->equals($email)->and()->where("status")->equals("2")->execute()->fetch(PDO::FETCH_ASSOC)) {

            $config       = $this->module->getConfig();
            $cookie_name  = $config->get("cookie_name");
            $session_time = $config->get("session_time");
            $geo       = $config->get("geo");
            $geo_range = $config->get("geo.range");


            if (password_verify($password, $user["password"])) {

                // check geo location
                if ($geo) {
                    // check config for geo location
                    if (empty($latitude) && empty($logitude)) {
                        setcookie($cookie_name, "", time() - 3600, "/");
                        return [
                            "code" => 400,
                            "message" => "Required to verify your location",
                        ];
                    }

                    if (!$this->geoInRange($user['id'], $latitude, $logitude)) {
                        setcookie($cookie_name, "", time() - 3600, "/");
                        return [
                            "code" => 400,
                            "message" => "Can't verify your location"
                        ];
                    }

                    if (!self::insertGeo($user['id'], $latitude, $logitude)) {
                        setcookie($cookie_name, "", time() - 3600, "/");
                        return [
                            "code" => 400,
                            "message" => "Failed to insert geo location"
                        ];
                    }
                }

                // check last session
                $last_session = DB::table("session_token")->select("*")->where("expires")->greaterThan(date("Y-m-d H:i:s"))->and()->where("user_id")->equals($user["id"])->execute();

                $token = bin2hex(random_bytes(4));
                if ($last_session->rowCount() > 0) {
                    $token = $last_session->fetch(PDO::FETCH_ASSOC)["token"];
                } else {
                    DB::table("session_token")->insert([
                        "token" => $token,
                        "expires" => date("Y-m-d H:i:s", strtotime("+ $session_time hours")),
                        "client_ip" => self::get_client_ip(),
                        "user_id" => $user["id"]
                    ])->execute();
                }

                if (!setcookie($config->get("cookie_name"), AES::encrypt($token), strtotime(date("Y-m-d H:i:s", strtotime("+ $session_time hours"))), "/")) {

                    return [
                        "code" => 400,
                        "message" => "Failed to set cookie"
                    ];
                }

                unset($user['password']);
                return [
                    "code" => 200,
                    "message" => "success",
                    "data" => [
                        "cookie-name" => $config->get("cookie_name"),
                        "token" => AES::encrypt($token),
                        "user" => $user
                    ]
                ];

            }
            return [
                "code" => 400,
                "message" => "Invalid credentials"
            ];
        }

        return [
            "code" => 404,
            "message" => "Record not found"
        ];
    }



    // Function to get the client IP address
    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }




    private function geoInRange($user_id, $latitude, $longitude)
    {

        $config = $this->module->getConfig();
        $range = $config->get("geo.range");
        if ($range == 0 || empty($range)) {
            return true;
        }
        if (empty($latitude) && empty($longitude)) {
            return false;
        }

        $SQL = "SELECT * FROM session_geo WHERE user_id = :id ORDER BY id DESC LIMIT 1";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['id' => $user_id]);
        $session_geo = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($session_geo) {
            try {
                $lastLatitude = $session_geo['latitude'];
                $lastLongitude = $session_geo['longitude'];
                return GeoLocation::isWithinRange($latitude, $longitude, $lastLatitude, $lastLongitude, intval($range));
            } catch (Throwable $e) {
                return false;
            }
        }

        return true;
    }



    private static function insertGeo($user_id, $latitude, $longitude)
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
            $data = json_decode($response, true);

            if (isset($data['display_name'], $data['address'])) {

                if (
                    DB::table("session_geo")->insert([
                        "user_id" => $user_id,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "display_name" => $data['display_name'],
                        "address" => !is_string($data['address']) ? json_encode($data['address']) : $data['address']
                    ])->execute()
                ) {
                    return true;
                }
            }
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
}
