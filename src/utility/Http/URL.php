<?php

namespace Mp\Utility\Http;

class URL
{

    protected string $URL = "";
    protected string $QUERY = "";
    protected string $index;



    /**
     * Constructs a new instance of the class.
     *
     * This function initializes the URL and QUERY properties of the object
     * based on the current server environment.
     *
     * @throws None
     * @return None
     */
    public function __construct($url = null)
    {

        $protocol =  ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        if ($url == null) 
        {

            $this->URL   = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $this->QUERY = $_SERVER['QUERY_STRING'];

        } 
        else 
        {

            $parse = parse_url($url);
            $url   = (isset($parse['scheme']) ? $parse['scheme'] . "://" : $protocol) . (isset($parse['host']) ? $parse['host'] : $_SERVER['HTTP_HOST']) . $parse['path'];

            $this->URL   = $url;
            $this->QUERY = isset($parse['query']) ? $parse['query'] : "";

        }

    }




    /**
     * Sets the index at the specified path.
     *
     * @param mixed $path The path to set the index at.
     * @throws Some_Exception_Class Description of exception.
     * @return void
     */
    public function setIndexAt($path)
    {

        $this->index = $path;

    }




    /**
     * Get the path of the URL.
     *
     * @return string The path of the URL.
     */
    public function getPath($idx = null)
    {

        $path    = parse_url($this, PHP_URL_PATH);
        $extract = array_values(array_filter(explode("/", $path)));

        if (isset($extract[$idx])) 
        {

            return $extract[$idx];

        }

        return implode("/", $extract);

    }




    
    /**
     * Returns the size of the path as an integer.
     *
     * @return int The size of the path.
     */
    public function getPathSize()
    {

        return count($this->getPathAsArray());

    }




    /**
     * Returns the path as an array.
     *
     * @return array The path as an array.
     */
    public function getPathAsArray()
    {

        return array_values(array_filter(explode("/", $this->getPath())));

    }




    /**
     * Retrieves the query string from a given URL.
     *
     * @param string $url The URL to extract the query string from.
     * @return string|null The query string from the URL, or null if not present.
     */
    public function getQuery()
    {

        return parse_url($this, PHP_URL_QUERY);

    }




    /**
     * Returns a string representation of the object.
     *
     * @return string The URL of the object.
     */
    public function __toString()
    {

        return $this->URL;

    }



    
    /**
     * Remove the path elements from the source path that match the elements in the finder path.
     *
     * @param string $finder The path to be used as the finder.
     * @param string $source The path to be used as the source.
     * @return string The source path with the matching elements removed.
     */
    public static function pathRemove($finder, $source)
    {

        $finder = str_replace("\\", "/", $finder);
        $source = str_replace("\\", "/", $source);

        $finder = array_values(array_filter(explode("/", $finder)));
        $source = array_values(array_filter(explode("/", $source)));

        foreach ($source as $key => $value) 
        {

            for ($i = $key; $i < count($finder); $i++) 
            {

                if ($value == $finder[$i]) 
                {

                    unset($source[$key]);

                }

            }

        }

        return implode("/", $source);

    }



    

    /**
     * Creates a normalized path from a given string path.
     *
     * @param string $stringPath The string path to create a normalized path from.
     * @return string The normalized path.
     */
    public static function createPath($stringPath)
    {

        $paths      = explode("/", $stringPath);
        $paths      = array_values(array_filter($paths));
        $path_list  = [];

        foreach ($paths as $path) 
        {

            if ($path == "..") 
            {

                unset($path_list[count($path_list) - 1]);
                break;

            }

            $path_list[] = $path;

        }

        return "/" . implode("/",  $path_list);

    }




    
    /**
     * Checks if a given file path is a valid URL.
     *
     * @param string $filePath The file path to be checked.
     * @return bool Returns true if the file path is a valid URL, false otherwise.
     */
    public static function isURL($filePath)
    {

        // Regular expression to match common URL patterns
        $urlPattern = '/^(?:\w+:)?\/\/(\S+)$/';

        // Test the file path against the URL pattern
        return preg_match($urlPattern, $filePath) === 1;

    }
    
}