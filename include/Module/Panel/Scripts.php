<?php

namespace MerapiPanel\Module\Panel;

use MerapiPanel\Box\Module\__Fragment;

class Scripts extends __Fragment
{

    protected $scripts = [];
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    function add($id, $script)
    {
        $this->scripts[$id] = $script;
    }


    function remove($id)
    {
        unset($this->scripts[$id]);
    }



    function getScripts()
    {
        $scripts = "";
        foreach ($this->scripts as $id => $script) {
            $script = trim($script);
            preg_match('/<script.*?src=["\']([^"\']+)["\'].*?><\/script>/im', $script, $matches);
            if (isset($matches[1])) {
                $script = "<script id='$id' src='$matches[1]' type='text/javascript'></script>\n";
            } else {
                $script = preg_replace("/<script[^>]*>/", "<script id='$id' type='text/javascript'>", $script) . "\n";
            }
            $scripts .= $script;
        }

        return $scripts;
    }

}
