<?php

namespace MerapiPanel {

    use Exception;
    use PDO;

    class DB
    {

        private PDO $pdo;
        private static $instance;


        /**
         * Constructor for initializing the database connection.
         *
         * @param string $host The host of the database.
         * @param mixed  $port The port number of the database.
         * @param string $username The username for database authentication.
         * @param string $password The password for database authentication.
         * @param string $database The name of the database.
         */
        private function __construct()
        {
            $host = $_ENV['__MP_MYSQL__']['host'];
            $port = $_ENV['__MP_MYSQL__']['port'];
            $username = $_ENV['__MP_MYSQL__']['username'];
            $password = $_ENV['__MP_MYSQL__']['password'];
            $database = $_ENV['__MP_MYSQL__']['database'];

            $this->pdo =  new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8;", $username, $password);
        }


        public static function getInstance(): PDO
        {

            if (!isset(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance->pdo;
        }



        /**
         * @method  prepare()
         * @param string $db 
         * Database name or SQL with primary database
         */
        public static function prepare($SQL = null)
        {
            return self::getInstance()->prepare($SQL);
        }


        public static function query($SQL = null)
        {
            return self::getInstance()->query($SQL);
        }
    }
}
