<?php

namespace il4mb\Mpanel\Core;

class File
{
    
    
    protected $path;


    /**
     * Creates a new instance of the class.
     *
     * @param string $path The path to be assigned to the object.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }




    /**
     * Returns a Directory object representing the directory of the current instance.
     *
     * @return Directory
     */
    public function getDirectory(): Directory
    {
        return new Directory($this->path);
    }


    

    /**
     * Returns the MIME type of a file.
     *
     * @return string The MIME type of the file.
     */
    public function mime()
    {
        return mime_content_type($this->path);
    }


    
    /**
     * Retrieves the size of the file.
     *
     * @return int The size of the file in bytes.
     */
    public function size()
    {
        return filesize($this->path);
    }


    
    /**
     * Deletes the file at the specified path.
     *
     * @throws IOException If an I/O error occurs.
     * @return bool True on success, false on failure.
     */
    public function delete()
    {
        return unlink($this->path);
    }



    
    /**
     * Moves the file or directory to the specified destination.
     *
     * @param string $destination The path to the new location.
     * @throws RuntimeException If the file or directory cannot be moved.
     * @return bool True on success, false on failure.
     */
    public function move($destination)
    {
        return rename($this->path, $destination);
    }



    
    /**
     * Writes the given content to a file.
     *
     * @param string $content The content to write to the file.
     * @throws Some_Exception_Class Description of the exception that may be thrown.
     * @return bool Returns the number of bytes written to the file, or false on failure.
     */
    public function write(string $content)
    {
        return file_put_contents($this->path, $content);
    }



    
    /**
     * Checks if a file exists.
     *
     * @throws Some_Exception_Class description of exception
     * @return bool true if the file exists, false otherwise
     */
    public function is_exists()
    {
        return file_exists($this->path);
    }



    
    /**
     * Returns a string representation of the object.
     *
     * @return string The path of the object.
     */
    public function __toString()
    {
        return $this->path;
    }
}
