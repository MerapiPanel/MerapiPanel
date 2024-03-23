<?php

namespace MerapiPanel\Module\User;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Database\DB;
use PDO;

class Service extends Module
{

    protected $box;


    public function setBox(Box $box)
    {

        $this->box = $box;
        $this->tryCreateDefaultAdmin();
    }



    private function tryCreateDefaultAdmin()
    {
        $username = 'admin';
        $email = 'admin@merapi.panel';
        $password = 'admin123';

        $query = DB::table("users")->select("*")->where("username")->equal($username)->or()->where("email")->equal($email)->execute();
        if ($query instanceof \PDOStatement) {
            if (!$query->fetch(PDO::FETCH_ASSOC)) {
                DB::table("users")->insert([
                    "username" => $username,
                    "password" => password_hash($password, PASSWORD_DEFAULT),
                    "email" => $email,
                ])->execute();
            }
        }
    }





    public function getUserByUserName($username)
    {
        return DB::table("users")->select("*")->where("username")->equal($username)->execute()->fetch(PDO::FETCH_ASSOC);
    }


    public function getUserById($id)
    {
        return DB::table("users")->select("*")->where("id")->equal($id)->execute()->fetch(PDO::FETCH_ASSOC);
    }



    public function getUserByEmail($email)
    {
        return DB::table("users")->select("*")->where("email")->equal($email)->execute()->fetch(PDO::FETCH_ASSOC);
    }





    public function getAllUser()
    {

        return DB::table("users")->select(["username", "email", "created_at", "updated_at"])->execute()->fetchAll(PDO::FETCH_ASSOC);
    }
}
