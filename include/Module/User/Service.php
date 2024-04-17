<?php

namespace MerapiPanel\Module\User;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
use PDO;

class Service extends __Fragment
{

    protected $module;


    function onCreate(Box\Module\Entity\Module $module)
    {

        $this->module = $module;
    }



    function update($id, $name, $email, $password, $confirmPassword, $role, $status)
    {

        if (empty($id)) {
            throw new \Exception('Missing required parameter: id');
        }

        $user = DB::table("users")->select("*")->where("id")->equals($id)->execute()->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new \Exception("User not found");
        }

        if (!empty($name)) {
            $user['name'] = $name;
        }

        if (!empty($email)) {
            $user['email'] = $email;
        }

        if (!empty($password)) {
            if(strlen($password) < 6){
                throw new \Exception("Password must be at least 6 characters long");
            }
            if(!empty($confirmPassword) && $password != $confirmPassword){
                throw new \Exception("Passwords don't match");
            }
            $user['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if (!empty($role)) {
            $user['role'] = $role;
        }

        if (!empty($status)) {
            $user['status'] = $status;
        }

        $user['update_date'] = date('Y-m-d H:i:s');

        DB::table("users")->update($user)->where("id")->equals($id)->execute();

        return $user;

    }



    public function fetch($columns = ["id", "name", "email", "role", "status", "post_date", "update_date"], $where = ["id" => 1])
    {

        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            return "`" . $item . "`";
        }, $columns)) . " FROM `users` WHERE " . implode(" AND ", array_map(function ($item) {
            return "`" . $item . "` = ?";
        }, array_keys($where)));

        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(array_values($where));

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && isset($user['email'])) {
            $user['avatar'] = "https://gravatar.com/avatar/" . md5(strtolower(trim($user['email']))) . "?d=mp?s=45";
        }

        return $user;
    }




    public function fetchAll($columns = ["id", "name", "email", "role", "status", "post_date", "update_date"])
    {

        $users = DB::table("users")->select($columns)->execute()->fetchAll(PDO::FETCH_ASSOC);
        if ($users) {
            $users = array_map(function ($item) {
                if (isset ($item['email'])) {
                    $item['avatar'] = "https://gravatar.com/avatar/" . md5(strtolower(trim($item['email']))) . "?d=mp?s=45";
                }

                return $item;
            }, $users);
        }
        return $users;
    }


}
