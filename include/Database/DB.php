<?php
namespace MerapiPanel\Database;
use PDO;


/**
 * Description: MerapiPanel Database class. 
 * @author      ilham b <durianbohong@gmail.com>
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 * @lastUpdate  2024-04-07
 * 
 * @package     MerapiPanel\Database
 * @extends     PDO
 */


final class DB extends PDO
{

    private static DB $instance;
    protected readonly string $host;
    protected readonly mixed $port;
    protected readonly string $username;
    protected readonly string $password;
    protected readonly string $database;



    /**
     * Constructor for initializing the database connection.
     *
     * @param string $host The host of the database.
     * @param mixed $port The port number of the database.
     * @param string $username The username for database authentication.
     * @param string $password The password for database authentication.
     * @param string $database The name of the database.
     */
    private final function __construct(string $host, mixed $port, string $username, string $password, string $database)
    {


        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        parent::__construct('mysql:host=' . $this->host . '; ' . 'port=' . $this->port . '; dbname=' . $this->database, $this->username, $this->password);
    }





    private static function getInstance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self(
                $_ENV['__MP_DATABASE__']['host'],
                $_ENV['__MP_DATABASE__']['port'],
                $_ENV['__MP_DATABASE__']['username'],
                $_ENV['__MP_DATABASE__']['password'],
                $_ENV['__MP_DATABASE__']['database']
            );
        }

        return self::$instance;
    }



    /**
     * Create a table connection
     *
     * @param string $table The name of the table
     * @return Table
     */
    public static function table($table)
    {
        // Create a new database instance
        $db = DB::getInstance();
        return new Table($db, $table);
    }



    /**
     * Get an instance of the PDO database connection
     *
     * @return PDO The PDO database connection
     */
    public static function instance(): DB
    {

        return self::getInstance();
    }
}


final class Table
{

    protected readonly string $name;
    protected readonly DB $db;

    /**
     * Constructor for the class.
     *
     * @param DB $db description
     * @param string $name description
     */
    public function __construct(DB $db, string $name)
    {
        $this->name = $name;
        $this->db = $db;
    }

    /**
     * Get the DB instance.
     *
     * @return DB
     */
    public function getDB(): DB
    {
        return $this->db;
    }



    /**
     * Get the name of the object.
     *
     * @return string
     */
    public function getName(): string
    {
        if (self::containsSpecialChars($this->name)) {
            return "'$this->name'";
        }
        return $this->name;
    }


    /**
     * Get the ID of the last inserted row in the database.
     *
     * @return mixed The ID of the last inserted row.
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }




    /**
     * Query to check if the table exists
     */
    public function isExist()
    {

        // Query to check if the table exists
        $query = "SELECT name FROM sqlite_master WHERE type='table' AND name=:table_name";

        // Prepare the statement
        $stmt = $this->getDB()->prepare($query);
        $stmt->bindValue(':table_name', $this->getName());

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $tableExists = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($tableExists['name']);
    }



    static function containsSpecialChars($string)
    {
        // This regular expression matches any character that is not a letter, digit, or whitespace.
        // Adjust the pattern as needed based on what you consider a special character.
        if (preg_match('/[^a-zA-Z0-9\s]/', $string)) {
            return true; // Special char(s) found
        } else {
            return false; // No special chars found
        }
    }


    /**
     * Select columns for the query in this table.
     *
     * @param array|string $columns The columns to be selected
     * @return SelectQuery
     */
    public function select(array|string $columns): SelectQuery
    {

        if (is_array($columns)) {
            $columns = implode(",", $columns);
        }
        return new SelectQuery($this, $columns);
    }



    /**
     * Insert data to this table using the given input.
     *
     * @param array $data description
     * @return InsertQuery
     */
    public function insert($data): InsertQuery
    {
        return new InsertQuery($this->getName(), $data);
    }

    /**
     * Update the data in this table using the given input.
     *
     * @param array $data 
     * @return UpdateQuery
     */
    public function update($data): UpdateQuery
    {
        //return new UpdateQuery($this, $data);
        return new UpdateQuery($this, $data);
    }



    /**
     * Delete record in this table
     * 
     * @return DeleteQuery
     */
    public function delete(): DeleteQuery
    {
        return new DeleteQuery($this);
    }
}


enum ORDER
{
    case ASC;
    case DESC;
}


final class Where
{

    public $column;
    public $operator;
    public $value;
    private $whereBuilder;


    /**
     * Constructor for the class.
     *
     * @param WhereQueryBuilder $whereBuilder The WhereQueryBuilder instance.
     * @param string $column The column name.
     */
    public function __construct(WhereQueryBuilder $whereBuilder, string $column)
    {
        // Assign the WhereQueryBuilder instance to the property
        $this->whereBuilder = $whereBuilder;
        // Assign the column name to the property
        $this->column = $column;
    }



    /**
     * Set the equal operator and value for the where clause.
     *
     * @param mixed $val The value to compare against.
     * @return WhereQueryBuilder The WhereQueryBuilder instance.
     * @deprecated version 1.0.5 use equals instead
     */
    public function equal($val): WhereQueryBuilder
    {
        $this->operator = "=";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }



    /**
     * Set the equal operator and value for the where clause.
     *
     * @param mixed $val The value to compare against.
     * @return WhereQueryBuilder The WhereQueryBuilder instance.
     */
    public function equals($val): WhereQueryBuilder
    {
        $this->operator = "=";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }



    /**
     * Set the operator to not equal and set the value for the condition
     *
     * @param mixed $val The value to compare against
     * @return WhereQueryBuilder
     */
    public function notEqual($val): WhereQueryBuilder
    {
        $this->operator = "!=";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }



    /**
     * Set the greater than operator and value for the where clause
     *
     * @param mixed $val The value to compare against
     * @return WhereQueryBuilder
     */
    public function greaterThan($val): WhereQueryBuilder
    {
        $this->operator = ">";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }



    /**
     * Set the operator to less than and the value to the given value.
     *
     * @param mixed $val The value to compare against.
     * @return WhereQueryBuilder The WhereQueryBuilder instance for method chaining.
     */
    public function lessThan($val): WhereQueryBuilder
    {
        $this->operator = "<";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }



    /**
     * Set the operator to "LIKE" and the value to the given input, then execute the where query
     *
     * @param mixed $val The value to match with the "LIKE" operator
     * @return WhereQueryBuilder The instance of WhereQueryBuilder for method chaining
     */
    public function like($val): WhereQueryBuilder
    {
        $this->operator = "LIKE";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }



    /**
     * Set the operator to "NOT LIKE" and the value to the given $val.
     * Return the WhereQueryBuilder after setting the conditions.
     *
     * @param mixed $val The value to compare with in the "NOT LIKE" condition
     * @return WhereQueryBuilder The updated WhereQueryBuilder instance
     */
    public function notLike($val): WhereQueryBuilder
    {
        $this->operator = "NOT LIKE";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Set the operator to "IN" and assign the given value.
     *
     * @param array $val The array of values to check against
     * @return WhereQueryBuilder The WhereQueryBuilder instance
     */
    public function in(array $val): WhereQueryBuilder
    {
        $this->operator = "IN";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Set the "NOT IN" operator and value for the where query builder.
     *
     * @param mixed $val The value for the "NOT IN" operator
     * @return WhereQueryBuilder
     */
    public function notIn(array $val): WhereQueryBuilder
    {
        $this->operator = "NOT IN";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Set the operator to "BETWEEN" and the value for the WHERE clause
     *
     * @param mixed $val The value for the WHERE clause
     * @return WhereQueryBuilder The WhereQueryBuilder instance
     */
    public function between($val): WhereQueryBuilder
    {
        $this->operator = "BETWEEN";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Set the operator to "NOT BETWEEN" and the value to $val, then apply the where condition.
     *
     * @param mixed $val The value to compare against.
     * @return WhereQueryBuilder The updated WhereQueryBuilder instance.
     */
    public function notBetween($val): WhereQueryBuilder
    {
        $this->operator = "NOT BETWEEN";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Set the operator and value to check for null.
     *
     * @return WhereQueryBuilder
     */
    public function isNull(): WhereQueryBuilder
    {
        $this->operator = "IS";
        $this->value = "NULL";
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Set the operator and value for a "IS NOT NULL" condition and return the updated WhereQueryBuilder.
     *
     * @return WhereQueryBuilder
     */
    public function isNotNull(): WhereQueryBuilder
    {
        $this->operator = "IS NOT";
        $this->value = "NULL";
        return $this->whereBuilder->where($this);
    }
    


    /**
     * Build the string representation of the query condition.
     *
     * @return string The string representation of the query condition.
     */
    public function build(): string
    {
        // Get the value
        $value = $this->value;

        // Check if the value is an array
        if (is_array($value)) {
            // Map the wrapped values to an array
            $arr = array_map([$this, "wrapValue"], $value);
            // Wrap the array and assign to value
            $value = "(" . implode(", ", $arr) . ")";
        }
        // Check if the value is a string
        elseif (is_string($value)) {
            // Wrap the value
            $value = $this->wrapValue($value);
        }

        // Return the built query condition
        return "{$this->column} {$this->operator} {$value}";
    }
    


    /**
     * Wraps the given value with quotes and escapes it if it's a string, otherwise returns the value as is.
     *
     * @param mixed $val The value to wrap
     * @return mixed The wrapped or original value
     */
    public function wrapValue($val)
    {
        // If the value is a string, wrap it with quotes and escape it
        return is_string($val) ? "'" . addslashes($val) . "'" : $val;
    }
}



class WhereQueryBuilder
{

    private string $nextOp = "AND";
    private $conditions = [];
    private $groups = [];
    private $orders = [];
    private $_limit;
    private $_paged;




    /**
     * Add a condition to the query.
     * 
     * @param Where|string $column The column to add a condition for.
     * @return $this | Where
     */
    public function where(Where|string $column): self|Where
    {
        if (is_string($column)) {
            return new Where($this, $column); // Create a new Where object with the current instance and the column
        }

        $this->conditions[] = [$column, $this->nextOp]; // Add the column and the next operator to the conditions array
        $this->nextOp = "AND"; // Set the next operator to "AND"
        return $this; // Return the current instance
    }




    /**
     * Set the next operation to "OR" for the WHERE clause.
     *
     * @return $this
     */
    public function or(): WhereQueryBuilder
    {
        $this->nextOp = "OR";
        return $this;
    }




    /**
     * Set the next logical operation to 'AND'
     *
     * @return $this
     */
    public function and(): WhereQueryBuilder
    {
        $this->nextOp = "AND";
        return $this;
    }




    /**
     * Set the columns to group by in the query.
     *
     * @param array|string $column
     * @return $this
     */
    public function group(array|string $column)
    {
        if (is_array($column)) {
            // Set the groups to the given array of columns
            $this->groups = $column;
        } else {
            // Push the given column to the groups array
            array_push($this->groups, $column);
        }
        return $this;
    }




    /**
     * Set the order for a specific column in the query result.
     *
     * @param string $column The column to order by
     * @param ORDER $order The order direction (default is ASC)
     * @return $this
     */
    public function order(string $column, ORDER $order = ORDER::ASC)
    {
        $this->orders[$column] = $order;
        return $this;
    }

    


    /**
     * Set the limit value.
     *
     * @param int $limit The limit value to set
     * @return $this
     */
    public function limit($int)
    {
        $this->_limit = $int;
        return $this;
    }




    /**
     * Set the page number for pagination.
     *
     * @param int $pageNumber The page number to set.
     * @return $this
     */
    public function paged($pageNumber)
    {
        $this->_paged = $pageNumber;
        return $this;
    }



    /**
     * Builds the WHERE part of the SQL query based on the conditions provided.
     *
     * @return string The WHERE part of the SQL query.
     */
    public function buildWherePart(): string
    {
        // Return empty string if no conditions are provided
        if (empty($this->conditions)) {
            return "";
        }

        $parts = [];
        $group = [];

        // Iterate through each condition to build the WHERE clause
        foreach ($this->conditions as $x => $condition) {
            list($WhereBuilder, $nextOp) = $condition;

            // Add the condition to the parts array if the next operator is "AND"
            if ($nextOp == "AND") {
                $parts[] = $WhereBuilder->build();
            }

            // If the next operator is "OR" or it's the last condition, group the parts array and reset it
            if ($nextOp == "OR" || $x == count($this->conditions) - 1) {
                $group[] = (count($parts) > 1 ? "(" : "") . implode(" AND ", $parts) . (count($parts) > 1 ? ")" : "");
                $parts = [$WhereBuilder->build()];
            }
        }

        // Combine the groups with "OR" and correct the SQL string for a single condition
        $whereString = implode(" OR ", $group);
        if (count($group) === 1) {
            $whereString = trim($whereString, '()');
        }

        return $whereString;
    }



    /**
     * Build the GROUP BY part of the SQL query.
     *
     * @return string The GROUP BY part of the SQL query.
     */
    public function buildGroupPart(): string
    {
        // Check if there are any groups set
        if (!isset($this->groups[0])) {
            return "";
        }

        // Return the GROUP BY part of the SQL query
        return "GROUP BY " . implode(", ", $this->groups);
    }



    /**
     * Build the ORDER BY part of the SQL query based on the given orders.
     *
     * @return string The ORDER BY part of the SQL query.
     */
    public function buildOrderPart(): string
    {
        // If there are no orders, return an empty string
        if (empty($this->orders)) {
            return "";
        }

        // Build the ORDER BY clause based on the orders
        $orderBy = [];
        foreach ($this->orders as $field => $direction) {
            // Add the field and direction to the ORDER BY clause
            $orderBy[] = "{$field} " . ($direction == ORDER::DESC ? "DESC" : "ASC");
        }

        // Return the constructed ORDER BY clause
        return "ORDER BY " . implode(", ", $orderBy);
    }


    /**
     * Wraps the given value in quotes and escapes it if it's a string.
     * 
     * @param mixed $val The value to be wrapped
     * @return mixed The wrapped value
     */
    public function wrapValue($val)
    {
        return is_string($val) ? "'" . addslashes($val) . "'" : $val;
    }



    /**
     * Build the SQL query based on the set query parameters
     *
     * @return string The generated SQL query
     */
    public function build()
    {

        $output = "";
        $where = $this->buildWherePart();

        // Add WHERE clause if where part is not empty
        if (!empty($where)) {
            $output .= "WHERE $where";
        }

        // Add GROUP BY clause if group part is not empty
        if (!empty($this->buildGroupPart())) {
            $output .= $this->buildGroupPart() . " ";
        }

        // Add ORDER BY clause if order part is not empty
        if (!empty($this->buildOrderPart())) {
            $output .= $this->buildOrderPart() . " ";
        }

        // Add LIMIT clause if limit is set
        if (isset($this->_limit)) {

            $output .= "LIMIT {$this->_limit}";

            // Add OFFSET clause if paged is set
            if (isset($this->_paged)) {
                $output .= ", {$this->_paged}";
            }
        }

        return trim($output);
    }



    /**
     * Executes the function
     * 
     * This function is left empty because it should be implemented by descendants
     * 
     * @return \PDOStatement|false|int
     */
    public function execute(): \PDOStatement|false|int
    {
        return false;
    }



    /**
     * Returns the string representation of the object
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }
}


final class InsertQuery
{




    private Table $table;
    private array $data;




    /**
     * Constructor for initializing the table and data.
     *
     * @param string $table The table name.
     * @param array $data The data to be inserted.
     */
    public function __construct($table, $data)
    {
        $this->table = $table;
        $this->data = $data;
    }








    /**
     * Execute the query and return the result
     *
     * @return mixed The result of the query
     */
    public function execute()
    {
        // Execute the query using the built SQL statement
        return $this->table->getDB()->query($this->build());
    }








    /**
     * Build and return an SQL INSERT statement based on the provided data and table.
     *
     * @return string The SQL INSERT statement.
     */
    public function build(): string
    {
        // Create a comma-separated list of column names
        $columns = implode(",", array_keys($this->data));

        // Create a comma-separated list of escaped values
        $values = implode(",", array_map(function ($value) {
            return is_string($value) ? "'" . addslashes($value) . "'" : $value;
        }, $this->data));

        // Return the formatted SQL INSERT statement
        return "INSERT INTO {$this->table->getName()} ({$columns}) VALUES ({$values})";
    }






    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }
}





final class SelectQuery extends WhereQueryBuilder
{

    private Table $table;
    private string $columns;



    /**
     * Constructor for the class
     * 
     * @param Table $table The table object
     * @param string $columns The array of column names
     */
    public function __construct(Table $table, string $columns)
    {
        $this->table = $table;
        $this->columns = $columns;
    }



    /**
     * Execute the SQL query and return the PDO statement.
     *
     * @return \PDOStatement
     */
    public function execute(): \PDOStatement|int
    {
        // Construct the SQL query
        $columns = $this->columns;
        $table = $this->table->getName();
        $conditions = !empty($this->build()) ? " " . $this->build() : "";
        $sql = "SELECT $columns FROM $table" . "$conditions";

        // Execute the SQL query
        return $this->table->getDB()->query($sql);
    }



    /**
     * Convert the object to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        // Generate the SQL query
        $columns = is_array($this->columns) ? implode(', ', $this->columns) : $this->columns;
        $tableName = $this->table->getName();
        $conditions = !empty($this->build()) ? " " . $this->build() : "";

        $sql = 'SELECT ' . $columns . ' FROM `' . $tableName . "`" . $conditions;

        return $sql;
    }
}


final class UpdateQuery extends WhereQueryBuilder
{


    private Table $table;
    private $data;



    /**
     * Constructor for initializing the table and data.
     *
     * @param Table $table The name of the table.
     * @param array $data The data to be stored in the table.
     */
    public function __construct(Table $table, $data)
    {

        $this->table = $table;
        $this->data = $data;
    }



    /**
     * Execute the query and return the PDOStatement, false, or the number of affected rows
     *
     * @return \PDOStatement|false|int
     */
    public function execute(): \PDOStatement|false|int
    {
        // Execute the query using the getDB method of the table object
        return $this->table->getDB()->query("$this");
    }



    /**
     * Convert the object to its string representation
     *
     * @return string
     */
    public function __toString()
    {
        // Initialize an array to store the SET parts of the SQL query
        $setParts = [];

        // Iterate through the data array and build the SET parts
        foreach ($this->data as $column => $value) {
            // If the value is a string, add slashes and wrap it in quotes
            $setParts[] = "{$column} = " . (is_string($value) ? "'" . addslashes($value) . "'" : (empty($value) ? 'NULL' : $value));
        }

        // Build the WHERE part of the SQL query
        $wherePart = $this->build();

        // Assemble the UPDATE query string
        return "UPDATE {$this->table->getName()} SET " . implode(",", $setParts) . (!empty($wherePart) ? " {$wherePart}" : "");
    }
}





final class DeleteQuery extends WhereQueryBuilder
{

    private Table $table;


    /**
     * Constructor for the DeleteQuery.
     *
     * @param Table $table Name of the table.
     */
    public function __construct($table)
    {
        $this->table = $table;
    }


    /**
     * Execute the query and return the PDOStatement or false if the query fails.
     *
     * @return \PDOStatement|false|int
     */
    public function execute(): \PDOStatement|false|int
    {
        return $this->table->getDB()->query("$this");
    }



    /**
     * Convert the object to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        // Build the WHERE part of the SQL query
        $wherePart = $this->build();

        // Construct and return the DELETE SQL query
        return "DELETE FROM {$this->table->getName()}" . (!empty($wherePart) ? " {$wherePart}" : "");
    }
}
