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


            if (count($fragments) > 1) {
                $fragments = array_slice($fragments, 1);
                array_pop($fragments);
                foreach ($fragments as $key => $fragment) {
                    $service = $service->$fragment;
                }
            }

            preg_match("/\((.*?)\)/", $method, $match);
            if (isset($match[1])) {
                $raw_params = $this->rawParams($match[1]);
                $params = $this->paramValues($raw_params);
                $method = preg_replace("/\(.*/", "", $method);

                if (is_array($params)) {
                    $result = $service->$method(...$params);
                } else {
                    $result = $service->$method($params);
                }

            } else {

                $result = $service->$method();
            }

        } catch (\Throwable $e) {
            $result = $e->getMessage();
        }

        return $result;
    }


    function parseVariableValue($value)
    {

        $value = trim($value);

        // If it's an empty string, return an empty array
        if (empty($value)) {
            return [];
        }
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }


        preg_match_all('/=>[^"]*\b[\w.]+\b/im', $value, $matches);
        if (isset($matches[0])) {
            foreach ($matches[0] as $key => $match) {
                $variable_name = trim(preg_replace('/^\=\>/', '', $match));

                $variable_val = $this->getVariable($variable_name);

                if (is_array($variable_val)) {
                    $variable_val = json_encode($variable_val);
                }
                $value = str_replace($match, "=> \"" . $variable_val . "\"", $value);
            }
        }


        try {
            $parsed = eval ("return " . $value . ";");
            if (is_array($parsed)) {
                return $parsed;
            }
        } catch (\Exception $e) {
            // Do nothing
        }

        // If it's a JSON string, decode it and return
        if ($decoded = json_decode($value, true)) {
            return $decoded;
        }
        // If it's an array string representation, parse it
        if (preg_match('/^\[.*\]$/', $value)) {

            // Remove the brackets
            $value = substr($value, 1, -1);
            // Split the string by commas
            $items = explode(',', $value);
            // Trim each item and parse recursively if necessary
            $result = [];
            foreach ($items as $item) {
                $item = trim($item);
                if (preg_match('/^\[.*\]$/', $item)) {
                    $result[] = $this->parseVariableValue($item);
                } elseif (preg_match('/^\{.*\}$/', $item)) {
                    $result[] = $this->parseVariableValue($item);
                } else {
                    if ($this->isQuoted($item)) {
                        $item = substr($item, 1, -1);
                    }
                    $result[] = $item;
                }
            }
            return $result;
        }
        // If it's a key-value pair string representation, parse it
        if (preg_match('/\{(.*)\}/', $value, $matches)) {
            // Extract key-value pairs
            $pairs = explode(',', $matches[1]);
            // Initialize an associative array
            $result = [];
            foreach ($pairs as $pair) {
                list($key, $val) = explode('=>', $pair);
                $key = trim($key);
                $val = trim($val);
                $result[$key] = $this->parseVariableValue($val);
            }
            return $result;
        }
        // If none of the above, return the value as is
        return $value;
    }


    // function parseVariableValue($value, $stack = [])
    // {
    //     $pattern = '/\[(.*?)\]/';
    //     preg_match($pattern, $value, $matches);

    //     if (isset($matches[1])) {
    //         $temp = [];
    //         $arrayStr = $matches[1];

    //         // Check if the array string contains key-value pairs
    //         if (str_contains($arrayStr, "=>")) {
    //             // Split the string into key-value pairs
    //             $pairs = explode(",", $arrayStr);

    //             foreach ($pairs as $pair) {
    //                 // Split each pair into key and value
    //                 [$key, $val] = explode("=>", $pair);

    //                 // Trim whitespace
    //                 $key = trim($key);
    //                 $val = trim($val);

    //                 // Remove quotes if present
    //                 if ($this->isQuoted($val)) {
    //                     $val = substr($val, 1, -1);
    //                 }

    //                 // Parse variables or get variable values
    //                 if (preg_match($pattern, $val, $matches)) {
    //                     $val = $this->parseVariableValue($val, $stack);
    //                 } else if (is_string($val)) {
    //                     $val = $this->getVariable($val);
    //                 }

    //                 // Remove quotes if present
    //                 $key = $this->isQuoted($key) ? substr($key, 1, -1) : $key;

    //                 // Assign key-value pair to temporary array
    //                 $temp[$key] = $val;
    //             }
    //         } else {
    //             // If no key-value pairs, treat as a simple array
    //             $items = array_map('trim', explode(",", $arrayStr));
    //             foreach ($items as $item) {
    //                 if ($this->isQuoted($item)) {
    //                     $temp[] = substr($item, 1, -1);
    //                 } else if (preg_match($pattern, $item, $matches)) {
    //                     $temp[] = $this->parseVariableValue($item, $stack);
    //                 } else {
    //                     $temp[] = $this->getVariable($item);
    //                 }
    //             }
    //         }

    //         // Push the temporary array onto the stack
    //         $stack[] = $temp;

    //         // Replace the array string with an empty string
    //         $value = str_replace($matches[0], "", $value);

    //         // Recursively parse any remaining content in the value
    //         if (strlen($value) > 0) {
    //             return $this->parseVariableValue($value, $stack);
    //         }
    //     } else {
    //         // If no array notation found, treat as a single value
    //         if (str_contains($value, ",")) {
    //             // If value contains commas, split and add each item to the stack
    //             $items = array_map('trim', explode(",", $value));
    //             foreach ($items as $other) {
    //                 $stack[] = $other;
    //             }
    //         } else {
    //             // If single value, add it to the stack
    //             $stack[] = $value;
    //         }
    //     }

    //     // Handle stack transformation
    //     if (is_array($stack) && count($stack) == 1) {
    //         $stack = $stack[0];
    //     } else if (is_array($stack) && count($stack) > 1) {
    //         $stack = array_values($stack);
    //     }

    //     return $stack;
    // }



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

        } else if (is_string($name) && str_contains($name, ".")) {

            $fragments = explode(".", $name);
            $key = array_search($fragments[0], array_column($this->variables, 'name'));

            if ($key !== false) {
                $value = $this->getVariable($fragments[0]);
                unset($fragments[0]);
                foreach ($fragments as $fragment) {
                    try {
                        $value = $value[$fragment] ?? [];
                    } catch (\Exception $e) {
                        $value = null;
                    }
                }
            } else {
                $value = null;
            }

        } else if (is_string($value) && $this->isQuoted($value)) {
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

        // if (count($stack) == 1) {
        //     return $stack[0];
        // }
        return $stack;
    }




    function isQuoted($string)
    {
        return preg_match('/^["\'].*["\']$/', $string);
    }
}