<?php

namespace MerapiQu\Module\User;

use MerapiQu\Box;
use MerapiQu\Core\Abstract\Module;

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

        $this->db->query($SQL);


        $SQL = "INSERT OR IGNORE INTO users (name, email, password, role) VALUES ('admin', 'admin@user.com', 'password123', 10);";
        $this->db->query($SQL);
    }



    public function getLogedinUser(): Type
    {
        $SQL = "SELECT * FROM users WHERE email = 'admin@user.com'";
        $result = $this->db->query($SQL);
        return new Type(...$result->fetchArray(SQLITE3_ASSOC));
    }


    public function getUserById($id)
    {
        $SQL = "SELECT * FROM users WHERE id = $id";
        $result = $this->db->query($SQL);
        return $result;
    }


    public function getUserByEmail($email)
    {
        $SQL = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->db->query($SQL);
        return $result;
    }
}
