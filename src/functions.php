<?php

namespace il4mb\Mpanel;


/**
 * Scans a directory and returns a formatted tree structure of its contents.
 *
 * @param string $dir The directory to scan.
 * @param string $prefix The prefix to add to each line of the output.
 * @param bool $isLast Whether the current file is the last file in the directory.
 * @param array $excludeDirs An array of directories to exclude from the scanning process.
 * @return string The formatted tree structure of the directory contents.
 */
function scanDirectory($dir, $prefix = '', $isLast = true, $excludeDirs = [])
{

    $output  = '';
    $files   = scandir($dir);
    $total   = count($files);
    $current = 1;

    foreach ($files as $file) 
    {

        if ($file === '.' || $file === '..' || in_array($file, $excludeDirs)) 
        {

            continue;

        }


        $isLastFile = $current === $total;

        $output .= $prefix;
        $output .= $isLast ? '└── ' : '├── ';
        $output .= $file . PHP_EOL;

        $nextPrefix = $prefix . ($isLastFile ? '    ' : '│   ');

        if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) 
        {

            if ($file === 'exceptions') 
            {

                // Handle exceptions folder separately
                $exceptionsDir = $dir . DIRECTORY_SEPARATOR . $file;
                $output .= $nextPrefix . '│' . PHP_EOL;
                $output .= $nextPrefix . '└── ' . $file . PHP_EOL;
                $output .= scanDirectory($exceptionsDir, $nextPrefix . '    ', $isLastFile, $excludeDirs);

            } 
            else 
            {

                $output .= scanDirectory($dir . DIRECTORY_SEPARATOR . $file, $nextPrefix, $isLastFile, $excludeDirs);

            }

        }

        $current++;

    }

    return $output;

}






/**
 * Generates a tree structure of the given directory and saves it to the specified output file.
 *
 * @param string $outputFile The path to the output file where the tree structure will be saved.
 * @param string $directory The directory to scan for generating the tree structure.
 * @throws Exception If there is an error while scanning the directory or saving the tree structure.
 */
function generateTree($outputFile, $directory)
{

    // Define directories to exclude from the scan
    $excludeDirs = ['vendor', 'node_modules'];

    $tree = scanDirectory($directory, '', true, $excludeDirs);

    // Save the tree structure to the output file
    file_put_contents($outputFile, $tree);

    // echo "Tree structure saved to $outputFile\n";
}
