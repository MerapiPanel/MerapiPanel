<?php

namespace MerapiQu\Module\Users;

use MerapiQu\Box;
use MerapiQu\Core\Abstract\Module;
use PDO;

class Service extends Module
{

    protected $box;
    protected $db;

    public function setBox(Box $box)
    {
        $this->box = $box;
        $this->db = $this->getDatabase();

        $SQL = "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                role INTEGER NOT NULL
            );";

        $this->db->runQuery($SQL);


        $SQL = "INSERT OR IGNORE INTO users (name, email, password, role) VALUES ('admin', 'admin@user.com', 'password123', 10);";
        $this->db->runQuery($SQL);
    }



    public function getLogedinUser(): Type
    {
        $SQL = "SELECT * FROM users WHERE email = 'admin@user.com'";
        $result = $this->db->runQuery($SQL);
        return new Type(...$result->fetch(PDO::FETCH_ASSOC));
    }


    public function getUserById($id)
    {
        $SQL = "SELECT * FROM users WHERE id = $id";
        $result = $this->db->runQuery($SQL);
        return $result;
    }


    public function getUserByEmail($email)
    {
        $SQL = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->db->runQuery($SQL);
        return $result;
    }

    public function getAllUser() {

        $SQL = "SELECT * FROM users";
        $result = $this->db->runQuery($SQL);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
