<?php

namespace il4mb\Mpanel\Core;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Directory
{


    private $path;



    /**
     * Constructor for the class.
     *
     * @param string $path The path to be processed.
     */
    public function __construct($path)
    {

        preg_match('/\.\w+$/', $path, $matches);

        if (isset($matches[0])) 
        {

            $this->path = self::normalizePath(pathinfo($path, PATHINFO_DIRNAME));

        }
        else 
        {

            $this->path = self::normalizePath($path);

        }
    }



    
    /**
     * Normalize a given path.
     *
     * @param string $path The path to be normalized.
     * @return string The normalized path.
     */
    private static function normalizePath($path)
    {

        if (strpos(PHP_OS, 'WIN') === 0) 
        {

            $path = str_replace("\\", "/", $path);

        }

        // filter
        $parts = explode("/", $path);

        $normalizedParts = array_filter($parts);

        return (strpos(PHP_OS, 'WIN') !== 0 ? "/" : "") . implode("/", $normalizedParts);

    }




    /**
     * Load a path and return a File object.
     *
     * @param string $path The path to load.
     * @return void|File Returns a File object if the path is valid, otherwise void.
     */
    public function loadPath($path) : string|File
    {

        $the_path = $this->normalizePath($this->path . "/" . $path);

        $relativeParts = explode('/', $the_path);

        $stack = array();

        foreach ($relativeParts as $part) 
        {

            if ($part === '..') 
            {
                // If we encounter '..', pop the last element from the stack (move up one level)
                array_pop($stack);

            }
             else if ($part !== '' && $part !== '.') 
            {
                // Ignore empty parts and '.'
                array_push($stack, $part);

            }
        }

        $finalpath = strpos(PHP_OS, 'WIN') !== 0 ? "/" : "" . implode("/", $stack);

        preg_match('/\.\w+$/', $finalpath, $matches);

        if (isset($matches[0])) 
        {

            $this->path = pathinfo($finalpath, PATHINFO_DIRNAME);

            return new File($finalpath);

        }

        $this->path = $finalpath;

        return $finalpath;

    }


    

    /**
     * Get the absolute path of the object.
     *
     * @return string The absolute path.
     */
    public function getAbsolutePath()
    {

        return  $this->path;

    }



    
    /**
     * Check if the file exists.
     *
     * @return bool Returns true if the file exists, otherwise false.
     */
    public function exists()
    {
        return file_exists($this->path);
    }



    
    /**
     * Create the directory if it does not exist.
     *
     * @throws Some_Exception_Class description of exception
     * @return boolean Returns true if the directory is created successfully, false otherwise.
     */
    public function create()
    {

        if (!$this->exists()) 
        {

            return mkdir($this->path, 0777, true);

        }

        return false;

    }



    
    /**
     * Deletes the file or directory represented by this object.
     *
     * @throws Some_Exception_Class If the file or directory does not exist.
     * @return bool True if the file or directory was successfully deleted, false otherwise.
     */
    public function delete()
    {

        if ($this->exists()) 
        {

            return rmdir($this->path);

        }

        return false;
    }



    
    /**
     * Checks if the directory is empty.
     *
     * @throws Some_Exception_Class Description of exception
     * @return bool True if the directory is empty, false otherwise.
     */
    public function isEmpty()
    {

        if ($this->exists()) 
        {

            $iterator = new DirectoryIterator($this->path);

            return !$iterator->valid();

        }

        return false;

    }




    
    /**
     * Retrieves the list of files in the directory.
     *
     * @throws DirectoryNotFoundException if the directory does not exist.
     * @return array an array containing the names of the files in the directory.
     */
    public function getFiles() : Array
    {

        $files = [];

        if ($this->exists()) 
        {

            $iterator = new DirectoryIterator($this->path);

            foreach ($iterator as $fileInfo) 
            {

                if ($fileInfo->isFile()) 
                {

                    $files[] = $fileInfo->getFilename();

                }

            }

        }

        return $files;

    }




    /**
     * Retrieves a list of directories within the directory.
     *
     * @return array
     */
    public function getDirectories()
    {

        $directories = [];

        if ($this->exists()) 
        {

            $iterator = new DirectoryIterator($this->path);

            foreach ($iterator as $fileInfo) 
            {

                if ($fileInfo->isDir() && !$fileInfo->isDot()) 
                {

                    $directories[] = $fileInfo->getFilename();

                }

            }

        }

        return $directories;

    }




    /**
     * Retrieves the file path for the given filename.
     *
     * @param string $filename The name of the file to retrieve.
     * @return string|false The file path if it exists, false otherwise.
     */
    public function getFile($filename)
    {

        $filePath = $this->path . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filePath) && is_file($filePath)) 
        {

            return $filePath;

        }

        return false;

    }



    
    /**
     * Retrieves a directory object for the given directory name.
     *
     * @param string $dirname The name of the directory.
     * @throws Some_Exception_Class Description of exception.
     * @return Directory|bool The directory object if it exists, otherwise false.
     */
    public function getDirectory($dirname)
    {
        $directoryPath = $this->path . DIRECTORY_SEPARATOR . $dirname;

        if (file_exists($directoryPath) && is_dir($directoryPath)) 
        {

            return new Directory($directoryPath);

        }

        return false;

    }



    
    /**
     * Copies the file to the specified destination.
     *
     * @param string $destination The destination path where the file should be copied to.
     * @throws Exception If the file does not exist.
     * @return bool True if the file was successfully copied, false otherwise.
     */
    public function copyTo($destination)
    {
        if ($this->exists()) 
        {

            return $this->recursiveCopy($this->path, $destination);

        }

        return false;

    }



    
    /**
     * Recursively copies files and directories from source to destination.
     *
     * @param string $src The source directory or file path.
     * @param string $dst The destination directory or file path.
     * @throws Exception If there is an error during the copying process.
     * @return void
     */
    private function recursiveCopy($src, $dst)
    {

        if (is_dir($src))
        {

            $dir = opendir($src);

            @mkdir($dst);

            while (($file = readdir($dir)) !== false) 
            {

                if ($file != '.' && $file != '..') 
                {

                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);

                }

            }

            closedir($dir);

        } 
        else if (is_file($src)) 
        {

            copy($src, $dst);

        }

    }



    
    /**
     * Moves the file to the specified destination.
     *
     * @param string $destination The path to the destination directory or file.
     * @return bool True if the file was successfully moved, false otherwise.
     */
    public function moveTo($destination)
    {

        if ($this->exists()) 
        {

            return rename($this->path, $destination);
        
        }

        return false;

    }



    
    /**
     * Renames the file or directory to the specified new name.
     *
     * @param string $newName The new name for the file or directory.
     * @return bool Returns true if the rename operation was successful, false otherwise.
     */
    public function rename($newName)
    {

        if ($this->exists()) 
        {

            $directoryPath = dirname($this->path);

            $newPath = $directoryPath . DIRECTORY_SEPARATOR . $newName;

            return rename($this->path, $newPath);

        }

        return false;

    }



    
    /**
     * Checks if a file exists in the specified directory.
     *
     * @param string $filename The name of the file to check.
     * @return bool Returns true if the file exists and is a regular file, false otherwise.
     */
    public function isFile($filename)
    {

        $filePath = $this->path . DIRECTORY_SEPARATOR . $filename;

        return file_exists($filePath) && is_file($filePath);

    }



    
    /**
     * Check if a given directory exists.
     *
     * @param string $dirname The name of the directory.
     * @throws None
     * @return bool Returns true if the directory exists and is a directory, false otherwise.
     */
    public function isDirectory($dirname)
    {

        $directoryPath = $this->path . DIRECTORY_SEPARATOR . $dirname;

        return file_exists($directoryPath) && is_dir($directoryPath);

    }



    
    /**
     * Calculates the total size of the directory.
     *
     * @throws Exception if the directory does not exist
     * @return int the total size of the directory in bytes
     */
    public function getSize()
    {

        $size = 0;

        if ($this->exists()) 
        {

            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));

            foreach ($iterator as $file) 
            {

                if ($file->isFile()) 
                {

                    $size += $file->getSize();

                }

            }

        }

        return $size;

    }



    
    /**
     * Convert the object to its string representation.
     *
     * @return string The string representation of the object.
     */
    function __toString()
    {

        return $this->path;
        
    }
}
