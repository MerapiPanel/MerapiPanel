<?php

namespace MerapiQu\Core;

use SQLite3;

class Database extends SQLite3
{
    /**
     * Constructs a new instance of the class.
     *
     * @param string $dbFile The path to the SQLite database file.
     */
    public function __construct($dbFile)
    {
        parent::__construct(rtrim($dbFile, '/') . '/' . strtolower(basename($dbFile)) . '.db');
    }

    /**
     * Runs a query and returns the statement object.
     *
     * @param string $query The SQL query to be executed.
     * @param array $params An array of parameters to be bound to the query.
     * @return SQLite3Stmt The statement object representing the executed query.
     */
    public function runQuery($query, $params = [])
    {
        $statement = $this->prepare($query);

        foreach ($params as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $result = $statement->execute();

        return $result;
    }

    /**
     * Fetch all records from the database based on the given query and parameters.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An optional array of parameters to bind to the query.
     * @return array An array of associative arrays representing the fetched records.
     */
    public function fetchAll($query, $params = [])
    {
        $statement = $this->runQuery($query, $params);
        $rows = [];

        while ($row = $statement->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Fetches a single row from the database based on the given query and parameters.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An optional array of parameters to bind to the query.
     * @return array|null Returns an associative array representing a single row from the result set, or null if no rows are found.
     */
    public function fetchRow($query, $params = [])
    {
        $statement = $this->runQuery($query, $params);

        return $statement->fetchArray(SQLITE3_ASSOC);
    }

    /**
     * Inserts data into a specified table.
     *
     * @param string $table The name of the table.
     * @param array $data An associative array of column names and their corresponding values.
     * @return int The last inserted ID.
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($key) {
            return ":$key";
        }, array_keys($data)));

        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        $statement = $this->runQuery($query, $data);

        return $this->lastInsertRowID();
    }

    /**
     * Updates a record in the specified table with the given data based on the provided condition.
     *
     * @param string $table The name of the table to update.
     * @param array $data An associative array containing the column names and their new values.
     * @param string $where The condition that determines which records to update.
     * @param array $params An optional array of additional parameters for the query.
     * @return int The number of rows affected by the update query.
     */
    public function update($table, $data, $where, $params = [])
    {
        $setClause = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));

        $query = "UPDATE $table SET $setClause WHERE $where";
        $params = array_merge($data, $params);
        $statement = $this->runQuery($query, $params);

        return $this->changes();
    }

    /**
     * Deletes records from a specified table based on a given condition.
     *
     * @param string $table The name of the table to delete records from.
     * @param string $where The condition to determine which records to delete.
     * @param array $params An optional array of parameters for the query.
     * @return int The number of rows deleted.
     */
    public function delete($table, $where, $params = [])
    {
        $query = "DELETE FROM $table WHERE $where";
        $statement = $this->runQuery($query, $params);

        return $this->changes();
    }
}
