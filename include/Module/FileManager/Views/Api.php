<?php
namespace MerapiPanel\Module\FileManager\Views;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use Symfony\Component\Filesystem\Path;

class Api extends __Fragment
{

    protected Module $module;

    function onCreate(Module $module)
    {

        $this->module = $module;
    }

    public function fetch()
    {

        $root = Box::module("FileManager")->props->root;

        error_log("from api: " . $root);
        // if (empty($root)) {
        //     // if root is empty toggle Service
        //     // Box::module('FileManager')->Service;
        //     // $root = $this->module->props->root;
        // }
        // return array_filter(scandir($root, SCANDIR_SORT_DESCENDING), function ($file) {
        //     return !in_array($file, ['.', '..']);
        // });
        
    }


    public function is_file($file)
    {
        return is_file(Path::join($this->module->props->root, $file));
    }
}