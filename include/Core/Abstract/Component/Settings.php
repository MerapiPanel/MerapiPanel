<?php

namespace MerapiPanel\Core\Abstract\Component;

use ArrayAccess;
use MerapiPanel\Core\Database;
use PDO;

final class Settings implements ArrayAccess
{

    protected $container = [];
    protected Database $db;

    final public function __construct(Database $db)
    {

        $this->db = $db;

        $SQL = "CREATE TABLE IF NOT EXISTS settings (
            `name` TEXT PRIMARY KEY,
            `value` TEXT UNIQUE NOT NULL
        );";
        $this->db->runQuery($SQL);


        $SQL = "SELECT * FROM settings";
        $result = $this->db->runQuery($SQL);
        $this->container = $result->fetchAll(PDO::FETCH_ASSOC);
    }



    function offsetExists(mixed $key): bool
    {

        $SQL = "SELECT * FROM settings WHERE name = :name";
        $stmt = $this->db->runQuery($SQL, ['name' => $key]);

        return $stmt->rowCount() > 0;
    }



    function offsetGet(mixed $key) : mixed
    {

        $SQL = "SELECT * FROM settings WHERE name = :name";
        $stmt = $this->db->runQuery($SQL, ['name' => $key]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['value'];
    }

    function offsetSet($key, $value): void
    {

        $SQL = "INSERT OR REPLACE INTO settings (name, value) VALUES (:name, :value)";
        $this->db->runQuery($SQL, ['name' => $key, 'value' => $value]);
    }


    function offsetUnset($value): void
    {

        $SQL = "DELETE FROM settings WHERE name = :name";
        $this->db->runQuery($SQL, ['value' => $value]);
    }

    public function getContainer() {
        return $this->container;
    }
}
