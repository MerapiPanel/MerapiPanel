<?php

namespace MerapiPanel\Box\Module\Entity {

    use Closure;
    use Exception;
    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\AbstractLoader;
    use MerapiPanel\Box\Container;
    use MerapiPanel\Database\DB;
    use MerapiPanel\Exception\Model\Error;
    use PDO;
    use ReflectionObject;
    use ReflectionProperty;
    use Symfony\Component\Filesystem\Path;
    use Throwable;

    class Module
    {

        public readonly string $path;
        public readonly string $namespace;
        protected $fragments = [];
        protected Container $container;
        protected array $listener = [];

        function throwError(Throwable $t, $file = null, $line = null)
        {
            // Extract message, file, and line from the Throwable object
            $message = $t->getMessage();
            $file = $t->getFile(); // Use provided file or fallback to Throwable's file
            $line = $t->getLine(); // Use provided line or fallback to Throwable's line
            if (preg_match("/.*[\/\\\\]include[\/\\\\]Module[\/\\\\]/", $file)) {

                // Process the file path to generate a fragment
                // Remove leading path up to 'Module' and replace directory separators with dots
                $fragment = preg_replace("/.*[\/\\\\]Module[\/\\\\]/", "", $file);
                $fragment = preg_replace("/[\/\\\\]/", ".", $fragment);
                $fragment = preg_replace("/\.php$/", "", $fragment); // Remove .php extension

                // Ensure the fragment is not repeated in the error message
                $errorMessage = "$message $fragment";
            }

            // Create an Error object with the processed message and fragment
            $faker = Error::caught($t, $errorMessage ?? null);

            // Set the file and line in the Error object if available
            if (!empty($file)) {
                $faker->setFile($file);
            }
            if (!empty($line)) {
                $faker->setLine($line);
            }

            // Throw the Error object
            throw $faker;
        }



        public function __construct(Container $container, array $payload)
        {

            $this->container = $container;

            $required_payload = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_READONLY);
            foreach ($required_payload as $value) {
                $name = $value->getName();
                if (array_key_exists($name, $payload)) {
                    $this->$name = $payload[$name];
                } else {
                    throw new Exception("Missing required property: {$name}");
                }
            }
        }


        public function listenOn($key, Closure $callback)
        {
            $this->listener[$key][] = $callback;
        }


        public function getLoader(): AbstractLoader
        {

            return $this->container->getLoader();
        }


        public function __get($name): Proxy|Fragment|null|bool
        {

            if (empty($this->fragments[$name])) {
                $fragment = $this->getLoader()->loadFragment($name, $this);
                $this->fragments[$name] = $fragment;
            } else {
                $fragment = &$this->fragments[$name];
            }
            $result = &$fragment;
            // if (isset($this->listener[$name])) {
            //     foreach ($this->listener[$name] as $callback) {
            //         $callback($result, $this);
            //     }
            // }
            // if (isset($this->listener["*"])) {
            //     foreach ($this->listener["*"] as $callback) {
            //         $callback($name, $result, $this);
            //     }
            // }
            return $result;
        }



        public function __call($method, $args)
        {

            $service = $this->Service;
            if (!$service) {
                throw new Exception("Default service not found in {$this->namespace}");
            }
            $output = $service->$method(...$args);
            $result = &$output;

            // if (isset($this->listener[$method])) {
            //     foreach ($this->listener[$method] as $callback) {
            //         $callback($result, ...$args);
            //     }
            // }
            // if (isset($this->listener["*"])) {
            //     foreach ($this->listener["*"] as $callback) {
            //         $callback($method, $result, ...$args);
            //     }
            // }
            return $result;
        }

        function get($name)
        {
            return $this->__get($name);
        }


        public function __toString()
        {

            return "Module: {$this->namespace}";
        }


        protected Config|null $config = null;

        public function getConfig(): Config
        {

            if (!$this->config) {
                $this->config = new Config($this);
            }

            return $this->config;
        }


        public function getRoles(): Roles
        {
            return new Roles($this);
        }
    }


    /**
     * Class Config - config.json
     */
    class Config implements \Countable
    {

        public array $stack = [];
        protected readonly Module $module;
        protected readonly string $moduleName;


        function __construct($module)
        {
            $this->module = $module;
            $this->moduleName = strtolower(basename($this->module->path));

            $path = Path::join($this->module->path, "config.json");
            if (file_exists($path)) {

                try {

                    $this->stack = $this->normalize(json_decode(file_get_contents($path), true));
                    $this->fetch();
                } catch (Exception $e) {

                    error_log($e->getMessage());
                }
            }
        }


        /**
         * Normalize config array
         */
        private function normalize($items, $stack = [], $prefix = null)
        {
            if (!is_array($items) || empty($items)) {
                return $stack;
            }

            foreach ($items as $value) {
                if (!is_array($value) || !isset($value['name']) || empty($value['name']) || !isset($value['default'])) {
                    continue;
                }

                $name = ($prefix ? "{$prefix}." : "") . preg_replace("/[^a-zA-Z]+/", "_", strtolower($value['name']));
                $default = $value['default'];
                $data = [
                    "type" => $value['type'] ?? "text",
                    "value" => $default,
                    "default" => $default,
                    ...$value,
                    "name" => $name
                ];


                if (isset($value['children'])) {
                    $childrenPrefix = $name;
                    $children = $this->normalize($value['children'], [], $childrenPrefix);
                    $data['children'] = $children;
                }

                $stack[$value['name']] = $data;
            }

            return $stack;
        }




        public function count(): int
        {
            return count($this->stack);
        }



        public function isRequired(string $name): bool
        {
            return isset($this->flatten($this->stack)[$name]['required']);
        }



        /**
         * Set config value
         */
        public function set(string $name, $value)
        {
            if (
                $this->module->Service?->__method_exists("ON_CONFIG_SET")
                && $this->module->Service?->__method_is_static("ON_CONFIG_SET")
            ) {
                // Check if the current function is indirectly called from onConfigSet
                $backtrace = debug_backtrace();
                $callerFunction = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : '';
                if (strtoupper($callerFunction) === 'ON_CONFIG_SET') {
                    error_log("onConfigSet ignored infinite recursion");
                    return;
                }
                $this->module->Service?->ON_CONFIG_SET($name, $value);
            }
            $this->___set($name, $value);
            $this->post();
        }



        private function ___set(string $name, $value)
        {
            $keys = explode('.', $name);
            $current = &$this->stack;
            foreach ($keys as $i => $key) {
                if (!isset($current[$key])) {
                    $current[$key] = [];
                }
                if ($i === count($keys) - 1) {
                    $current[$key]['value'] = $value;
                } else {
                    $current = &$current[$key]['children'];
                }
            }
        }



        /**
         * Get config value
         */
        public function get(string $key)
        {
            $keys = explode('.', $key);
            $current = $this->stack;
            foreach ($keys as $i => $nestedKey) {
                if (!isset($current[$nestedKey])) {
                    return null;
                }
                if ($i === count($keys) - 1) {
                    return $current[$nestedKey]['value'];
                } else if (isset($current[$nestedKey]['children'])) {
                    $current = $current[$nestedKey]['children'];
                } else {
                    return null;
                }
            }
            return null;
        }



        /**
         * Fetch values from database
         */
        private function fetch()
        {

            $result = DB::table("configs")->select(["name", "value"])->where("name")->like($this->moduleName . ".%")->execute()->fetchAll(PDO::FETCH_ASSOC);
            if (!$result) {
                return;
            }

            $flatten = $this->flatten($this->stack);
            foreach ($result as $row) {

                $name = str_replace($this->moduleName . ".", "", $row['name']);
                if (!isset($flatten[$name]) || $flatten[$name]['default'] == $row['value']) {
                    $SQL = "DELETE FROM configs WHERE name = :name";
                    DB::instance()->prepare($SQL)->execute(['name' => $row['name']]);
                    continue;
                }

                $item = $flatten[$name];
                if ($item["type"] === "checkbox" || $item["type"] === "radio") {
                    $row['value'] = $row['value'] === "1" ? true : false;
                }
                if ($flatten[$name]['default'] == $row['value']) {
                    $SQL = "DELETE FROM configs WHERE name = :name";
                    DB::instance()->prepare($SQL)->execute(['name' => $row['name']]);
                    continue;
                }

                $this->___set($name, $row['value']);
            }
        }




        /**
         * Save values to database
         */
        private function post()
        {
            $SQL = "REPLACE INTO configs (name, value) VALUES (:name, :value)";
            $stmt = DB::instance()->prepare($SQL);
            // filter out items that have not changed
            $flatten = array_filter($this->flatten($this->stack), function ($item) {
                return trim($item['value']) !== trim($item['default']);
            });
            foreach ($flatten as $key => $item) {
                $stmt->bindValue(":name", $this->moduleName . "." . $key, PDO::PARAM_STR);
                $stmt->bindValue(":value", $item["value"] ?? "", PDO::PARAM_STR);
                $stmt->execute();
            }


            $SQL = "DELETE FROM configs WHERE name  = :name";
            $sameDefault = array_filter($this->flatten($this->stack), function ($item) {
                return trim($item['value']) === trim($item['default']);
            });

            if (!empty($sameDefault)) {
                $stmt = DB::instance()->prepare($SQL);
                foreach ($sameDefault as $key => $item) {
                    $stmt->bindValue(":name", $this->moduleName . "." . $key, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }



        /**
         * Flatten the stack
         */
        private function flatten($stack, $parentKey = '')
        {
            $result = [];

            foreach ($stack as $key => $value) {
                $newKey = $parentKey ? "$parentKey.$key" : $key;

                if (isset($value['children'])) {
                    $children = $value['children'];
                    unset($value['children']);
                    $result[$newKey] = $value;
                    $result = array_merge($result, $this->flatten($children, $newKey));
                } else {
                    if ($parentKey === '') {
                        $result[$key] = $value;
                    } else {
                        $result[$newKey] = $value;
                    }
                }
            }

            return $result;
        }



        /**
         * Get the stack
         */
        public function getStack()
        {
            return $this->stack;
        }


        function __toArray()
        {
            $output = [];
            foreach ($this->flatten($this->stack) as $item) {
                $output[$item["name"]] = $item["value"];
            }
            return $output;
        }

        function __toString()
        {
            return json_encode($this->__toArray());
        }
    }


    /**
     * Class Roles - roles.json
     */
    class Roles
    {

        public array $roles = [];
        public array $defaults = [];
        protected Module $module;


        function __construct(Module $module)
        {
            $this->module = $module;
            try {
                $path = Path::join($module->path, "roles.json");
                if (file_exists($path)) {
                    $data = json_decode(file_get_contents($path), true);
                    if (isset($data["roles"])) {
                        $this->roles = $data["roles"];
                    }
                    if (isset($data["defaults"])) {
                        $this->defaults = $data["defaults"];
                    }
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }


        /**
         * Get defaults
         */
        function getDefaults()
        {
            return $this->defaults;
        }



        /**
         * Check if role is allowed base on id
         */
        function isAllowed(mixed $id): bool
        {

            if (!empty($this->roles)) {
                $find = array_search($id, array_column($this->roles, "id"));
                if ($find !== false) {
                    $role = $this->roles[$find];
                    $user = $this->getLogedInUser();

                    if ($user) {
                        return $this->isAllowedForUser($role, $user);
                    }
                    //return $role["default"] == true;
                    return false;
                }
            }

            return false;
        }



        /**
         * Check if role is allowed compared to user
         */
        function isAllowedForUser($role, $user): bool
        {

            try {

                $user_id = $user['id'];
                $user_role = $user['role'];
                $rule_name = strtolower(basename($this->module->path)) . "." . $role['id'];

                $SQL = "SELECT * FROM users_roles WHERE user_id = :user_id AND name = :name";
                $stmt = DB::instance()->prepare($SQL);
                $stmt->execute(['user_id' => $user_id, 'name' => $rule_name]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result['value'] == 1;
                }

                $SQL = "SELECT * FROM roles WHERE role = :role AND name = :name";
                $stmt = DB::instance()->prepare($SQL);
                $stmt->execute(['name' => $rule_name, 'role' => $user_role]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result['value'] == 1;
                }

                if (isset($this->defaults[$user_role]['value'][$role['id']])) {
                    return $this->defaults[$user_role]['value'][$role['id']] == 1 || $this->defaults[$user_role]['value'][$role['id']] == true;
                }
                if (isset($role['default']) && in_array($role['default'], [0, false])) {
                    return $role['default'];
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }


            return true;
        }



        /**
         * Get loged in user
         */
        private function getLogedInUser()
        {
            $user = false;
            try {
                $user = Box::module("Auth")->Session->getUser();
            } catch (\Throwable $e) {
                // ignore
                error_log($e->getMessage());
            }
            return $user;
        }



        public function __toArray()
        {
            $data = [
                "roles" => $this->roles,
                "defaults" => $this->defaults
            ];
            return $data;
        }

        public function __toString()
        {
            $data = [
                "roles" => $this->roles,
                "defaults" => $this->defaults
            ];
            return json_encode($data);
        }
    }
}
