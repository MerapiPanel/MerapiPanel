<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Util;
use PDO;

class Session extends __Fragment
{

    protected $module;
    function onCreate(Module $module)
    {
        $this->module = $module;
    }



    function getToken()
    {

        $cookie_name = $this->module->getConfig()->get("cookie_name");
        if (!isset($_COOKIE[$cookie_name]) || !$token = AES::decrypt($_COOKIE[$cookie_name] ?? "")) {
            return null;
        }

        return $token;
    }




    function getUser($colums = ["id", "name", "email", "role", "status", "post_date", "update_date"])
    {

        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        $SQL = "SELECT user_id FROM `session` WHERE token = :token";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['token' => $token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($session) {
            return Box::module("User")->fetch($colums, ["id" => $session['user_id'], "status" => 2]);
        }
        return null;
    }




    public function getCurrent()
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }
        return DB::table("session")->select("*")->where("token")->equals($token)->execute()->fetch(PDO::FETCH_ASSOC);
    }






    function getUserSessions($user_id = null)
    {

        if($user_id) {
            $SQL = "SELECT * FROM `session` WHERE user_id = :user_id ORDER BY post_date DESC LIMIT 100";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $key => $value) {
                $result[$key]['data'] = json_decode($value['data'], true);
                $result[$key]['browser'] = $this->getBrowserName($value['user_agent']);
                $result[$key]['device'] = $this->getDeviceName($value['user_agent']);
                $result[$key]['time_ago'] = Util::timeAgo($value['post_date']);
            }

            return $result;
        }

        $token = $this->getToken();
        $user = $this->getUser();
        if (!$user) {
            return [];
        }

        $SQL = "SELECT * FROM `session` WHERE user_id = :user_id ORDER BY post_date DESC LIMIT 100";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['user_id' => $user['id']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $value) {
            $result[$key]['data'] = json_decode($value['data'], true);
            $result[$key]['browser'] = $this->getBrowserName($value['user_agent']);
            $result[$key]['device'] = $this->getDeviceName($value['user_agent']);
            $result[$key]['time_ago'] = Util::timeAgo($value['post_date']);
            $result[$key]['is_current'] = ($value['token'] == $token);
        }

        return $result;
    }




    function getBrowserName($userAgent)
    {
        $browser = "Unknown";

        // Check if user agent contains specific browser keywords
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Google Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Microsoft Edge';
        }

        return $browser;
    }

    // Function to get device name from user agent
    function getDeviceName($userAgent)
    {
        $device = "Unknown";

        // Check if user agent contains specific device keywords
        if (strpos($userAgent, 'Windows') !== false) {
            $device = 'Windows';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            $device = 'iPad';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            $device = 'iPhone';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $device = 'Android Device';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $device = 'Linux';
        } elseif (strpos($userAgent, 'Macintosh') !== false || strpos($userAgent, 'Mac OS X') !== false) {
            $device = 'Macintosh';
        }

        return $device;
    }

}