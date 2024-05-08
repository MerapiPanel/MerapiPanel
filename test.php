<?php

function getFileType($file)
{
    if (is_dir($file)) {
        return "folder";
    }
    $type_list = [
        "image" => ["jpg", "png", "gif", "webp", "ico", "bmp", "svg", "webp"],
        "video" => ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"],
    ];
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    for ($i = 0; $i < count($type_list); $i++) {
        if (in_array($extension, $type_list[array_keys($type_list)[$i]])) {
            return array_keys($type_list)[$i];
        }
    }
    return "file";
}


echo getFileType("test.jpg");