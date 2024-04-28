<?php

namespace MerapiPanel\Box\Module\Entity {

    use Closure;
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

                    $data = json_decode(file_get_contents($path),  true);

                    foreach ($data as $value) {
                        if(!is_array($value) || !isset($value['name']) || !isset($value['default'])) {
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



        public function count()
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


}