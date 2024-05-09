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

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

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
            if (strlen($password) < 6) {
                throw new \Exception("Password must be at least 6 characters long");
            }
            if (!empty($confirmPassword) && $password != $confirmPassword) {
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


    function add($name, $email, $password, $confirmPassword, $role, $status, $sendConfirmation)
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        if (empty($name)) {
            throw new \Exception('Missing required parameter: name');
        }

        if (empty($email)) {
            throw new \Exception('Missing required parameter: email');
        }
        if (empty($role)) {
            throw new \Exception('Missing required parameter: role');
        }
        if (!in_array($status, [0, 1, 2])) {
            throw new \Exception('Missing required parameter: status');
        }

        if (empty($password)) {
            throw new \Exception('Missing required parameter: password');
        }

        if (strlen($password ?? "") < 6) {
            throw new \Exception('Passwords must be at least 6 characters long');
        }
        if ($password != $confirmPassword) {
            throw new \Exception("Passwords don't match");
        }


        if (DB::table("users")->select("id")->where("email")->equals($email)->execute()->rowCount() > 0) {
            throw new \Exception("Email already exists");
        }



        if (
            DB::table("users")->insert([
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_BCRYPT),
                "role" => $role,
                "status" => $status
            ])->execute() && DB::instance()->lastInsertId() > 0
        ) {

            if ($sendConfirmation) {
                $this->module->sendConfirmation($email);
            }
            return [
                "id" => DB::instance()->lastInsertId(),
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_BCRYPT),
                "role" => $role,
                "status" => $status
            ];
        }


        throw new \Exception("Failed to add user");
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




    public function fetchAll(
        $columns = ["id", "name", "email", "role", "status", "post_date", "update_date"],
        $page = null,
        $limit = null,
        $search = null
    ) {

        // Validate $limit to prevent division by zero
        if ($limit <= 0) {
            // Fetch all results if $limit is zero or negative
            $limit = null;
        }

        // Construct the main SQL query
        $mainSQL = "SELECT " . ($columns ? implode(",", $columns) : "*") . " FROM users"
            . ($search ? " WHERE name LIKE '%{$search}%' OR email LIKE '%{$search}%' OR role LIKE '%{$search}%' " : "")
            . " ORDER BY id DESC";

        // Construct the count SQL query
        $countSQL = "SELECT COUNT(*) AS total FROM users"
            . ($search ? " WHERE name LIKE '%{$search}%' OR email LIKE '%{$search}%' OR role LIKE '%{$search}%' " : "");

        // Execute the count query to get total results
        $totalCount = DB::instance()->query($countSQL)->fetchColumn();
        if(!$totalCount) {
            return [
                'users' => [],
                'totalPages' => 0,
                'totalResults' => 0
            ];
        }
        // Calculate total pages based on total results and limit per page
        $totalPages = ($limit > 0) ? ceil($totalCount / $limit) : 0;

        // Construct the main SQL query with pagination
        $offset = ($page - 1) * $limit;
        $SQL = $mainSQL . ($limit ? " LIMIT $offset, $limit" : "");

        // Execute the main query to fetch users
        $users = DB::instance()->query($SQL)->fetchAll(PDO::FETCH_ASSOC);

        if($users){
            foreach($users as $key => $user){
                $users[$key]['avatar'] = "https://gravatar.com/avatar/" . md5(strtolower(trim($user['email']))) . "?d=mp&s=45";
            }
        }

        // Return the users, total pages, and total results
        return [
            'users' => $users,
            'totalPages' => $totalPages,
            'totalResults' => $totalCount
        ];
    }


    function sendConfirmation($email)
    {

        error_log("Sending confirmation email to $email");
    }



    function delete($id)
    {
        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }
        DB::table("users")->delete()->where("id")->equals($id)->execute();
    }

}
