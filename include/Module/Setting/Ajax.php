<?php

namespace MerapiPanel\Module\Setting;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;


class Ajax extends __Fragment
{

    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    function saveConfig()
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        $req = Request::getInstance();
        $token = $req->setting_token();



        if (!$token || !AES::decrypt($token)) {
            throw new \Exception("Invalid token");
        }
        $data = AES::decrypt($token);
        $entry = unserialize($data);

        if (!isset($entry['module']) || !$entry['input']) {
            throw new \Exception("Invalid data");
        }

        $module_name = $entry['module'];
        $input = $entry['input'];
        $module = Box::module(ucfirst($module_name));
        $config = $module->getConfig();
        $stack = [];
        // Fetch values from request, check required
        foreach ($input as $name) {
            // Request query name must be in snake case
            $queryName = preg_replace("/\./", "_", $name);
            $value = $req->$queryName();
            if (empty($value) && $config->isRequired($name)) {
                if (str_contains($name, ".") && count(explode(".", $name)) > 1) {
                    $names = explode(".", $name);
                    $parentName = implode(".", array_slice($names, 0, count($names) - 1));
                    if($req->$parentName() == true || $req->$parentName() == 1){
                        throw new \Exception("Missing required parameter: $name");
                    }
                }
            }
            $stack[$name] = $value;
        }
        
        foreach ($stack as $name => $value) {
            $config->set($name, $value);
        }

        return true;
    }

    function updateRole($role, $name, $value)
    {

        if (!$this->module->getRoles()->isAllowed(2)) {
            throw new \Exception('Permission denied');
        }


        $roleNames = Util::getRoles();
        if (!in_array($role, $roleNames)) {
            throw new \Exception("Invalid role");
        }
        if (empty($name) || !str_contains($name, ".")) {
            throw new \Exception("Missing required parameter: name");
        }
        if (!in_array($value, [0, 1])) {
            throw new \Exception("Invalid value");
        }

        $SQL = "SELECT * FROM roles WHERE role = :role AND name = :name";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute([':role' => $role, ':name' => $name]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            $SQL = "INSERT INTO roles (role, name, value) VALUES (:role, :name, :value)";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute([
                ':role' => $role,
                ':name' => $name,
                ':value' => $value
            ]);
        } else {
            $SQL = "UPDATE roles SET value = :value WHERE role = :role AND name = :name";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute([
                ':role' => $role,
                ':name' => $name,
                ':value' => $value
            ]);
        }
    }
}