<?php

namespace MerapiPanel\Core\Abstract\Component;

use ArrayAccess;
use MerapiPanel\Box;
use MerapiPanel\Database\DB;
use PDO;

final class Config implements ArrayAccess
{


    protected $container = [];





    final public function __construct()
    {
        $this->initialize();
    }







    private function initialize()
    {

        if (!DB::table("config")->isExist()) {

            $SQL = "CREATE TABLE IF NOT EXISTS config (`name` TEXT PRIMARY KEY,`value` TEXT UNIQUE NOT NULL);";
            DB::instance()->query($SQL);
        }

        $this->container = DB::table("config")->select("*")->execute()->fetchAll(PDO::FETCH_ASSOC);


        $defaultConfig = $this->getDefaultConfig();

        if (is_array($defaultConfig) && count($this->container) != count($defaultConfig)) {

            $arrayKeys = array_keys($defaultConfig);
            foreach ($arrayKeys as $key) {
                if (!(DB::table('config')->select("value")->where("name")->equal($key)->execute()->fetch(PDO::FETCH_ASSOC))) {
                    DB::table("config")->insert(["name" => $key, "value" => $defaultConfig[$key]])->execute();
                } else {
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

            error_log("Error: " . $e->getMessage());
        }

        return false;
    }








    private function getCaller()
    {

        $file = false;

        foreach (debug_backtrace() as $call) {
            if (
                isset($call['file']) &&
                !in_array("core", array_map("strtolower", explode((PHP_OS == "WINNT" ? "\\" : "/"),  $call['file'])))
            ) {
                $file = $call['file'];
                break;
            }
        }

        error_log("File: " . $file);
        return $file;
    }








    private function getModuleName()
    {
        $caller = $this->getCaller();
        $dirname = dirname($caller);
        $basename = basename($dirname);

        while (strtolower(basename($dirname)) != "module") {

            $basename = basename($dirname);
            $dirname = realpath("$dirname/..");

            if (preg_replace('/[^a-zA-Z0-9]+/', '', $_SERVER['DOCUMENT_ROOT']) ==  preg_replace('/[^a-zA-Z0-9]+/', '', $dirname)) {
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
