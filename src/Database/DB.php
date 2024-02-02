<?php

namespace MerapiPanel\Database;

use Exception;
use PDO;
use Symfony\Component\Yaml\Yaml;

class DB
{

    private \PDO $dbh;

    private function findDatabase()
    {

        $backtrace = debug_backtrace();
        $caller = $backtrace[1];

        $file = $caller['file'];

        $filename = 'database.yml';  // File to search for
        $dirname = dirname($file);   // Initial directory

        // Loop until the file is found or the root directory is reached
        while ($dirname !== '/' && !file_exists("$dirname/$filename")) {
            $dirname = realpath("$dirname/..");
        }

        if ($dirname !== '/') {

            $this->prepareDatabase("$dirname/$filename");
        } else {
            throw new Exception("file configuration database.yml not found in " . dirname($file));
        }
    }



    private function prepareDatabase($fileYml)
    {

        $dbFileName = trim(preg_replace('/(^src|^\/src|[^a-z0-9]+)/', '-', strtolower(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace(PHP_OS == "WINNT" ? "\\" : "/", "/", dirname(realpath($fileYml)))))), "-");

        $dbFile = __DIR__ . "/data/$dbFileName";
        $conf = Yaml::parseFile($fileYml);

        if (!isset($conf['version'])) {
            throw new Exception("Invalid Config : please provide version in yml file");
        }

        if (!isset($conf['tables'])) {
        }

        if (!file_exists($dbFile)) {
            mkdir($dbFile, 0777, true);
        }
        $dbFile .= "/$conf[version].sqlite";

        if (!file_exists($dbFile)) {

            $this->initialNewVersion($dbFile, $conf);
        } else {
            // Create a PDO connection
            $dsn = 'sqlite:' . $dbFile;
            $this->dbh = new \PDO($dsn);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    private function initialNewVersion($newVersionFile, $conf)
    {

        $newDB = $this->createDatabase($newVersionFile);
        $this->initialTables($newDB, $conf['tables']);

        $oldVersionFile = $this->findBackwardVersion($newVersionFile);
        if ($oldVersionFile && file_exists($oldVersionFile)) {

            $oldDB = $this->createDatabase($oldVersionFile);
            $oldTables = $this->getTableNames($oldDB);
            foreach ($oldTables as $table) {

                $this->importBetweenDatabase($table, $oldDB, $newDB);
            }
        }

        $this->dbh = $newDB;
    }


    private function createDatabase($dbFile): \PDO
    {

        $dsn = 'sqlite:' . $dbFile;
        $dbh = new \PDO($dsn);
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }

    private function getTableNames($pdo)
    {

        $SQL = "SELECT name FROM sqlite_master WHERE name NOT LIKE 'sqlite_%'";
        $stmt = $pdo->prepare($SQL);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    private function findBackwardVersion($file): string | false
    {

        $dirname = dirname($file);
        $dirs = glob("$dirname/*.sqlite");
        $highestVersionFile = null;
        $highestVersion = '';

        foreach ($dirs as $dir) {
            $fileName = pathinfo($dir, PATHINFO_FILENAME);

            // Assuming version format in the filename, adjust the regex to match your versioning scheme
            if (preg_match('/(\d+)\.(\d+)\.(\d+)/', $fileName, $matches)) {
                $version = $matches[0]; // This captures the whole version string, e.g., 1.2.3

                if (version_compare($version, $highestVersion, '>')) {
                    $highestVersion = $version;
                    $highestVersionFile = $dir;
                }
            }
        }

        if ($highestVersionFile != null) {
            return $dirname . '/' . $highestVersionFile . '.sqlite';
        }
        return false;
    }

    function initialTables(\PDO $dbh, array $tables)
    {

        try {

            foreach ($tables as $table => $options) {

                $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
                $columns = $options['columns'];
                $columnDefinitions = [];
                $primaryKeyDefined = false;

                foreach ($columns as $name => $col) {
                    $name = trim($name);
                    $columnSql = "`$name` ";

                    // Determine if column is the primary key and an integer
                    $isPrimaryKey = isset($col['autoincrement']) && $col['autoincrement'] && strtoupper($col['type']) === 'INTEGER';

                    if ($isPrimaryKey) {
                        $primaryKeyDefined = true;
                        $columnSql .= "INTEGER PRIMARY KEY AUTOINCREMENT";
                    } else {
                        $columnSql .= strtoupper($col['type']);

                        if (!empty($col['length'])) {
                            $columnSql .= "({$col['length']})";
                        }

                        if (!empty($col['unsigned']) && $col['unsigned']) {
                            // Note: SQLite does not support the UNSIGNED keyword. This part is for compatibility with other SQL databases.
                            $columnSql .= " UNSIGNED";
                        }

                        if (!empty($col['notnull']) && $col['notnull']) {
                            $columnSql .= " NOT NULL";
                        }

                        if (!empty($col['defaultcurrent']) && $col['defaultcurrent']) {
                            $columnSql .= " DEFAULT CURRENT_TIMESTAMP";
                        }
                    }

                    $columnDefinitions[] = $columnSql;
                }

                $sql .= implode(", ", $columnDefinitions);

                // Add primary key if defined in indexes and not already defined through autoincrement
                if (!$primaryKeyDefined && !empty($options['indexes']['PRIMARY KEY'])) {
                    $sql .= ", PRIMARY KEY (`{$options['indexes']['PRIMARY KEY']}`)";
                }

                $sql .= ");";

                // Log or execute SQL
                $stream = fopen(__DIR__ . "/access.log", "a+");
                fwrite($stream, date("Y-m-d H:i:s") . " " . $sql . "\n");
                fclose($stream);

                // Uncomment this line to execute the query using your database connection
                $dbh->query($sql);
            }
        } catch (Exception $e) {

            if ($GLOBALS["debug"]) {
                $stream = fopen(__DIR__ . "/error.log", "a+");
                fwrite($stream, date("Y-m-d H:i:s") . " " . $e->getMessage() . "\n");
                fclose($stream);
            }
        }
    }


    public function importBetweenDatabase(string $tableName, \PDO $fromDB, \PDO $toDB)
    {

        $sql1 = "PRAGMA table_info($tableName)";
        $stmt1 = $fromDB->prepare($sql1);
        $stmt1->execute();
        $tableInfo1 = $stmt1->fetchAll(\PDO::FETCH_ASSOC);

        $sql2 = "PRAGMA table_info($tableName)";
        $stmt2 = $toDB->prepare($sql2);
        $stmt2->execute();
        $tableInfo2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

        if (count($tableInfo1) <= 0 || count($tableInfo2) <= 0) {
            return false;
        }

        // Create a mapping between the columns in the source table and the columns in the destination table
        $columnMapping = [];
        foreach ($tableInfo1 as $column1) {
            foreach ($tableInfo2 as $column2) {
                if ($column1['name'] === $column2['name']) {
                    $columnMapping[$column1['name']] = $column1['name'];
                    break;
                }
            }
        }

        // Import data from db1 to db2, ignoring columns that are not present in db2
        $columnNames = implode(', ', array_values($columnMapping));
        $sql = "INSERT IGNORE INTO $tableName ($columnNames) SELECT " . implode(', ', array_keys($columnMapping)) . " FROM $tableName";
        $toDB->exec($sql);
    }

    function query($sql): \PDOStatement|false|null|int
    {

        if ($GLOBALS['debug']) {
            $stream = fopen(__DIR__ . "/access.log", "a+");
            fwrite($stream, date("Y-m-d H:i:s") . " " . $sql . "\n");
            fclose($stream);
        }

        $res = $this->dbh->exec($sql);
        if ($res == 0 && strtolower(substr($sql, 0, 6)) == 'select') return $this->dbh->query($sql);
        else return $res;
    }

    function exec($sql): int|false
    {
        return $this->dbh->exec($sql);
    }

    public static function table($table)
    {

        $db = new self();
        $db->findDatabase();

        return new Table($db, $table);
    }

    public static function instance(): PDO
    {

        $db = new self();
        $db->findDatabase();
        return $db->dbh;
    }
}


class Table
{

    protected $name;
    protected $db;

    public function __construct($db, $name)
    {
        $this->name = $name;
        $this->db = $db;
    }

    public function getDB(): DB
    {
        return $this->db;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function select(array|string $columns)
    {

        if (is_array($columns)) {
            $columns = implode(",", $columns);
        }
        // TODO: Implement select() method.
        return new SelectQuery($this, $columns);
    }

    public function insert($data)
    {
        return new InsertQuery($this, $data);
    }

    public function update($data)
    {
        //return new UpdateQuery($this, $data);
        return new UpdateQuery($this, $data);
    }

    public function delete()
    {
        return new DeleteQuery($this);
    }
}


enum ORDER
{
    case ASC;
    case DESC;
}


class Where
{

    public $column;
    public $operator;
    public $value;

    private $whereBuilder;

    public function __construct(WhereQueryBuilder $whereBuilder, $column)
    {

        $this->whereBuilder = $whereBuilder;
        $this->column = $column;
    }


    public function equal($val): WhereQueryBuilder
    {

        $this->operator = "=";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }



    public function notEqual($val): WhereQueryBuilder
    {
        $this->operator = "!=";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }

    public function greaterThan($val): WhereQueryBuilder
    {
        $this->operator = ">";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }
    public function lessThan($val): WhereQueryBuilder
    {
        $this->operator = "<";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }

    public function like($val): WhereQueryBuilder
    {
        $this->operator = "LIKE";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }

    public function notLike($val): WhereQueryBuilder
    {
        $this->operator = "NOT LIKE";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }

    public function in(array $val): WhereQueryBuilder
    {
        $this->operator = "IN";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }

    public function notIn($val): WhereQueryBuilder
    {
        $this->operator = "NOT IN";
        $this->value = $val;
        return $this->whereBuilder->where($this);
    }

    public function between($val): WhereQueryBuilder
    {
        $this->operator = "BETWEEN";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }

    public function notBetween($val): WhereQueryBuilder
    {
        $this->operator = "NOT BETWEEN";
        $this->value    = $val;
        return $this->whereBuilder->where($this);
    }

    public function isNull(): WhereQueryBuilder
    {
        $this->operator = "IS";
        $this->value    = "NULL";
        return $this->whereBuilder->where($this);
    }

    public function isNotNull(): WhereQueryBuilder
    {
        $this->operator = "IS NOT";
        $this->value    = "NULL";
        return $this->whereBuilder->where($this);
    }

    public function build(): string
    {
        $value = $this->value;
        if (is_array($value)) {
            $arr = array_map([$this, "wrapValue"], $value);
            $value = "(" . implode(", ", $arr) . ")";
        } elseif (is_string($value)) {
            $value = $this->wrapValue($value);
        }

        return "{$this->column} {$this->operator} {$value}";
    }

    public function wrapValue($val)
    {

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


    public function __construct()
    {
    }


    public function where(Where|string $column)
    {

        if (is_string($column)) {
            return new Where($this, $column);
        }

        $this->conditions[] = [$column, $this->nextOp];
        $this->nextOp = "AND";
        return $this;
    }


    public function or(): WhereQueryBuilder
    {
        $this->nextOp = "OR";
        return $this;
    }

    public function and(): WhereQueryBuilder
    {
        $this->nextOp = "AND";
        return $this;
    }


    public function group(array | string $column)
    {
        if (is_array($column)) {
            $this->groups = $column;
        } else {
            array_push($this->groups, $column);
        }
        return $this;
    }

    public function order(string $column, ORDER $order = ORDER::ASC)
    {

        $this->orders[$column] = $order;
        return $this;
    }

    public function limit($int)
    {
        $this->_limit = $int;
        return $this;
    }

    public function paged($int)
    {
        $this->_paged = $int;
        return $this;
    }

    public function buildWherePart(): string
    {
        if (empty($this->conditions)) {
            return "";
        }

        $parts = [];
        $group = [];

        foreach ($this->conditions as $x => $condition) {
            list($WhereBuilder, $nextOp) = $condition;

            if ($nextOp == "AND") {
                $parts[] = $WhereBuilder->build();
            }

            if ($nextOp == "OR" || $x == count($this->conditions) - 1) {

                $group[] = (count($parts) > 1 ? "(" : "") . implode(" AND ", $parts) . (count($parts) > 1 ? ")" : "");
                $parts = [$WhereBuilder->build()];
            }
        }

        $whereString = implode(" OR ", $group);

        // Correct the SQL string for a single condition
        if (count($group) === 1) {
            $whereString = trim($whereString, '()');
        }

        return $whereString;
    }



    public function buildGroupPart(): string
    {
        if (!isset($this->groups[0])) {
            return "";
        }

        return "GROUP BY " . implode(", ", $this->groups);
    }

    public function buildOrderPart(): string
    {
        if (empty($this->orders)) {
            return "";
        }

        $orderBy = [];
        foreach ($this->orders as $field => $direction) {
            $orderBy[] = "{$field} " . ($direction == ORDER::DESC ? "DESC" : "ASC");
        }

        return "ORDER BY " . implode(", ", $orderBy);
    }

    public function wrapValue($val)
    {

        return is_string($val) ? "'" . addslashes($val) . "'" : $val;
    }

    public function build()
    {

        $output = "";
        $where = $this->buildWherePart();

        if (!empty($where)) {
            $output .= "WHERE $where";
        }

        if (!empty($this->buildGroupPart())) {
            $output .= $this->buildGroupPart() . " ";
        }
        if (!empty($this->buildOrderPart())) {
            $output .= $this->buildOrderPart() . " ";
        }

        if (isset($this->_limit)) {
            $output .= "LIMIT {$this->_limit}";
        }
        if (isset($this->_paged)) {
            $output .= ", {$this->_paged}";
        }

        return trim($output);
    }


    public function execute(): \PDOStatement|false|int
    {
        return false;
    }

    public function __toString()
    {
        return $this->build();
    }
}



class InsertQuery
{

    private Table $table;
    private array $data;

    public function __construct($table, $data)
    {
        $this->table = $table;
        $this->data = $data;
    }

    public function execute()
    {
        return $this->table->getDB()->query($this->build());
    }

    public function build(): string
    {
        $columns = implode(",", array_keys($this->data));
        $values = implode(",", array_map(function ($value) {
            return is_string($value) ? "'" . addslashes($value) . "'" : $value;
        }, $this->data));

        return "INSERT INTO {$this->table->getName()} ({$columns}) VALUES ({$values})";
    }

    public function __toString()
    {
        return $this->build();
    }
}


class SelectQuery extends WhereQueryBuilder
{

    private Table $table;
    private $columns;

    protected WhereQueryBuilder $WherequeryBuilder;

    public function __construct(Table $table, $columns)
    {

        $this->table   = $table;
        $this->columns = $columns;
    }

    public function execute(): \PDOStatement
    {
        $sql = 'SELECT ' . (is_array($this->columns) ? implode(', ',) : $this->columns) . ' FROM `' . $this->table->getName() . "`" . (!empty($this->build()) ? " " . $this->build() : "");
        return $this->table->getDB()->query($sql);
    }

    public function __toString()
    {

        $sql = 'SELECT ' . (is_array($this->columns) ? implode(', ',) : $this->columns) . ' FROM `' . $this->table->getName() . "`" . (!empty($this->build()) ? " " . $this->build() : "");
        return $sql;
    }
}


class UpdateQuery extends WhereQueryBuilder
{

    private Table $table;
    private $data;


    public function __construct($table, $data)
    {

        $this->table = $table;
        $this->data = $data;
    }

    public function execute(): \PDOStatement|false|int
    {

        return $this->table->getDB()->query("$this");
    }


    function __toString()
    {
        $setParts = [];
        foreach ($this->data as $column => $value) {
            $setParts[] = "{$column} = " . (is_string($value) ? "'" . addslashes($value) . "'" : $value);
        }

        $wherePart = $this->build();

        return "UPDATE {$this->table->getName()} SET " . implode(",", $setParts) . (!empty($wherePart) ? " {$wherePart}" : "");
    }
}


class DeleteQuery extends WhereQueryBuilder
{

    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }


    public function execute(): \PDOStatement|false|int
    {
        return $this->table->getDB()->query("$this");
    }

    public function __toString()
    {

        $wherePart = $this->build();
        return "DELETE FROM {$this->table->getName()}" . (!empty($wherePart) ?  " {$wherePart}" : "");
    }
}
