<?php

namespace MerapiPanel\Module\Auth;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;
use MerapiPanel\Views\View;
use PDO;

class Guest extends __Fragment
{

    protected $module;

    function onCreate(Module $module)
    {
        $this->module = $module;
    }


    /**
     * Login Method
     * @guest true  - Allow login method for guest
     * @admin false - Deny login method for admin
     */
    public function loginHandler($email, $password, $longitude, $latitude)
    {

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter an valid email address", 400);
        }

        if (empty($password) || strlen($password) < 8) {
            throw new Exception("Invalid credentials", 400);
        }

        if (!$user = Box::module("User")->fetch(["id", "name", "password", "email", "role", "status", "post_date", "update_date"], ["email" => $email])) {
            throw new Exception("User not found");
        }
        if (!in_array($user['status'], [0, 1, 2]) && $user['status'] != 2) {
            throw new Exception("User is not active");
        }
        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid credentials");
        }


        $config = $this->module->getConfig();
        $cookie_name = $config->get("cookie_name");
        $session_time = $config->get("session_time");
        $geo = $config->get("geo");


        $session = [
            'token'     => Util::uniq(32),
            'user_id'   => $user['id'],
            'ip'        => Request::getClientIP(),
            'user_agent'        => Request::getUserAgent(),
            'logitudelatitude'  => $longitude . "," . $latitude,
            'post_date' => date("Y-m-d H:i:s"),
            'data'      => [],
            "expire"    => date("Y-m-d H:i:s", strtotime("+ $session_time hours"))
        ];

        if ($geo) {

            if (!filter_var($longitude, FILTER_VALIDATE_FLOAT) || !filter_var($latitude, FILTER_VALIDATE_FLOAT)) {
                throw new Exception("Invalid coordinates");
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
                'token'             => $session['token'],
                'user_id'           => $session['user_id'],
                'ip'                => $session['ip'],
                'user_agent'        => $session['user_agent'],
                'logitudelatitude'  => $session['logitudelatitude'],
                'post_date'         => $session['post_date'],
                'data'              => !is_string($session['data']) ? json_encode($session['data']) : $session['data'],
            ])->execute()
        ) {
            if (!setcookie($cookie_name, AES::encrypt($session['token']), strtotime($session['expire']), "/")) {
                throw new Exception("Failed to set cookie");
            }

            return $session;
        }

        throw new Exception("Failed to login");
    }



    private function checkGeoOptions($user_id, $longitude, $latitude): ?bool
    {

        $cookie_name = $this->module->getConfig()->get("cookie_name");
        // check config for geo location
        if (empty($latitude) && empty($longitude)) {
            setcookie($cookie_name, "", time() - 3600, "/");
            throw new Exception("Required to verify your location");
        }

        if (!$this->geoInRange($user_id, $latitude, $longitude)) {
            setcookie($cookie_name, "", time() - 3600, "/");
            throw new Exception("Your location is out of range");
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
