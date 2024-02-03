<?php

namespace MerapiPanel\Core\Token;

use MerapiPanel\Database\DB;

class Token
{

    public static function generate()
    {

        $uniqid = self::uniqidReal();

        self::deleteOldTokens();

        DB::table("m-token")->insert([
            "token" => $uniqid,
            "created_at" => date("Y-m-d H:i:s")
        ])->execute();

        return $uniqid;
    }



    public static function validate($token)
    {
        $result = DB::table("m-token")->select("id")->where("token")->equal($token)->execute()?->fetchAll(\PDO::FETCH_ASSOC);
        if ($result) {
            DB::table("m-token")->delete()->where("id")->equal($result[0]['id'])->execute();
        }
        return count($result) > 0;
    }

    private static function deleteOldTokens()
    {
        DB::table("m-token")->delete()->where("created_at")->lessThan(date("Y-m-d H:i:s", strtotime("-30 minutes")))->execute();
    }





    public static function uniqidReal($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }
}
