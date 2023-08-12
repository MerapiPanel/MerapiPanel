<?php

namespace il4mb\Mpanel\Core;

class Utilities
{


    /**
     * Compare two paths and check if they are equal.
     *
     * @param string $path1 The first path to compare.
     * @param string $path2 The second path to compare.
     * @return bool True if the paths are equal, false otherwise.
     */
    function comparePaths(string $path1, string $path2): bool
    {

        // Normalize paths by removing trailing slashes
        $normalizedPath1 = rtrim($path1, '/');
        $normalizedPath2 = rtrim($path2, '/');

        // Compare the normalized paths
        return $normalizedPath1 === $normalizedPath2;

    }





    /**
     * Get the MIME type of a file based on its extension.
     *
     * @param string $filename The name of the file.
     * @return string The MIME type of the file.
     */
    static function getMimeType($filename)
    {

        // Get the file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Check the file extension and return the corresponding MIME type
        if ($extension === 'css') 
        {

            return 'text/css';

        } 
        elseif ($extension === 'js') 
        {

            return 'text/javascript';

        } 
        else 
        {

            // If the file extension is not css or js, use the mime_content_type function to get the MIME type
            return mime_content_type($filename);

        }

    }
    
}
