<?php
namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
use Symfony\Component\Filesystem\Path;
use Throwable;

class Api extends __Fragment
{
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    // Function to count files recursively with specific extensions
    private function countFilesByExtensions($directory, $extensions)
    {
        $total = 0;

        // Get all items (files and directories) in the current directory
        $items = glob(rtrim($directory, '/') . '/*');

        if ($items === false) {
            return 0; // If no items found, return 0
        }

        foreach ($items as $item) {
            if (is_file($item)) {
                // Check if the file has one of the specified extensions
                $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (in_array($extension, $extensions)) {
                    $total++; // Increment count for valid file
                }
            } elseif (is_dir($item)) {
                // Recursively count files in subdirectory
                $total += $this->countFilesByExtensions($item, $extensions);
            }
        }

        return $total;
    }


    function fetch($folder = "/", $extensions = [])
    {

        $folder = ltrim($folder, '/');
        if (strpos($folder, "/content") === 0) {
            $folder = substr($folder, 7);
        }
        if (strpos($folder, "content/") === 0) {
            $folder = substr($folder, 7);
        }
        $root = Path::join($_ENV['__MP_CWD__'], "content", $folder);
        return $this->scanFiles($root, $extensions);
    }

    public function fetchFolder()
    {

        function nested($dir)
        {
            $stack = [];
            foreach (glob($dir . "/*", GLOB_ONLYDIR) as $file) {
                $stack[] = [
                    "name" => basename($file),
                    "children" => (is_dir($file)) ? nested($file) : [],
                ];
            }

            return $stack;
        }

        return nested($_ENV['__MP_CWD__'] . "/content");
    }

    public function fetchFile()
    {
        $extensions = ["jpg", "png", "gif", "webp", "ico", "bmp", "mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"];
        $root = $_ENV['__MP_CWD__'] . "/content";
        $stack = [];
        $done = 0;

        // Open the directory handle
        if ($dh = opendir($root)) {
            while (false !== ($filename = readdir($dh))) {

                if ($filename == '.' || $filename == '..') {
                    continue; // Skip current and parent directory pointers
                }

                $filePath = $root . "/" . $filename;

                if (is_dir($filePath)) {
                    // Recursively fetch from nested directories
                    $this->fetchNested($filePath, $stack, $extensions);
                } elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                    // Process files with allowed extensions

                    // Only add files starting from the 'start' index
                    $stack[] = [
                        "name" => $filename,
                        "path" => $this->absoluteToRelativePath($filePath),
                        "type" => in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["jpg", "png", "gif", "webp", "ico", "bmp"])
                            ? "image"
                            : (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"])
                                ? "video"
                                : "file"),
                    ];
                }
            }
            closedir($dh); // Close the directory handle
        }

        return [
            "total" => $this->countFilesByExtensions($root, $extensions),
            "items" => $stack
        ];
    }


    private function scanFiles($directory, $extensions = ["jpg", "png", "gif", "webp", "ico", "bmp", "mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"])
    {

        $pattern = $directory . "/*" . ($extensions ? (count($extensions) > 0 ? ".{" . implode(",", $extensions) . "}" : "") : "");
        $stack = [];

        if ($extensions) {
            foreach (glob($pattern, GLOB_BRACE) as $file) {
                $file = [
                    "name" => basename($file),
                    "path" => $this->absoluteToRelativePath($file),
                    "time" => filemtime($file),
                    "type" => self::getFileType($file),
                ];
                $stack[] = $file;
            }

            foreach (glob($directory . "/*", GLOB_ONLYDIR) as $file) {
                $pattern = $file . "/*" . ($extensions && count($extensions) > 0 ? ".{" . implode(",", $extensions) . "}" : "");
                $files_count = count(glob($pattern, GLOB_BRACE));
                $folder_count = count(glob($file . "/*", GLOB_ONLYDIR));
                $file = [
                    "name" => basename($file),
                    "path" => $this->absoluteToRelativePath($file),
                    "time" => filemtime($file),
                    "type" => "folder",
                    "children_count" => $files_count + $folder_count,
                ];
                $stack[] = $file;
            }
        } else {

            foreach (glob($directory . "/*") as $file) {
                $file = [
                    "name" => basename($file),
                    "path" => $this->absoluteToRelativePath($file),
                    "time" => filemtime($file),
                    "type" => self::getFileType($file),
                    "children_count" => count(glob($file . "/*")),
                ];
                $stack[] = $file;
            }

        }





        return $stack;
    }




    private static function getFileType($file)
    {
        if (is_dir($file)) {
            return "folder";
        }
        $type_list = [
            "image" => ["jpg", "jpeg", "png", "gif", "webp", "ico", "bmp", "svg", "webp"],
            "video" => ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"],
        ];
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION) ?? "");
        for ($i = 0; $i < count($type_list); $i++) {
            if (in_array($extension, $type_list[array_keys($type_list)[$i]])) {
                return array_keys($type_list)[$i];
            }
        }
        return "file";
    }








    private function fetchNested($directory, &$stack, $extensions)
    {
        if ($dh = opendir($directory)) {
            while (false !== ($filename = readdir($dh))) {

                if ($filename == '.' || $filename == '..') {
                    continue; // Skip current and parent directory pointers
                }

                $filePath = $directory . "/" . $filename;

                if (is_dir($filePath)) {
                    // Recursively fetch from nested directories
                    $this->fetchNested($filePath, $stack, $extensions);
                } elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                    // Process files with allowed extensions

                    $stack[] = [
                        "name" => $filename,
                        "path" => $this->absoluteToRelativePath($filePath),
                        "type" => in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["jpg", "png", "gif", "webp", "ico", "bmp"])
                            ? "image"
                            : (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"])
                                ? "video"
                                : "file"),
                    ];

                }
            }
            closedir($dh); // Close the directory handle
        }
    }


    private function absoluteToRelativePath($absolute_path)
    {

        $server_root = strtolower(str_replace('\\', '/', strtolower($_SERVER['DOCUMENT_ROOT'])));
        $absolute_path = strtolower(str_replace('\\', '/', $absolute_path));

        return str_replace($server_root, '', $absolute_path);

    }






    public function upload()
    {

        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception('Permission denied');
        }

        try {


            $files = $_FILES['files'];
            $request = Request::getInstance();


            if (!empty($request->parent())) {
                $root = Path::join($_ENV['__MP_CWD__'], "content", $request->parent());
            } else {

                $root = $_ENV['__MP_CWD__'] . "/content";
                if (!file_exists($root))
                    mkdir($root);
                $root .= "/upload";
                if (!file_exists($root))
                    mkdir($root);

                $root .= "/" . date('Y-m-w');
                if (!file_exists($root)) {
                    mkdir($root);
                }
            }

            $uploaded = [];

            for ($i = 0; $i < count($files['name']); $i++) {
                $temp = $files['tmp_name'][$i];
                $name = $files['name'][$i];
                $path = $root . "/" . $name;

                if (move_uploaded_file($temp, $path)) {

                    $uploaded[] = [
                        "name" => $name,
                        "status" => "success",
                        "src" => $this->absoluteToRelativePath($path),
                        "type" => in_array(pathinfo($path, PATHINFO_EXTENSION), ["img", "png", "jpg", "jpeg", "svg", "gif", "webp", "ico", "bmp"]) ? "image" : (in_array(pathinfo($path, PATHINFO_EXTENSION), ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"]) ? "video" : "file"),
                    ];
                } else {
                    $uploaded[] = [
                        "status" => "failed",
                    ];
                }
            }

            if (count($uploaded) <= 0) {
                throw new \Exception("Upload failed", 401);
            }

            return $uploaded;

        } catch (Throwable $e) {
            throw $e;
        }
    }



    function uploadInfo($id)
    {
        // remove old file in uploads filetime > 1 week
        foreach (glob(__DIR__ . "/uploads/*") as $file) {
            if (filemtime($file) < time() - 3600 * 24 * 7) {
                unlink($file);
            }
        }
        if (empty($id)) {
            return [];
        }
        try {
            $file = Path::join(__DIR__, "uploads", $id);
            if (file_exists($file)) {
                return json_decode(file_get_contents($file));
            }
        } catch (Throwable $e) {
            // throw $e;
        }
        return [];
    }




    function uploadChunk()
    {

        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception('Permission denied');
        }

        if (!isset($_FILES['file'])) {
            throw new \Exception("Invalid file");
        }

        $file = $_FILES['file'];
        $uploader_file_name = Request::getInstance()->http("uploader-file-name");
        $uploader_file_id = Request::getInstance()->http("uploader-file-id");
        $uploader_chunk_number = Request::getInstance()->http("uploader-chunk-number");
        $uploader_chunks_total = Request::getInstance()->http("uploader-chunks-total");
        $uploader_file_folder = Request::getInstance()->http("uploader-file-folder");
        $uploader_file_size = Request::getInstance()->http("uploader-file-size") ?? 0;


        if (!isset($uploader_file_id) || !isset($uploader_chunk_number) || !isset($uploader_chunks_total)) {
            throw new \Exception("Invalid request");
        }

        if (empty($uploader_file_folder) || $uploader_file_folder == "null" || $uploader_file_folder == "undefined") {
            $uploader_file_folder = '/upload/' . date('Y-m-d');
        } else if (strpos($uploader_file_folder, "/content") === 0) {
            $uploader_file_folder = substr($uploader_file_folder, 8);
        } else if (strpos($uploader_file_folder, "content") === 0) {
            $uploader_file_folder = substr($uploader_file_folder, 7);
        }
        // Create upload directory if it doesn't exist
        $root = $_ENV['__MP_CWD__'] . "/content/" . ltrim($uploader_file_folder, '/');

        if (!file_exists($root)) {
            mkdir($root, 0777, true); // Ensure recursive directory creation
        }


        $information = [
            "id" => $uploader_file_id,
            "file_name" => $uploader_file_name,
            "file_id" => $uploader_file_id,
            "file_folder" => $this->absoluteToRelativePath($root),
            "file_size" => $uploader_file_size,
            "file_type" => self::getFileType(Path::join($root, $uploader_file_name)),
            "chunk_number" => $uploader_chunk_number,
            "chunks_total" => $uploader_chunks_total,
            "status" => "uploading",
        ];

        // Move uploaded chunk to the appropriate directory
        $path = Path::join(__DIR__, "uploads", "temp", $uploader_file_id);
        if (!file_exists($path)) {
            mkdir($path, 0777, true); // Ensure recursive directory creation
        }
        $path = Path::join($path, $uploader_chunk_number);

        if (move_uploaded_file($file['tmp_name'], $path)) {

            // Check if all chunks are uploaded
            if ($uploader_chunk_number == $uploader_chunks_total - 1) {
                // Combine chunks into a single file
                $combinedFilePath = Path::join($root, $uploader_file_name);
                $combinedFile = fopen($combinedFilePath, 'ab'); // Open file in append mode

                for ($i = 0; $i <= $uploader_chunks_total - 1; $i++) {
                    $chunkFilePath = Path::join(__DIR__, "uploads", "temp", $uploader_file_id, $i);
                    $chunkFile = fopen($chunkFilePath, 'rb'); // Open chunk file in read mode

                    if ($chunkFile) {
                        // Read chunk content and write it to the combined file
                        while ($data = fread($chunkFile, 8192)) {
                            fwrite($combinedFile, $data);
                        }
                    }

                    if ($chunkFile) {
                        // Close chunk file
                        fclose($chunkFile);
                        // Delete chunk file after merging
                        unlink($chunkFilePath);
                    }
                }

                function rrmdir($dir)
                {
                    foreach (scandir($dir) as $file) {
                        if ('.' === $file || '..' === $file)
                            continue;
                        if (is_dir($dir . '/' . $file))
                            rrmdir($dir . '/' . $file);
                        else
                            unlink($dir . '/' . $file);
                    }
                    return rmdir($dir);
                }

                $information["status"] = "success";
                // Close combined file
                fclose($combinedFile);
                rrmdir(Path::join(__DIR__, "uploads", "temp", $uploader_file_id));
            }

            file_put_contents(Path::join(__DIR__, "uploads", $uploader_file_id), json_encode($information));
            return true;

        } else {

            file_put_contents(Path::join(__DIR__, "uploads", $uploader_file_id), json_encode($information));
            throw new \Exception("Failed to move uploaded file");
        }
    }







    function copy($files, $parent = null, $target = null)
    {

        function xcopy($src, $dest)
        {
            if (!is_dir($dest)) {
                mkdir($dest);
            }
            foreach (scandir($src) as $file) {
                if (!is_readable($src . '/' . $file))
                    continue;
                if (is_dir($src . '/' . $file) && ($file != '.') && ($file != '..')) {
                    mkdir($dest . '/' . $file);
                    xcopy($src . '/' . $file, $dest . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dest . '/' . $file);
                }
            }
        }

        if (is_string($files)) {
            $files = [$files];
        }
        foreach ($files as $file) {

            $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $file);
            $target = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($target) ? $target : ""), $file);

            if (preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower(Path::canonicalize($target . "/.."))) == preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower($source))) {
                throw new \Exception("Target directory cannot be in the source directory");
            }

            if (is_file($source)) {
                copy($source, $target);
            }
            if (is_dir($source)) {
                xcopy($source, $target);
            }
        }
    }


    function move($files, $parent = null, $target = null)
    {

        function xmove($src, $dest)
        {

            if (!is_dir($dest)) {
                mkdir($dest);
            }

            foreach (scandir($src) as $file) {
                if (!is_readable($src . '/' . $file) || $file == '.' || $file == '..')
                    continue;
                if (is_dir($src . '/' . $file) && ($file != '.') && ($file != '..')) {
                    mkdir($dest . '/' . $file);
                    xmove($src . '/' . $file, $dest . '/' . $file);
                } else {
                    rename($src . '/' . $file, $dest . '/' . $file);
                }
            }
            rmdir($src);
        }

        if (is_string($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $file);
            $target = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($target) ? $target : ""), $file);
            if (preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower(Path::canonicalize($target . "/.."))) == preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower($source))) {
                throw new \Exception("Target directory cannot be in the source directory");
            }
            if (is_file($source)) {
                rename($source, $target);
            }
            if (is_dir($source)) {
                xmove($source, $target);
            }
        }
    }


    function rename($path, $name)
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        if (empty($path) || empty($name)) {
            throw new \Exception("Invalid parameters");
        }
        $path = ltrim($path, '/');
        if (strpos($path, "/content") === 0) {
            $path = substr($path, 7);
        }
        if (strpos($path, "content/") === 0) {
            $path = substr($path, 7);
        }
        if (empty($path) || $path == "/" || $path == "content/") {
            throw new \Exception("Cant rename root folder");
        }
        $source = Path::join($_ENV['__MP_CWD__'], 'content', $path);
        $dirname = dirname($source);
        $target = Path::join($dirname, $name);

        if (rename($source, $target)) {
            return true;
        }
        throw new \Exception("Failed to rename file");
    }


    function delete($path)
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        if (empty($path)) {
            throw new \Exception("Invalid parameters");
        }

        function rrmdir($dir)
        {
            foreach (scandir($dir) as $file) {
                if ('.' === $file || '..' === $file)
                    continue;
                if (is_dir($dir . '/' . $file))
                    rrmdir($dir . '/' . $file);
                else
                    unlink($dir . '/' . $file);
            }
            return rmdir($dir);
        }

        $path = ltrim($path, '/');
        if (strpos($path, "/content") === 0) {
            $path = substr($path, 7);
        }
        if (strpos($path, "content/") === 0) {
            $path = substr($path, 7);
        }
        if (empty($path) || $path == "/" || $path == "content/") {
            throw new \Exception("Cant delete root folder");
        }
        $source = Path::join($_ENV['__MP_CWD__'], 'content', $path);
        if (is_file($source)) {
            unlink($source);
            return true;
        }
        if (is_dir($source)) {
            rrmdir($source);
            return true;
        }

        throw new \Exception("Failed to delete file");
    }


    function newFolder($path, $name)
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        if (empty($path) || empty($name)) {
            throw new \Exception("Invalid parameters");
        }
        $path = ltrim($path, '/');
        if (strpos($path, "/content") === 0) {
            $path = substr($path, 7);
        }
        if (strpos($path, "content/") === 0) {
            $path = substr($path, 7);
        }
        $folder = Path::join($_ENV['__MP_CWD__'], 'content', $path, $name);

        if (mkdir($folder, 0777)) {
            return true;
        }


        throw new \Exception("Failed to create folder");
    }
}