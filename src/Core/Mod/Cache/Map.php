<?php

namespace MerapiPanel\Core\Mod\Cache;

use ArrayAccess;

class Map implements ArrayAccess
{
    private $fileName = "map.json";
    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . "/" . $this->fileName;
    }

    public function offsetExists($offset): bool
    {
        $data = $this->loadData();
        return isset($data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        $data = $this->loadData();
        return $data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $data = $this->loadData();
        $data[$offset] = $value;
        $this->saveData($data);
    }

    public function offsetUnset($offset): void
    {
        $data = $this->loadData();
        unset($data[$offset]);
        $this->saveData($data);
    }

    private function loadData()
    {
        if (file_exists($this->file)) {
            $data = file_get_contents($this->file);
            return json_decode($data, true);
        }
        return [];
    }

    private function saveData($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
