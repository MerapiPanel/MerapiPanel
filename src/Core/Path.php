<?php

namespace il4mb\Mpanel\Core;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Path
{


    private $path;




    /**
     * Constructor for the class.
     *
     * @param mixed $path The path to be used. If null, the DOCUMENT_ROOT is used by default.
     * @return void
     */
    public function __construct($path = null)
    {

        if ($path === null) 
        {

            $path = getenv('DOCUMENT_ROOT');

        }

        $this->path = rtrim($path, DIRECTORY_SEPARATOR);

    }





    /**
     * Get the path of the object.
     *
     * @return string The path of the object.
     */
    public function getPath()
    {

        return $this->path;

    }





    /**
     * Set the path for the object.
     *
     * @param string $path The path to set.
     * @return void
     */
    public function setPath($path)
    {
        
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);

    }





    /**
     * Joins multiple parts of a path into a single path.
     *
     * @param mixed ...$parts The parts of the path to join.
     * @return string The joined path.
     */
    public function join(...$parts)
    {
        $parts = array_map(function ($part) 
        {

            return trim($part, DIRECTORY_SEPARATOR);

        }, $parts);

        return $this->path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts);

    }





    /**
     * Check if the file exists.
     *
     * @return bool Returns true if the file exists, false otherwise.
     */
    public function exists()
    {

        return file_exists($this->path);

    }





    /**
     * Creates a directory if it doesn't already exist.
     *
     * @throws Exception if the directory cannot be created.
     * @return bool true if the directory was successfully created, false otherwise.
     */
    public function createDirectory()
    {
        if (!$this->exists()) 
        {

            return mkdir($this->path, 0777, true);

        }

        return false;

    }





    /**
     * Delete the directory.
     *
     * @throws Throwable description of exception
     * @return bool true if the directory is deleted successfully, false otherwise.
     */
    public function deleteDirectory()
    {

        // Check if the directory exists
        if ($this->exists()) 
        {

            // Delete the directory
            return rmdir($this->path);

        }

        // Return false if the directory does not exist
        return false;
        
    }





    /**
     * Checks if the directory is empty.
     *
     * @return bool Returns true if the directory is empty, false otherwise.
     */
    public function isEmptyDirectory()
    {

        // Check if the directory exists
        if ($this->exists()) 
        {

            // Create a DirectoryIterator object with the directory path
            $iterator = new DirectoryIterator($this->path);

            // Return true if the iterator is not valid, which means the directory is empty
            return !$iterator->valid();

        }

        // Return false if the directory does not exist
        return false;
        
    }





    /**
     * Returns the file path for a given filename.
     *
     * @param string $filename The name of the file to retrieve.
     * @return string|false The file path if it exists, false otherwise.
     */
    public function getFile(string $filename): string|false
    {

        // Construct the file path by concatenating the directory path with the filename
        $filePath = $this->path . DIRECTORY_SEPARATOR . $filename;

        // Check if the file exists and is a regular file
        if (file_exists($filePath) && is_file($filePath)) 
        {

            return $filePath;

        }

        // Return false if the file doesn't exist or is not a regular file
        return false;
        
    }





    /**
     * Retrieves the directory path based on the given directory name.
     *
     * @param string $dirname The name of the directory.
     * @return Path|false Returns a Path object if the directory exists, or false if it does not.
     */
    public function getDirectory($dirname)
    {

        // Construct the full directory path by appending the directory name to the base path
        $directoryPath = $this->path . DIRECTORY_SEPARATOR . $dirname;

        // Check if the directory exists and is a directory
        if (file_exists($directoryPath) && is_dir($directoryPath)) 
        {

            // Create and return a new Path object with the directory path
            return new Path($directoryPath);

        }
        
        // Return false if the directory does not exist or is not a directory
        return false;
        
    }




    /**
     * Returns the absolute path from a relative path within the directory.
     *
     * @param string $relativePath The relative path.
     *
     * @return string The absolute path.
     */
    public function getAbsolutePath(string $relativePath): string
    {

        // Concatenate the base path and the relative path
        $absolutePath = $this->path . DIRECTORY_SEPARATOR . $relativePath;
        
        // Resolve any symbolic links and return the absolute path
        return realpath($absolutePath);

    }



    
    /**
     * Checks if a file exists in the specified directory.
     *
     * @param string $filename The name of the file to check.
     * @return bool True if the file exists, false otherwise.
     */
    public function isFile($filename)
    {

        // Construct the file path by concatenating the directory path and the file name
        $filePath = $this->path . DIRECTORY_SEPARATOR . $filename;
        
        // Check if the file exists and if it is a regular file
        return file_exists($filePath) && is_file($filePath);
        
    }




    
    /**
     * Checks if a subdirectory exists in the directory.
     *
     * @param string $dirname - The name of the subdirectory to check.
     * @return bool - True if the subdirectory exists, false otherwise.
     */
    public function isDirectory($dirname)
    {

        // Get the full path of the subdirectory
        $directoryPath = $this->path . DIRECTORY_SEPARATOR . $dirname;
        
        // Check if the subdirectory exists and is a directory
        return file_exists($directoryPath) && is_dir($directoryPath);
        
    }





    /**
     * Calculates the total size of the directory (including all files and subdirectories).
     *
     * @return int The total size of the directory.
     */
    public function getSize()
    {

        // Initialize the size counter.
        $size = 0;

        // Check if the directory exists.
        if ($this->exists()) 
        {

            // Create a recursive iterator to traverse the directory.
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->path)
            );

            // Iterate over each file in the directory.
            foreach ($iterator as $file) 
            {

                // Check if the current item is a file.
                if ($file->isFile())
                {

                    // Add the size of the file to the total size counter.
                    $size += $file->getSize();

                }

            }

        }

        // Return the total size of the directory.
        return $size;
        
    }





    /**
     * Converts the object to a string representation.
     *
     * @return string The string representation of the object.
     */
    public function __toString()
    {
        return $this->path;
    }
}
