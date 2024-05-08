<?php

namespace MerapiPanel\Box\Module\Entity {

    use Closure;
    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\AbstractLoader;
    use MerapiPanel\Box\Container;
    use MerapiPanel\Database\DB;
    use PDO;
    use ReflectionObject;
    use ReflectionProperty;
    use Symfony\Component\Filesystem\Path;



    class Module
    {

        public readonly string $path;
        public readonly string $namespace;
        protected $fragments = [];
        protected Container $container;
        protected array $listener = [];



        public function __construct(Container $container, array $payload)
        {

            $this->container = $container;

            $required_payload = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_READONLY);
            foreach ($required_payload as $value) {
                $name = $value->getName();
                if (array_key_exists($name, $payload)) {
                    $this->$name = $payload[$name];
                } else {
                    throw new \Exception('Missing required property: ' . $name);
                }
            }
        }


        public function __on($key, Closure $callback)
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
            if (isset($this->listener[$name])) {
                foreach ($this->listener[$name] as $callback) {
                    $callback($result, $this);
                }
            }
            if (isset($this->listener["*"])) {
                foreach ($this->listener["*"] as $callback) {
                    $callback($name, $result, $this);
                }
            }
            return $result;
        }



        public function __call($method, $args)
        {
            $service = $this->Service;
            if (!$service) {
                throw new \Exception("Default service not found in " . $this->namespace);
            }
            return $service->$method(...$args);
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

                    $data = json_decode(file_get_contents($path), true);

                    foreach ($data as $value) {
                        if (!is_array($value) || !isset($value['name']) || !isset($value['default'])) {
                            continue;
                        }
                        $name = preg_replace("/[^a-zA-Z]+/", "_", strtolower($value['name']));
                        $default = $value['default'];
                        $this->stack[$name] = [
                            "type" => "text",
                            "value" => null,
                            "default" => $default,
                            ...$value
                        ];
                    }

                    $this->fetch();
                } catch (\Exception $e) {

                    error_log($e->getMessage());
                }
            }
        }



        public function count(): int
        {
            return count($this->stack);
        }



        public function set(string $name, $value)
        {

            $key = strtolower($name);
            if (!isset($this->stack[$key])) {
                return;
            }

            $this->stack[$key] = [
                "name" => $name,
                "default" => $this->stack[$key]["default"],
                "value" => $value
            ];


            if ($value == $this->stack[$key]["default"] || empty($value)) {
                $SQL = "DELETE FROM configs WHERE name = :name";
                DB::instance()->prepare($SQL)->execute(['name' => $this->moduleName . "." . $key]);
            }

            $this->post();
        }


        public function get(string $key)
        {
            if (!isset($this->stack[$key])) {
                return null;
            }
            if (isset($this->stack[$key]["value"]) && $this->stack[$key]["value"]) {
                return $this->stack[$key]["value"];
            }
            return $this->stack[$key]['default'];

        }


        private function fetch()
        {

            $result = DB::table("configs")->select(["name", "value"])->where("name")->like($this->moduleName . ".%")->execute()->fetchAll(PDO::FETCH_ASSOC);

            if (!$result) {
                return;
            }

            foreach ($result as $row) {

                $name = str_replace($this->moduleName . ".", "", $row['name']);

                if (!isset($this->stack[$name])) {
                    $SQL = "DELETE FROM configs WHERE name = :name";
                    DB::instance()->prepare($SQL)->execute(['name' => $row['name']]);

                    continue;
                }
                $this->stack[$name] = [
                    ...$this->stack[$name],
                    "value" => $row['value']
                ];
            }
        }


        private function post()
        {
            $SQL = "REPLACE INTO configs (name, value) VALUES (:name, :value)";
            $stmt = DB::instance()->prepare($SQL);
            foreach (array_keys($this->stack) as $key) {
                if (!isset($this->stack[$key]["value"]) || !$this->stack[$key]["value"]) {
                    continue;
                }
                $stmt->bindValue(":name", $this->moduleName . "." . $key, PDO::PARAM_STR);
                $stmt->bindValue(":value", $this->stack[$key]["value"], PDO::PARAM_STR);
                $stmt->execute();
            }
        }

        public function getStack()
        {
            return $this->stack;
        }

        function __toArray()
        {
            $data = [];
            foreach ($this->stack as $key => $value) {
                $data[$key] = $value["value"] ?: $value["default"];
            }
            return $data;
        }

        function __toString()
        {
            return json_encode($this->__toArray());
        }
    }


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


        function getDefaults()
        {
            return $this->defaults;
        }



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
                    return $role["default"] == true;
                }
            }

            return true;
        }



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



        private function getLogedInUser()
        {
            $user = false;
            try {
                $user = Box::module("Auth")->getLogedinUser();
            } catch (\Throwable $e) {
                // ignore
                error_log($e->getMessage());
            }
            return $user;
        }



        public function __toArray()
        {
            $data = [
                "rules" => $this->rules,
                "defaults" => $this->defaults
            ];
            return $data;
        }

        public function __toString()
        {
            $data = [
                "rules" => $this->rules,
                "defaults" => $this->defaults
            ];
            return json_encode($data);
        }
    }

}