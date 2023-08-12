<?php

namespace il4mb\Mpanel\Config;

class Config
{

    
    protected $configData;


    /**
     * Constructs a new instance of the class.
     *
     * @param array $configData Optional configuration data for the instance
     */
    public function __construct(array $configData = [])
    {

        $this->configData = $configData;

    }



    /**
     * Retrieves the value corresponding to the given key from the config data array.
     *
     * @param string $key The key for which the value needs to be retrieved.
     * @param mixed $default The default value to be returned if the key does not exist in the config data array.
     * @return mixed The value corresponding to the given key, or the default value if the key does not exist.
     */
    public function get(string $key, $default = null)
    {

        return $this->configData[$key] ?? $default;

    }




    /**
     * Sets a value in the config data array.
     *
     * @param string $key The key to set in the config data array.
     * @param mixed $value The value to set for the given key.
     * @return void
     */
    public function set(string $key, $value): void
    {

        $this->configData[$key] = $value;
        
    }




    /**
     * Checks if a given key exists in the configuration data.
     *
     * @param string $key The key to check for existence.
     * @return bool Returns true if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {

        return isset($this->configData[$key]);

    }




    /**
     * Retrieves all the elements from the configData array.
     *
     * @return array An array containing all the elements from the configData array.
     */
    public function all(): array
    {

        return $this->configData;

    }




    /**
     * Merges the given array of config data with the existing config data.
     *
     * @param array $configData The array of config data to merge.
     * @throws \Exception If an error occurs during the merge process.
     * @return void
     */
    public function merge(array $configData): void
    {

        $this->configData = array_merge($this->configData, $configData);

    }




    /**
     * Removes a specified key from the configData array.
     *
     * @param string $key The key to be removed.
     * @throws None
     * @return void
     */
    public function remove(string $key): void
    {

        unset($this->configData[$key]);

    }




    /**
     * Clears the config data.
     */
    public function clear(): void
    {

        $this->configData = [];

    }

}
