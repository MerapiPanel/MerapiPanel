<?php

namespace Mp\Database;

use SQLite3;

class DB {

    const NAME = "data";

    protected $db;
    private static $instance = null;

    private function __construct() {
        $this->db = new SQLite3(self::NAME);
    }

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

}