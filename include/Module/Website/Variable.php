<?php

namespace MerapiPanel\Module\Website;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;

class Variable extends __Fragment
{
    protected $variables = [];
    protected $params = [];
    protected $sandbox = false;
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    function test($variables = [], $params = [])
    {

        $this->sandbox = true;
        $this->variables = $variables;
        $this->params = $params;

        $output = [];
        foreach ($this->variables as $variable) {
            $name = $variable['name'];
            $output[$name] = $this->getVariable($name);
        }

        return $output;
    }


    function execute($variables = [])
    {

        if (!is_array($variables)) {
            return [];
        }
        $this->variables = $variables;

        $output = [];
        foreach ($this->variables as $variable) {
            $name = $variable['name'];
            $output[$name] = $this->getVariable($name);
        }

        return $output;

    }



    function excecuteDynamic($service, $method, $fragments = [])
    {

        $result = null;

        try {

            preg_match("/\((.*?)\)/", $method, $match);
            if (isset($match[1])) {
                $raw_params = $this->rawParams($match[1]);
                //       error_log("raw_params: " . print_r($raw_params, true));
                $params = $this->paramValues($raw_params);
                //         error_log("params: " . print_r($params, true));
                $method = preg_replace("/\(.*/", "", $method);
                $result = $service->$method(...$params);

            } else {
                $result = $service->$method();
            }

        } catch (\Exception $e) {
            $result = null;
        }

        return $result;
    }


    function parseVariableValue($value, $stack = [])
    {
        $pattern = '/\[(.*?)\]/';
        preg_match($pattern, $value, $matches);

        if (isset($matches[1])) {
            $temp = [];
            foreach (array_values(array_filter(explode(",", $matches[1]))) as $item) {
                if (str_contains($item, "=>")) {
                    [$key, $val] = explode("=>", $item);
                    $key = trim($key);
                    $val = trim($val);

                    if ($this->isQuoted($val)) {
                        $val = substr($val, 1, -1);
                    } else if (is_string($val)) {
                        $val = $this->getVariable($val);
                    }

                    $key = $this->isQuoted($key) ? substr($key, 1, -1) : $key;
                    $temp[$key] = $val;
                    continue;
                }
                $val = trim($item);
                if ($this->isQuoted($val)) {
                    $temp[] = substr($val, 1, -1);
                    continue;
                }
                $temp[] = $this->getVariable($val);
            }
            $stack[] = $temp;


            $value = str_replace($matches[0], "", $value);
            if (strlen($value) > 0) {
                return $this->rawParams($value, $stack);
            }
        } else {
            if (str_contains($value, ",")) {
                foreach (array_values(array_filter(explode(",", $value))) as $other) {
                    $stack[] = $other;
                }
            } else {
                $stack[] = $value;
            }
        }
        if (is_array($stack) && count($stack) == 1) {
            $stack = $stack[0];
        } else if (is_array($stack) && count($stack) > 1) {
            $stack = array_values($stack);
        }

        return $stack;
    }


    function nestedVariableValue($value, $stack = [])
    {
        if (is_string($value)) {

            if ($this->isQuoted($value)) {
                $stack[] = $value;
            } else if (strpos($value, "params.") === 0) {
                if ($this->sandbox) {
                    $stack[] = $this->params[$value] ?? null;
                } else {
                    $stack[] = Request::getInstance()->$value;
                }
            } else if (preg_match("/::/im", $value)) {

                $fragments = explode("::", $value);
                $moduleName = $fragments[0];
                $service = Box::module($moduleName);
                $method = end($fragments);
                $stack[] = $this->excecuteDynamic($service, $method, $fragments);

            } else {

                $stack[] = $value;
            }
        } else if (is_array($value)) {
            foreach ($value as $key => $val) {
                $stack[$key] = $this->nestedVariableValue($val, []);

            }
        }
        if (count($stack) == 1) {
            return $stack[0];
        }
        return $stack;
    }

    function getVariable($name)
    {

        $value = null;
        $key = array_search($name, array_column($this->variables, 'name'));

        if ($key !== false) {

            $variable = $this->variables[$key];
            $value = $variable['value'];

            if (is_string($value) && strpos($value, "params.") === 0) {
                $paramName = substr($value, 7);
                if ($this->sandbox) {
                    $value = $this->params[$paramName] ?? null;
                } else {
                    $value = Request::getInstance()->$paramName();
                }
            } else if (is_string($value) && preg_match("/::/im", $value)) {
                try {
                    $fragments = explode("::", $value);
                    $moduleName = $fragments[0];
                    $service = Box::module($moduleName);
                    $method = end($fragments);
                    $value = $this->excecuteDynamic($service, $method, $fragments);
                } catch (\Exception $e) {
                    $value = null;
                }
            } else {
                $value = $this->parseVariableValue($value);
            }
        }

        if (is_string($value) && $this->isQuoted($value)) {
            $value = substr($value, 1, -1);
        }

        return $value;
    }

    function rawParams($params = "", $stack = [])
    {
        // Regular expression pattern to extract the array
        $pattern = '/\[(.*?)\]/';

        // Perform the regular expression match
        preg_match($pattern, $params, $matches);

        if (isset($matches[1])) {
            $stack[] = explode(",", $matches[1]);
            $params = str_replace($matches[0], "", $params);
            if (strlen($params) > 0) {
                return $this->rawParams($params, $stack);
            }
        } else {
            foreach (array_values(array_filter(explode(",", $params))) as $other) {
                $stack[] = $other;
            }
        }
        return array_values($stack);
    }



    function paramValues($params = [], $stack = [])
    {
        if (!is_array($params)) {
            return $params;
        }
        if (empty($params)) {
            return $stack;
        }

        foreach ($params as $key => $param) {
            if (is_string($param)) {
                $param = trim($param);
                if ($this->isQuoted($param)) {
                    $stack[$key] = trim(substr($param, 1, -1));
                } else if (strpos($param, "params.") === 0) {
                    $paramName = substr($param, 7);
                    if ($this->sandbox) {
                        $stack[$key] = $this->params[$paramName] ?? null;
                    } else {
                        $stack[$key] = Request::getInstance()->$paramName();
                    }
                } else {
                    $stack[$key] = $this->getVariable($param);
                }
            } else if (is_array($param)) {
                $stack[$key] = $this->paramValues($param, []);
            }
        }

        if (count($stack) == 1) {
            return $stack[0];
        }
        return $stack;
    }




    function isQuoted($string)
    {
        return preg_match('/^["\'].*["\']$/', $string);
    }
}