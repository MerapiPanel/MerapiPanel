<?php

namespace Mp\Core;

use PDO;

class Database extends PDO
{

    /**
     * Constructs a new instance of the class.
     *
     * @param mixed $dsn The data source name (DSN) for the database connection.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     * @param array $options Additional options for the database connection (optional).
     * @throws PDOException A description of the exception that can be thrown.
     * @return void
     */
    public function __construct($dsn, $username, $password, $options = [])
    {

        parent::__construct($dsn, $username, $password, $options);

    }




    /**
     * Runs a query and returns the statement object.
     *
     * @param string $query The SQL query to be executed.
     * @param array $params An array of parameters to be bound to the query.
     * @return Statement The statement object representing the executed query.
     */
    public function runQuery($query, $params = [])
    {

        $statement = $this->prepare($query);
        
        $statement->execute($params);

        return $statement;
    }





    /**
     * Fetch all records from the database based on the given query and parameters.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An optional array of parameters to bind to the query.
     * @throws PDOException If there is an error executing the query.
     * @return array An array of associative arrays representing the fetched records.
     */
    public function fetchAll($query, $params = [])
    {

        $statement = $this->runQuery($query, $params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }





    /**
     * Fetches a single row from the database based on the given query and parameters.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An optional array of parameters to bind to the query.
     * @throws PDOException If there is an error executing the query.
     * @return array|null Returns an associative array representing a single row from the result set, or null if no rows are found.
     */
    public function fetchRow($query, $params = [])
    {

        $statement = $this->runQuery($query, $params);

        return $statement->fetch(PDO::FETCH_ASSOC);
        
    }





    /**
     * Fetches a single column from the result of a query.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The array of parameters to bind to the query.
     * @param int $columnIndex The index of the column to fetch (default is 0).
     *
     * @throws PDOException A description of the exception that can be thrown.
     *
     * @return mixed The value of the fetched column.
     */
    public function fetchColumn($query, $params = [], $columnIndex = 0)
    {
        
        $statement = $this->runQuery($query, $params);

        return $statement->fetchColumn($columnIndex);

    }





    /**
     * Inserts data into a specified table.
     *
     * @param string $table The name of the table.
     * @param array $data An associative array of column names and their corresponding values.
     * @throws PDOException A description of the exception that can be thrown.
     * @return int The last inserted ID.
     */
    public function insert($table, $data)
    {
        
        $columns = implode(', ', array_keys($data));
        $values  = implode(', ', array_fill(0, count($data), '?'));
        $query   = "INSERT INTO $table ($columns) VALUES ($values)";
        $params  = array_values($data);

        $this->runQuery($query, $params);

        return $this->lastInsertId();

    }





    /**
     * Updates a record in the specified table with the given data based on the provided condition.
     *
     * @param string $table The name of the table to update.
     * @param array $data An associative array containing the column names and their new values.
     * @param string $where The condition that determines which records to update.
     * @param array $params An optional array of additional parameters for the query.
     * @throws PDOException A description of the exception that may be thrown.
     * @return int The number of rows affected by the update query.
     */
    public function update($table, $data, $where, $params = [])
    {

        $setClause  = implode(' = ?, ', array_keys($data)) . ' = ?';
        $query      = "UPDATE $table SET $setClause WHERE $where";
        $updateData = array_values($data);
        $params     = array_merge($updateData, $params);

        return $this->runQuery($query, $params)->rowCount();

    }





    /**
     * Deletes records from a specified table based on a given condition.
     *
     * @param string $table The name of the table to delete records from.
     * @param string $where The condition to determine which records to delete.
     * @param array $params An optional array of parameters for the query.
     * @throws Some_Exception_Class A description of the exception that can be thrown.
     * @return int The number of rows deleted.
     */
    public function delete($table, $where, $params = [])
    {

        $query = "DELETE FROM $table WHERE $where";

        return $this->runQuery($query, $params)->rowCount();

    }
    
}
