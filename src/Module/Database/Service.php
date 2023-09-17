<?php

namespace Mp\Module\Database;

use SQLite3;

class Service
{
    const NAME = __DIR__ . "/data.db";

    protected $db;
    protected $box;

    public function setBox($box)
    {
        $this->box = $box;
        if (!isset($this->db)) {
            $this->initDB();
        }
    }

    public function initDB()
    {
        $this->db = new SQLite3(self::NAME);
    }


    public function prepare($SQL)
    {

        return $this->db->prepare($SQL);
    }

    public function execute() {
        return $this->db->execute();
    }




    public function executeSelectQuery($query, $parameters = [])
    {
        // Prepare and execute a SELECT query
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        foreach ($parameters as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }

        $result = $stmt->execute();
        if ($result === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        // Handle the result, e.g., fetch rows and return
        // You can modify this part based on your specific needs
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }




    public function executeInsertQuery($query, $parameters = [])
    {
        // Prepare and execute an INSERT query
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        foreach ($parameters as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }

        $result = $stmt->execute();
        if ($result === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        // Return the last inserted ID if needed
        return $this->db->lastInsertRowID();
    }





    public function executeUpdateQuery($query, $parameters = [])
    {
        // Prepare and execute an UPDATE query
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        foreach ($parameters as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }

        $result = $stmt->execute();
        if ($result === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        // Return the number of affected rows if needed
        return $this->db->changes();
    }






    public function executeDeleteQuery($query, $parameters = [])
    {
        // Prepare and execute a DELETE query
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        foreach ($parameters as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }

        $result = $stmt->execute();
        if ($result === false) {
            $this->handleError($this->db->lastErrorMsg());
            return false;
        }

        // Return the number of affected rows if needed
        return $this->db->changes();
    }




    public function beginTransaction()
    {
        // Begin a database transaction
        return $this->db->exec('BEGIN');
    }

    public function commitTransaction()
    {
        // Commit the current transaction
        return $this->db->exec('COMMIT');
    }

    public function rollbackTransaction()
    {
        // Rollback the current transaction
        return $this->db->exec('ROLLBACK');
    }

    public function handleError($errorMessage)
    {
        // Handle and log database errors (You can customize this part)
        echo "Database Error: " . $errorMessage;
    }

    public function closeDB()
    {
        // Close the SQLite database connection
        $this->db->close();
    }

    // Add other methods as needed

    public final function __toString()
    {
        return "(Service)";
    }
}
