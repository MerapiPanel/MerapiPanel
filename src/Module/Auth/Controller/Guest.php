<?php

namespace Mp\Module\Auth\Controller;

use Mp\Box;

class Guest
{

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register($router)
    {

        $router->get("/login", self::class . "@index");
        $router->post("/login", self::class . "@login");
    }


    public function index($entity)
    {

        $db = $this->box->database();
        $users = $db->executeSelectQuery("SELECT * FROM users");

        ($db->prepare("ALTER TABLE users ADD password TEXT;"))->execute();

        if (empty($users)) {

            $db->executeInsertQuery("INSERT INTO users (username, password) VALUES ('admin', 'admin')");
        }

        $users = $db->executeSelectQuery("SELECT * FROM users");


        print_r($users);


        return $entity->render("login.html.twig");
    }


    public function login()
    {

        return [
            "status" => 203,
            "response" => "success"
        ];
    }
}
