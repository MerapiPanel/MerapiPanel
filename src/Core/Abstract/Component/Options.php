<?php

namespace MerapiPanel\Core\Abstract\Component;

use ArrayAccess;
use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Util;
use PDO;

enum ConnectionType
{
    case FILE;
    case SQLITE;
}


final class Options implements ArrayAccess
{

    const file = "config.json";
    private $conType = ConnectionType::FILE;

    protected $container = [];
    private $className;





    final public function __construct($className)
    {

        $this->className = $className;
        $this->initialize();
    }







    /**
     * Initialize the configuration table and populate it with default values if necessary
     */
    private function initialize()
    {

        $find = DB::findYmlConfig();

        if ($find && file_exists($find)) {
            $this->initialWithDB();
            $this->conType = ConnectionType::SQLITE;
        } else {
            $this->initialWithFile();
            $this->conType = ConnectionType::FILE;
        }
    }


    private function initialWithFile()
    {

        $file = __DIR__ . "/../../../Module" . Module::getModuleName($this->className);
        if (!file_exists($file)) {
            throw new \Exception("Module " . Module::getModuleName($this->className) . " not found");
        }

        $file .= "/" . self::file;

        error_log("File: " . $file);

    }




    private function initialWithDB()
    {

        // Check if the config table exists, if not, create it
        if (!DB::table("config")->isExist()) {
            $SQL = "CREATE TABLE IF NOT EXISTS config (`name` TEXT PRIMARY KEY,`value` TEXT UNIQUE NOT NULL);";
            DB::instance()->query($SQL);
        } else {
            // Check if the config table structure matches the expected columns
            $sql = "PRAGMA table_info(config)";
            $stmt = DB::instance()->prepare($sql);
            $stmt->execute();
            $tableInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $expectedColumns = [
                ["name" => "key", "type" => "varchar(255)"],
                ["name" => "value", "type" => "varchar(255)"]
            ];

            $columnsMatch = true;

            foreach ($expectedColumns as $expectedColumn) {
                $columnExists = false;
                foreach ($tableInfo as $info) {
                    if ($info['name'] === $expectedColumn['name'] && $info['type'] === $expectedColumn['type']) {
                        $columnExists = true;
                        break;
                    }
                }
                if (!$columnExists) {
                    $columnsMatch = false;
                    break;
                }
            }

            // If the table structure does not match, drop the table and reinitialize
            if (!$columnsMatch) {
                $SQL = "DROP TABLE config;";
                DB::instance()->query($SQL);
                return $this->initialize();
            }
        }

        // Retrieve the configuration data from the database
        $this->container = DB::table("config")->select("*")->execute()->fetchAll(PDO::FETCH_ASSOC);

        // Get the default configuration values
        $defaultConfig = $this->getDefaultConfig();

        // If the number of configuration items does not match the default, update or insert default values
        if (is_array($defaultConfig) && count($this->container) != count($defaultConfig)) {
            $arrayKeys = array_keys($defaultConfig);
            foreach ($arrayKeys as $key) {
                if (!(DB::table('config')->select("value")->where("name")->equal($key)->execute()->fetch(PDO::FETCH_ASSOC))) {
                    // Insert default value if the config does not exist
                    DB::table("config")->insert(["name" => $key, "value" => $defaultConfig[$key]])->execute();
                } else {
                    // Update value if the config exists
                    DB::table('config')->update(["value" => $defaultConfig[$key]])->where("name")->equal($key)->execute();
                }
            }
        }
    }





    private function getDefaultConfig(): array|false
    {

        try {

            $modAddr = "Module_{$this->getModuleName()}";
            $config = Box::Get($this)->$modAddr()?->getProperty("config");

            return $config;
        } catch (\Throwable $e) {

            error_log(self::class . " Error: " . $e->getMessage());
        }

        return false;
    }








    private function getCaller()
    {

        $file = false;

        foreach (debug_backtrace() as $call) {
            // only in module
            if (isset($call['file']) && in_array("module", array_map("strtolower", explode((PHP_OS == "WINNT" ? "\\" : "/"), $call['file'])))) {
                $file = $call['file'];
                break;
            }
        }

        return $file;
    }








    private function getModuleName()
    {
        $caller = $this->getCaller();

        error_log("caller : " . $caller);

        $dirname = dirname($caller);
        $basename = basename($dirname);

        while (strtolower(basename($dirname)) != "module" && $dirname != '/' && $dirname != null) {

            $basename = basename($dirname);
            $dirname = realpath("$dirname/..");

            if (preg_replace('/[^a-zA-Z0-9]+/', '', $_SERVER['DOCUMENT_ROOT']) == preg_replace('/[^a-zA-Z0-9]+/', '', $dirname)) {
                $basename = null;
                break;
            }
        }

        return $basename;
    }








    function offsetExists(mixed $key): bool
    {

        $result = DB::table('config')->select("value")->where("name")->equal($key)->execute()->fetch(PDO::FETCH_ASSOC);
        return isset($result["value"]);
    }






    function offsetGet(mixed $key): mixed
    {

        $result = DB::table('config')->select("value")->where("name")->equal($key)->execute()->fetch(PDO::FETCH_ASSOC);
        return $result['value'];
    }





    function offsetSet($key, $value): void
    {

        $SQL = "INSERT OR REPLACE INTO settings (name, value) VALUES (:name, :value)";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->bindParam(':name', $key);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }







    function offsetUnset($value): void
    {

        $SQL = "DELETE FROM settings WHERE name = :name";

        $stmt = DB::instance()->prepare($SQL);
        $stmt->bindParam(':name', $value);
    }
}
