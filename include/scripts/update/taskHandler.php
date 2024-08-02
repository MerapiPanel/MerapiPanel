<?php

global $taskList;


$taskList[0] = function ($target_dir, $lastest) {
    $name    = basename($target_dir);
    $output_zip  = __DIR__ . "/data/" . $name . ".zip";
    if (!file_exists(dirname($output_zip))) {
        mkdir(dirname($output_zip), 0755, true);
    }
    return downloadLastestRelease($output_zip, $lastest);
};



$taskList[1] = function ($target_dir, $lastest) {
    $name     = basename($target_dir);
    $zipFile  = __DIR__ . "/data/" . $name . ".zip";
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo(preg_replace("/\.\w+/", "", $zipFile));
        $zip->close();
        unlink($zipFile);
        return true;
    }
    throw new Exception("Caught an error while unpack a module archive", 500);
};

$taskList[2] = function ($target_dir, $lastest) {
    $name    = basename($target_dir);
    $extracted_dir  = __DIR__ . "/data/" . $name;
    $extractedFirstNode = glob("{$extracted_dir}/*", GLOB_ONLYDIR);
    if (count($extractedFirstNode) == 1) {
        $extracted_dir = $extractedFirstNode[0];
    }

    // return moveFilesAndFolders($extracted_dir, $target_dir);
};


$taskList[3] = function ($target_dir, $lastest) {
    // clean 
};



function startTask($task_id, $target_dir, $lastest)
{
    if (empty($target_dir)) throw new Exception("Target dir should not be empty", 401);
    global $taskList;
    if (!in_array($task_id, array_keys($taskList))) {
        throw new Exception("Unknown task id", 401);
    }
    return $taskList[$task_id]($target_dir, $lastest);
};







function moveFilesAndFolders($sourceDir, $destinationDir)
{
    if (!is_dir($sourceDir)) {
        throw new Exception("Source directory does not exist.");
    }

    // Create the destination directory if it doesn't exist
    if (!is_dir($destinationDir)) {
        if (!mkdir($destinationDir, 0755, true)) {
            throw new Exception("Failed to create destination directory.");
        }
    }

    // Recursively move files and directories
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {

        $relativePath = $items->getSubPathName(); // Get the relative path of the item
        $destinationPath = $destinationDir . DIRECTORY_SEPARATOR . $relativePath;

        if ($item->isDir()) {
            // Create directory if it doesn't exist
            if (!is_dir($destinationPath) && !mkdir($destinationPath, 0755, true)) {
                throw new Exception("Failed to create directory: $destinationPath");
            }
        } else {
            // Move file and replace if it exists
            if (!rename($item, $destinationPath)) {
                throw new Exception("Failed to move file: $item to $destinationPath");
            }
        }
    }

    // Optionally remove the source directory after moving all files
    removeDirectory($sourceDir);
}

// Function to recursively remove a directory and its contents
function removeDirectory($dir)
{
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $filePath = "$dir/$file";
        (is_dir($filePath)) ? removeDirectory($filePath) : unlink($filePath);
    }
    rmdir($dir);
}
